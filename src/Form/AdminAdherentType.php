<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('birthDate', DateType::class)
            ->add('isGAFJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required'   => false,
            ])
            ->add('GAFJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
            ])
            ->add('isGAMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required'   => false,
            ])
            ->add('GAMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
            ])
            ->add('isTeamGYMJudge', null, [
                'attr' => [
                    'value' => false,
                ],
                'required'   => false,
            ])
            ->add('teamGYMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
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
                'data' => 'jamais'
            ])
            ->add('volunteerForClubLife', ChoiceType::class, [
                'choices' => [
                    'Jamais' => "jamais",
                    'Occasionnellement' => "occasionnellement",
                    'Régulièrement' => "regulierement",
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'jamais'
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
            ])
            ->add('firstNameRep1', TextType::class
            )
            ->add('lastNameRep1', TextType::class
            )
            ->add('firstNameRep2')
            ->add('lastNameRep2')
            ->add('emailRep1', EmailType::class)
            ->add('emailRep2', EmailType::class, [
                'required' => false
            ])
            ->add('addressRep1', TextType::class
            )
            ->add('addressRep2')
            ->add('zipCodeRep1', NumberType::class
            )
            ->add('zipCodeRep2')
            ->add('professionRep1')
            ->add('professionRep2')
            ->add('phoneRep1', NumberType::class)
            ->add('phoneRep2')
            ->add('paymentType', ChoiceType::class,[
                'choices' => [
                    'Espèces' => "especes",
                    'Chèque' => "cheque",
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('imageRight', ChoiceType::class, [
                'choices' => [
                    'J\'autorise' => true,
                    'Je n\'autorise pas' => false,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('medicalCertificate', FileType::class, array(
                'required' => false,
                'data_class' => null,
            ))
            ->add('bulletinN2Allianz', FileType::class, array(
                'required' => false,
                'data_class' => null,
            ))
            // Admin fields
            ->add('hasMedicalCertificate',  ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
            ])
            ->add('paymentFeesArePaid',  ChoiceType::class, [
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
            ]);

        $builder->get('medicalCertificate')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            if (null === $event->getData()) {
                $event->setData($event->getForm()->getData());
            }
        });

        $builder->get('bulletinN2Allianz')->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            if (null === $event->getData()) {
                $event->setData($event->getForm()->getData());
            }
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}
