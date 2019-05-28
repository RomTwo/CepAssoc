<?php

namespace App\Form;

use App\Entity\Account;
use App\Transformer\DateToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array(
                    'label' => 'Prénom : ',
                    'attr' => array(
                        'placeholder' => 'Prénom'
                    )
                )
            )
            ->add('lastName', TextType::class, array(
                    'label' => 'Nom : ',
                    'attr' => array(
                        'placeholder' => 'Nom'
                    )
                )
            )
            ->add('sex', ChoiceType::class, array(
                    'choices' => array(
                        'Masculin' => 'M',
                        'Feminin' => 'F'
                    ),
                    'expanded' => false,
                    'label' => 'Civilité : ',
                    'attr' => array(
                        'placeholder' => 'Civilité'
                    )
                )
            )
            ->add('birthDate', TextType::class, array(
                    'attr' => array(
                        'class' => 'datepicker'
                    ),
                )
            )
            ->add('address', TextType::class, array(
                    'label' => 'Adresse : ',
                    'attr' => array(
                        'placeholder' => 'Adresse'
                    )
                )
            )
            ->add('zipCode', TextType::class, array(
                    'label' => 'Code postal : ',
                    'attr' => array(
                        'placeholder' => 'Code postal'
                    )
                )
            )
            ->add('email', EmailType::class, array(
                    'label' => 'Adresse mail : ',
                    'attr' => array(
                        'placeholder' => 'Email'
                    )
                )
            )
            ->add('password', PasswordType::class, array(
                    'label' => 'Mot de passe : ',
                    'attr' => array(
                        'placeholder' => 'Mot de passe',
                        'class' => 'tool',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'right',
                        'title' => '8 à 15 caractères minimum, 1 caractère spécial, 1 chiffre, 1 majuscule',
                    )
                )
            )
            ->add('addAccountAdherent', null, [
                'label' => "Je suis un compétiteur"
            ])
            ->add('children', CollectionType::class, [
                'entry_type' => AdherentAccountType::class,
                'delete_empty' => true,
                'required' => false,
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit']
            )
            ->add('valid', SubmitType::class, array(
                'label' => 'S\'inscrire',
                'attr' => array(
                    'class' => 'btn btn-success btn-block'
                )
            ));
        $builder->get('birthDate')->addModelTransformer(new DateToStringTransformer());
    }

    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $data = $form->getData();

        $children = $data->getChildren();

        if ($children != null) {
            if ($data->getFirstName() != null) {
                $children[0]->setFirstName($data->getFirstName());
                $children[0]->setFirstNameRep1($data->getFirstName());
            }

            if ($data->getLastName() != null) {
                $children[0]->setLastName($data->getLastName());
                $children[0]->setLastNameRep1($data->getLastName());
            }

            $children[0]->setBirthDate($data->getBirthDate());

            if ($data->getEmail() != null) {
                $children[0]->setEmailRep1($data->getEmail());
            }

            if ($data->getAddress() != null) {
                $children[0]->setAddressRep1($data->getAddress());
            }

            if ($data->getZipCode() != null) {
                $children[0]->setZipCodeRep1($data->getZipCode());
            }

            if ($data->getSex() != null) {
                $children[0]->setSex($data->getSex());
            }

        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            "allow_extra_fields" => true
        ]);
    }

}
