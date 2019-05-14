<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => "Prenom",
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom de famille",
                ],
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y') - 100),
            ])
            ->add('isGAFJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge GAF",
            ])
            ->add('GAFJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('isGAMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge GAM",
            ])
            ->add('GAMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('isTeamGYMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required' => false,
                'label' => "Juge TeamGYM",
            ])
            ->add('teamGYMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                    'placeholder' => "Niveau",
                ],
                'required' => false,
            ])
            ->add('wantsAJudgeTraining', ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'required' => false,
            ])
            ->add('volunteerForTrainingHelp', ChoiceType::class, [
                'choices' => [
                    'Jamais' => "jamais",
                    'Occasionnellement' => "occasionnellement",
                    'Régulièrement' => "regulierement",
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'jamais',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('volunteerForClubLife', ChoiceType::class, [
                'choices' => [
                    'Jamais' => "jamais",
                    'Occasionnellement' => "occasionnellement",
                    'Régulièrement' => "regulierement",
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'jamais',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('registrationType', ChoiceType::class, [
                'attr' => [
                    'id' => 'gender',
                ],
                'choices' => [
                    'Renouvellement' => 'renouvellement',
                    'Nouvelle inscription' => 'nouveau',
                    'Mutation' => 'mutation'
                ],
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
            ])
            ->add('firstNameRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Prenom du représentant 1"
                ],
            ])
            ->add('lastNameRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom du représentant 1"
                ],
            ])
            ->add('firstNameRep2', TextType::class, [
                'attr' => [
                    'placeholder' => "Prenom du représentant 2"
                ],
                'required' => false,
            ])
            ->add('lastNameRep2', TextType::class, [
                'attr' => [
                    'placeholder' => "Nom du représentant 2"
                ],
                'required' => false,
            ])
            ->add('emailRep1', EmailType::class, [
                'attr' => [
                    'placeholder' => "Email du représentant 1"
                ],
            ])
            ->add('emailRep2', EmailType::class, [
                'attr' => [
                    'placeholder' => "Email du représentant 2"
                ],
                'required' => false,
            ])
            ->add('cityRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Ville du représentant 1"
                ],
            ])
            ->add('cityRep2', TextType::class, [
                'attr' => [
                    'placeholder' => "Ville du représentant 2"
                ],
                'required' => false,
            ])
            ->add('addressRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Adresse du représentant 1"
                ],
            ])
            ->add('addressRep2', TextType::class, [
                'attr' => [
                    'placeholder' => "Adresse du représentant 2"
                ],
                'required' => false,
            ])
            ->add('zipCodeRep1', NumberType::class, [
                'attr' => [
                    'placeholder' => "Zip code du représentant 1"
                ],
            ])
            ->add('zipCodeRep2', NumberType::class, [
                'attr' => [
                    'placeholder' => "Zip code du représentant 2"
                ],
                'required' => false,
            ])
            ->add('professionRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Profession du représentant 1"
                ],
            ])
            ->add('professionRep2', TextType::class, [
                'attr' => [
                    'placeholder' => "Profession du représentant 2"
                ],
                'required' => false,
            ])
            ->add('phoneRep1', NumberType::class, [
                'attr' => [
                    'placeholder' => "Numéro de téléphone du représentant 1"
                ],
            ])
            ->add('phoneRep2', NumberType::class, [
                'attr' => [
                    'placeholder' => "Numéro de téléphone du représentant 2"
                ],
                'required' => false,
            ])
            ->add('paymentType', ChoiceType::class, [
                'choices' => [
                    'Espèces' => "especes",
                    'Chèque' => "cheque",
                ],
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'required' => false,
            ])
            ->add('imageRight', ChoiceType::class, [
                'choices' => [
                    'J\'autorise' => true,
                    'Je n\'autorise pas' => false,
                ],
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'required' => false,
        ]);
    }
}
