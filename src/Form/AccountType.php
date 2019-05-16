<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
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
                        'M' => 'M',
                        'F' => 'F'
                    ),
                    'expanded' => false,
                    'label' => 'Civilité : ',
                    'attr' => array(
                        'placeholder' => 'Civilité'
                    )
                )
            )
            ->add('birthDate', DateType::class, array(
                    'label' => 'Date de naissance : ',
                    'widget' => 'choice',
                    'years' => range(date('Y'), date('Y') - 100),
                    'attr' => array(
                        'placeholder' => 'Date de naissance'
                    )
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
            ->add('city', TextType::class, array(
                    'label' => 'Ville : ',
                    'attr' => array(
                        'placeholder' => 'Ville'
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
            ->add('valid', SubmitType::class, array('label' => 'S\'inscrire'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class
        ]);
    }

}
