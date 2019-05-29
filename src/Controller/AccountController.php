<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Form\AccountType;
use App\Services\CaptchaCheck;
use App\Services\ForgotPassword;
use App\Services\GenerateToken;
use App\Services\Utilitaires;
use Dompdf\Dompdf;
use Dompdf\Options;
use Firebase\JWT\JWT;
use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
    public function add(Request $request, UserPasswordEncoderInterface $encoder, CaptchaCheck $captchaCheck, Utilitaires $utilitaires)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }

        $account = new Account();
        $adherent = new Adherent();
        $account->addChild($adherent);
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = null;

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request)) {
            if ($captchaCheck->captchaIsValid($request->request->get('recaptcha_response')) || true) {
            $account->setCity($request->request->get("account_city"));
            $adherent->setCityRep1($request->request->get("account_city"));
                if (!$this->findByEmail($account->getEmail())) {
                    if ($account->getAddAccountAdherent()) {
                        if ($this->isValidate($adherent)) {
                            $utilitaires->setOtherFields($adherent);
                            $adherent->setRegistrationType("nouveau");
                        } else {
                            $msg = "Attention, il manque des informations pour devenir adhérent";
                            return $this->render('account/index.html.twig', array(
                                "form" => $form->createView(),
                                "msg" => $msg,
                                'activities' => $this->getDoctrine()
                                    ->getRepository(Activity::class)
                                    ->findAll(),
                            ));
                        }
                    } else {
                        $account->removeChild($adherent);
                    }
                    if (!$this->isValidateHealthQuestionnaire($adherent->getHealthQuestionnaire())) {
                        $adherent->setHealthQuestionnaire(null);
                    } else {
                        $this->generatePDF($adherent);
                    }
                    $manager = $this->getDoctrine()->getManager();
                    $encoded = $encoder->encodePassword($account, $account->getPassword());
                    $account->setPassword($encoded);
                    $manager->persist($account);
                    $manager->flush();

                    $this->addFlash('success', "Votre compte vient d'être créé");
                    return $this->redirectToRoute('security_connexion', array(), 301);
                } else {
                    $msg = 'Cette adresse mail est déjà associé à un compte';
                }
            } else {
                $this->addFlash('error', 'Le captcha est invalide');
            }
        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView(),
            "errorMail" => $msg,
            'activities' => $this->getDoctrine()
                ->getRepository(Activity::class)
                ->findAll(),
            'days' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi')
        ));
    }

    /**
     * The user can modify your profile (personnal informations and his identifiants)
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function update(Request $request, UserPasswordEncoderInterface $encoder, Utilitaires $utilitaires)
    {
        $manager = $this->getDoctrine()->getManager();
        $currentUserEmail = $this->get('session')->get('_security.last_username');
        $account = $manager->getRepository(Account::class)->findOneBy(array('email' => $currentUserEmail));
        $oldPassword = $account->getPassword();

        $form = $this->createForm(AccountType::class, $account);
        $form->remove('children');
        $form->remove('addAccountAdherent');
        $form->add('newPassword', PasswordType::class, array(
            'mapped' => false,
            'label' => 'Mot de passe',
            'required' => false,
            'attr' => array(
                'placeholder' => 'Nouveau mot de passe',
                'class' => 'tool',
                'data-toggle' => 'tooltip',
                'data-placement' => 'right',
                'title' => '8 à 15 caractères minimum, 1 caractère spécial, 1 chiffre, 1 majuscule',
            )
        ));
        $form->handleRequest($request);
        $msg = null;

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("account_city"))) {
            $oldPassword = $this->getDoctrine()->getRepository(Account::class)->getOldPassword($currentUserEmail); //get the password of the current user (in database)
            $passwordEntryByUser = $account->getPassword(); //get the user password in the password field of the form
            $account->setPassword($oldPassword); //modify the password to can compare the old password (in database) with the old password entry in the form
            $account->setCity($request->request->get("account_city"));
            if ($currentUserEmail != $account->getEmail()) {
                if ($this->findByEmail($account->getEmail())) {
                    $msg = "Cet email a déjà un compte associé";
                    return $this->render('account/update.html.twig', array("form" => $form->createView(), "errorMail" => $msg));
                }
                $this->get('session')->set('_security.last_username', $account->getEmail());
            }

            if (!empty($request->request->get('newPassword'))) {
                if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/', $request->request->get('newPassword'))) {
                    if (!$encoder->isPasswordValid($account, $passwordEntryByUser)) {
                        $msg = "Votre mot de passe actuel est incorrect";
                        return $this->render('account/update.html.twig', array("form" => $form->createView(), "errorOldPassFalse" => $msg));
                    }
                    $encoded = $encoder->encodePassword($account, $request->request->get('newPassword'));
                    $account->setPassword($encoded);
                } else {
                    $msg = "Votre nouveau mot de passe est incorrect";
                    return $this->render('account/update.html.twig', array("form" => $form->createView(), "errorNewPassFalse" => $msg));
                }
            } else if (!$encoder->isPasswordValid($account, $passwordEntryByUser)) {
                $msg = "Votre mot de passe actuel est incorrect";
                return $this->render('account/update.html.twig', array("form" => $form->createView(), "errorOldPassFalse" => $msg));
            }
            $manager->flush();
            $this->addFlash('success', "Votre compte a été modifié");
            return $this->redirectToRoute('home');
        }
        return $this->render('account/update.html.twig', array("form" => $form->createView(), "error" => $msg, "city" => $account->getCity()));
    }

    /**
     * Generate token (save in database) and send and email at the user to change his password
     *
     * @param Request $request
     * @param ForgotPassword $forgotPasswordEmail
     * @param GenerateToken $generateToken
     * @param CaptchaCheck $captchaCheck
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function forgotPassword(Request $request, ForgotPassword $forgotPasswordEmail, GenerateToken $generateToken, CaptchaCheck $captchaCheck)
    {
        if ($request->isMethod('POST') && $request->request->has('recaptcha_response')) {
            if ($this->findByEmail($request->request->get('email'))) {
                if ($captchaCheck->captchaIsValid($request->request->get('recaptcha_response'))) {

                    $manager = $this->getDoctrine()->getManager();
                    $account = $manager->getRepository(Account::class)->findOneBy(
                        array(
                            'email' => $request->request->get('email')
                        )
                    );

                    $token = $generateToken->generateJwtToken();
                    $account->setTokenForgetPass($token);
                    $manager->flush();
                    $forgotPasswordEmail->sendEmail($account->getEmail(), $token);

                    $this->addFlash('success', "Un mail vient de vous être envoyé");
                } else {
                    $this->addFlash('error', "Le captcha est invalide");
                }
            } else {
                $this->addFlash('error', "Cette adresse mail n'existe pas");
            }
        } else {
            $this->addFlash('error', "Une erreur c'est produite");
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

                        $this->addFlash('success', "Votre mot de passe vient d'être modifié");
                        return $this->redirectToRoute('security_connexion', array(), 301);
                    }

                    $this->addFlash('error', "Mot(s) de passe(s) incorrect(s) ou différents");
                    return $this->render('account/resetPassword.html.twig', array(
                            'token' => $token
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

    private function isValidate($adherent)
    {
        if ($adherent->getSex() == null) {
            return false;
        }
        return true;
    }

    private function isValidateHealthQuestionnaire($healthQuestionnaire)
    {
        if ($healthQuestionnaire->getHasMemberOfFamilyDiedHeartAttack() == null) {
            return false;
        }

        if ($healthQuestionnaire->getHasPainChest() == null) {
            return false;
        }

        if ($healthQuestionnaire->getHasAsthma() == null) {
            return false;
        }

        return true;
    }

    public function generatePDF($adherent)
    {
        $html = $this->render('account/generateHealthQuestionnairePDF.html.twig', [
            'adherent' => $adherent,
        ])->getContent();//Cette ligne permet de générer l'HTML d'une page twig.
        //L'option 'getContent()' permet quant à elle de générer cette page sans les informations
        //fournis par domPDF comme des informations de requêtes...
        $pdfOptions = new Options();
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = md5(uniqid()) . '.pdf';
        file_put_contents('uploads/' . $filename, $dompdf->output());
        $adherent->setHealthQuestionnaireFile($filename);
    }
}
