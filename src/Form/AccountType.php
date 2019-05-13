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
            ->add('firstName', TextType::class, array('label' => 'Prénom : '))
            ->add('lastName', TextType::class, array('label' => 'Nom : '))
            ->add('sex', ChoiceType::class, array(
                'choices' => array(
                    'M' => 'M',
                    'F' => 'F'
                ),
                'expanded' => false,
                'label' => 'Civilité : '
            ))
            ->add('birthDate', DateType::class, array('label' => 'Date de naissance : ',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 100),
            ))
            ->add('address', TextType::class, array('label' => 'Adresse : '))
            ->add('zipCode', TextType::class, array('label' => 'Code postal : '))
            ->add('city', TextType::class, array('label' => 'Ville : '))
            ->add('email', EmailType::class, array('label' => 'Adresse mail : '))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe : '))
            ->add('valid', SubmitType::class, array('label' => 'S\'inscrire : '));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'cascade_calidation' => true,
        ]);
    }
}
