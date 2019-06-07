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
            ->add('addAccountAdherent', null, array(
                    'label' => "Je suis gymnaste",
                    'data' => false,
                )
            )
            ->add('children', CollectionType::class, array(
                    'entry_type' => AdherentAccountType::class,
                    'delete_empty' => true,
                    'required' => false,
                )
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                array($this, 'onSubmit')
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

        if ($data->getFirstName() && is_null($children[0]->getFirstNameRep1())) {
            $children[0]->setFirstName($data->getFirstName());
            $children[0]->setFirstNameRep1($data->getFirstName());
        }

        if ($data->getLastName() && is_null($children[0]->getLastNameRep1())) {
            $children[0]->setLastName($data->getLastName());
            $children[0]->setLastNameRep1($data->getLastName());
        }

        if (is_null($children[0]->getBirthDate())) {
            $children[0]->setBirthDate($data->getBirthDate());
        }

        if ($data->getEmail() && is_null($children[0]->getEmailRep1())) {
            $children[0]->setEmailRep1($data->getEmail());
        }

        if ($data->getAddress() && is_null($children[0]->getAddressRep1())) {
            $children[0]->setAddressRep1($data->getAddress());
        }

        if ($data->getZipCode() && is_null($children[0]->getZipCodeRep1())) {
            $children[0]->setZipCodeRep1($data->getZipCode());
        }

        if ($data->getSex() && is_null($children[0]->getSex())) {
            $children[0]->setSex($data->getSex());
        }

        if ($data->getCity() && is_null($children[0]->getCityRep1())) {
            $children[0]->setCityRep1($data->getCity());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => Account::class,
                "allow_extra_fields" => true,
            )
        );
    }

}
