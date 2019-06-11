<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Entity\Document;
use App\Entity\TimeSlot;
use App\Form\AccountType;
use App\Services\CaptchaCheck;
use App\Services\ForgotPassword;
use App\Services\GenerateToken;
use App\Services\Utilitaires;
use Dompdf\Dompdf;
use Dompdf\Options;
use Firebase\JWT\JWT;
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
                            $this->setPrice($adherent, $utilitaires->delimiter($request->request->get("idsOfTimeSlots")));
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
                    if (!$utilitaires->isValidateHealthQuestionnaire($adherent->getHealthQuestionnaire())) {
                        $adherent->setHealthQuestionnaire(null);
                    } else {
                        $this->downloadPDF($adherent);
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
            )
        );
    }

    /**
     * The user can modify your profile (personnal informations and his identifiants)
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public
    function update(Request $request, UserPasswordEncoderInterface $encoder, Utilitaires $utilitaires)
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
        return $this->render('account/update.html.twig', array(
                "form" => $form->createView(),
                "error" => $msg,
                "city" => $account->getCity()
            )
        );
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
    public
    function forgotPassword(Request $request, ForgotPassword $forgotPasswordEmail, GenerateToken $generateToken, CaptchaCheck $captchaCheck)
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
    public
    function resetPassword(Request $request, UserPasswordEncoderInterface $encoder, $token)
    {
        try {
            JWT::decode($token, $_ENV['PRIVATE_KEY'], array($_ENV['ALG']));

            $manager = $this->getDoctrine()->getManager();
            $account = $manager->getRepository(Account::class)->findOneBy(['tokenForgetPass' => $token]);

            if ($account) {
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
                return $this->render('account/resetPassword.html.twig', array(
                        'token' => $token
                    )
                );
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
    private
    function findByEmail(string $email)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(
            array(
                'email' => $email
            )
        );

        return $account != null ? true : false;
    }

    private
    function isValidate($adherent)
    {
        if (is_null($adherent->getSex())) {
            return false;
        }
        return true;
    }


    private
    function downloadPDF($adherent)
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
        $fileId = md5(uniqid());
        file_put_contents('uploads/' . $fileId, $dompdf->output());
        $adherent->setHealthQuestionnaireFile(new Document($fileId, $adherent->getFirstName() . "_" . $adherent->getLastName() . "_QuestionnaireDeSante_CEPPoitiers.pdf"));
    }

    public
    function generatePDF($id)
    {
        $user = $this->getUser();
        $adherent = $this->getDoctrine()->getRepository(Adherent::class)->find($id);
        $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());
        if ($adherent && ($this->searchChildren($account->getChildren(), $id) || (($user->getRoles()[0] == "ROLE_ADMIN") || ($user->getRoles()[0] == "ROLE_SUPER_ADMIN")))) {
            $html = $this->render('account/generateHealthQuestionnairePDF.html.twig', [
                'adherent' => $adherent,
            ])->getContent();
            $pdfOptions = new Options();
            $dompdf = new Dompdf($pdfOptions);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($adherent->getFirstName() . "_" . $adherent->getLastName() . "_QuestionnaireDeSante_CEPPoitiers.pdf", [
                "Attachment" => true
            ]);
        } else {
            $this->addFlash('error', "Erreur dans la requête.");
            return $this->redirectToRoute("home");
        }
    }

    private
    function searchChildren($adherents, $id){
        foreach($adherents as $struct) {
            if ($id == $struct->getId()) {
                return true;
            }
        }
        return false;
    }

    private
    function setPrice($adherent, $idsOfTimeSlot)
    {
        if ($idsOfTimeSlot) {
            $activities = array();
            foreach ($idsOfTimeSlot as $value) {
                $timeSlot = $this->getDoctrine()->getRepository(TimeSlot::class)->find($value);
                if (!in_array($timeSlot->getActivity()->getId(), $activities, true)) {
                    array_push($activities, $timeSlot->getActivity()->getId());
                }
                $timeSlot->addAdherent($adherent);
            }
            $price = 0;
            foreach ($activities as $value) {
                $activity = $this->getDoctrine()->getRepository(Activity::class)->find($value);
                $price += $activity->getPrice();
            }
            $adherent->setRegistrationCost($price);
        }

    }
}
