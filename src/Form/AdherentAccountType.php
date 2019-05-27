<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('professionRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Profession"
                ],
            ])
            ->add('phoneRep1', TextType::class, [
                'attr' => [
                    'placeholder' => "Numéro de téléphone"
                ],
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
            ])
            ->add('volunteerComment', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Plus d'informations sur vos disponibilités..."
                ]
            ])
            ->add('medicalCertificate', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez votre certificat médical"
                ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('bulletinN2Allianz', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez votre bulletin N2 Allianz "
                ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('healthQuestionnaireFile', FileType::class, [
                'attr' => [
                    'placeholder' => "Choisissez le questionnaire de santé"
                ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('healthQuestionnaire', HealthQuestionnaireType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
            'cascade_validation' => true,
        ]);
    }
}
