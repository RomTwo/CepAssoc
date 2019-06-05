<?php

namespace App\Form;

use App\Entity\Account;
use App\Transformer\ArrayToStringTransformer;
use App\Transformer\DateToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('sex', ChoiceType::class, array(
                    'choices' => array(
                        'M' => 'M',
                        'F' => 'F'
                    ),
                    'expanded' => false
                )
            )
            ->add('birthDate', TextType::class, array(
                    'attr' => array(
                        'class' => 'datepicker'
                    )
                )
            )
            ->add('address', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('city', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', HiddenType::class, array(
                    'attr' => array(
                        'value' => 'Didier@86'
                    )
                )
            )
            ->add('roles', ChoiceType::class, array(
                    'choices' => array(
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_MODERATOR' => 'ROLE_MODERATOR',
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'
                    ),
                    'expanded' => false
                )
            )
            ->add('valid', SubmitType::class, array('label' => 'Modifier'));

        $builder->get('roles')->addModelTransformer(new ArrayToStringTransformer());
        $builder->get('birthDate')->addModelTransformer(new DateToStringTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
