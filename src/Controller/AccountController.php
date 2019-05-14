<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Services\CaptchaCheck;
use App\Services\ForgotPasswordEmail;
use App\Services\GenerateToken;
use Firebase\JWT\JWT;
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
     * @param CaptchaCheck $captchaCheck
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, UserPasswordEncoderInterface $encoder, CaptchaCheck $captchaCheck)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = null;

        if ($form->isSubmitted() && $form->isValid() && $request->request->has('recaptcha_response')) {
            if ($captchaCheck->captchaIsValid($request->request->get('recaptcha_response'))) {
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
            $msg = "Captcha invalide";
        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView(),
            "msg" => $msg
        ));
    }

    /**
     * The user can modify your profile (personnal informations and his identifiants)
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
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
            "form" => $form->createView()
        ));
    }

    /**
     * Generate token (save in database) and send and email at the user to change his password
     *
     * @param Request $request
     * @param ForgotPasswordEmail $forgotPasswordEmail
     * @param GenerateToken $generateToken
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function forgotPassword(Request $request, ForgotPasswordEmail $forgotPasswordEmail, GenerateToken $generateToken)
    {
        if ($request->isMethod('POST') && $this->findByEmail($request->request->get('email'))) {
            $manager = $this->getDoctrine()->getManager();
            $account = $manager->getRepository(Account::class)->findOneBy(
                array(
                    'email' => $request->request->get('email')
                )
            );

            $token = $generateToken->generateToken();
            $account->setTokenForgetPass($token);
            $manager->flush();
            $forgotPasswordEmail->sendEmail($account->getEmail(), $token);

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
}
