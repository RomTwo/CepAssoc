<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $account = new Account();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $account);

        $formBuilder
            ->add('typeRegistration',  ChoiceType::class, array(
                'choices' => array(
                    'Renouvellement' => 'renouvellement',
                    'Nouvelle inscription' => 'nouveau',
                    'Transfert' => 'transfert',
                ),
                'expanded' => true,
                'multiple' => false))
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('birthdate', DateTimeType::class)
            ->add('sex', ChoiceType::class, array(
                'choices' => array(
                    'Homme' => 'H',
                    'Femme' => 'F',
                ),
                'expanded' => true,
                'multiple' => false
            ))
            ->add('save', SubmitType::class, ['label' => 'Create Post']);

        $form = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $data = $request->request->get('form');
            $this->add($data['firstname'],
                       $data['lastname'],
                       $data['sex'],
                       $data['birthdate'],
                       $data['zipcode'],
                       $data['address'],
                       $data['email'],
                       $data['city'],
                       $data['password'],
                       $data['typeRegistration']);
        }

        return $this->render('registration/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function add($firstname, $lastname, $sex, $birthdate, $zipcode, $address, $email, $city, $password, $typeRegistration){
        $entityManager = $this->getDoctrine()->getManager();

        $account = new Account();
        $account->setFirstName($firstname);
        $account->setLastName($lastname);
        $account->setSex($sex);
        $account->setBirthDate($birthdate);
        $account->setZipCode($zipcode);
        $account->setAddress($address);
        $account->setEmail($email);
        $account->setCity($city);
        $account->setPassword($password);
        $account->setTypeRegistration($typeRegistration);

        $entityManager->persist($account);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

    }
}
