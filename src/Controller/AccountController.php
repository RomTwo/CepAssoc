<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use Firebase\JWT\JWT;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Add user account
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->findByEmail($account->getEmail())) {
                $manager = $this->getDoctrine()->getManager();
                $encoded = $encoder->encodePassword($account, $account->getPassword());
                $account->setPassword($encoded);
                $manager->persist($account);
                $manager->flush();

                return $this->redirectToRoute('security_connexion', array(), 301);
            }
            $msg = "Cet email a déjà un compte associé !";
        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView(),
            "msg" => $msg
        ));
    }

    public function update(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $currentUserEmail = $this->get('session')->get('_security.last_username');
        $account = $manager->getRepository(Account::class)->findOneBy(array('email' => $currentUserEmail));

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($currentUserEmail != $account->getEmail()) {
                if ($this->findByEmail($account->getEmail())) {
                    $msg = "Cet email a déjà un compte associé !";
                    return $this->render('account/update.html.twig', array(
                        "form" => $form->createView(),
                        "msg" => $msg
                    ));
                }
                $this->get('session')->set('_security.last_username', $account->getEmail());
            }
            $encoded = $encoder->encodePassword($account, $account->getPassword());
            $account->setPassword($encoded);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('account/update.html.twig', array(
            "form" => $form->createView(),
        ));
    }

    /**
     * Generate token (save in database) and send and email at the user to change his password
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('POST') && $this->findByEmail($request->request->get('email'))) {
            $manager = $this->getDoctrine()->getManager();
            $account = $manager->getRepository(Account::class)->findOneBy(
                array(
                    'email' => $request->request->get('email')
                )
            );

            $token = $this->generateToken();
            $account->setTokenForgetPass($token);
            $manager->flush();
            $this->sendEmail($account->getEmail(), $token);

            $this->addFlash("msg", "Un mail vient de vous être envoyé");
        } else {
            $this->addFlash("msg", "Une erreur s'est produite");
        }

        return $this->redirectToRoute('security_connexion', array(), 301);
    }

    /**
     * Reset the user password after click on link present in the email send by the previous function (forgotPassword)
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $encoder, $token)
    {
        try {
            JWT::decode($token, $_ENV['PRIVATE_KEY'], array($_ENV['ALG']));

            $manager = $this->getDoctrine()->getManager();
            $account = $manager->getRepository(Account::class)->findOneBy(['tokenForgetPass' => $token]);

            if ($account !== null) {
                if ($request->isMethod('POST')) {
                    if ($request->request->get('password1') === $request->request->get('password2')
                        && preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $request->request->get('password1'))) {

                        $encoded = $encoder->encodePassword($account, $request->request->get('password1'));
                        $account->setPassword($encoded);
                        $account->setTokenForgetPass(null);
                        $manager->flush();

                        return $this->redirectToRoute('security_connexion', array(), 301);
                    }
                    return $this->render('account/resetPassword.html.twig', array(
                            'token' => $token,
                            'error' => 'Mot de passe incorrect',
                        )
                    );
                }
                return $this->render('account/resetPassword.html.twig', array('token' => $token));
            }
            return $this->redirectToRoute('error_404');
        } catch (\Exception $e) {
            return $this->redirectToRoute('error_404');
        }

    }


    /**
     * Check an existence of a user
     *
     * @param string $email
     * @return bool
     */
    private function findByEmail(string $email)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(
            array(
                'email' => $email
            )
        );

        return $account != null ? true : false;
    }

    /**
     * Generate a JWT token
     *
     * @return string
     */
    private function generateToken()
    {
        $payload = array(
            'iat' => time(),
            'exp' => time() + 1800
        );
        $token = JWT::encode($payload, $_ENV['PRIVATE_KEY'], $_ENV['ALG']);

        return $token;
    }

    /**
     * Send an email to reset the user password. It contains a link for reset the user password
     *
     * @param $mail
     * @param $token
     */
    private function sendEmail($mail, $token)
    {

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername($_ENV['MAILER_MAIL'])
            ->setPassword($_ENV['MAILER_PASSWORD']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Réinitialisation mot de passe Association Cep Poitiers Gymnastique'))
            ->setFrom([$_ENV['MAILER_MAIL'] => 'Association Cep Poitiers Gymnastique'])
            ->setTo([$mail])
            ->setBody($this->msgHtml($token), 'text/html')
            ->setCharset('UTF-8');


        $result = $mailer->send($message);
    }

    /**
     * This is a body of the email (email for reset the user password)
     *
     * @param $token
     * @return string
     */
    private function msgHtml($token)
    {
        $msg = '<p>Réinitialisation de votre mot de passe en cliquant <a href="' . $_ENV['MAILER_URL'] . $token . '">ici</a></p>';

        return $msg;

    }

}
