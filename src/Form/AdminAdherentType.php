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
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'EN ATTENTE',
                    'En manque de document(s)' => 'EN MANQUE DE DOCUMENT',
                    'En manque de paiement' => 'EN MANQUE DE PAIEMENT',
                    'En manque de document(s) et paiement' => 'EN MANQUE DE DOCUMENT ET PAIEMENT',
                    'Valide' => 'VALIDE',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('birthDate', DateType::class)
            ->add('isGAFJudge', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
            ])
            ->add('GAFJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
            ])
            ->add('isGAMJudge', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
            ])
            ->add('GAMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
            ])
            ->add('isTeamGYMJudge', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
            ])
            ->add('teamGYMJudgeLevel', NumberType::class, [
                'attr' => [
                    'value' => 0,
                ],
                'required'   => false,
            ])
            ->add('wantsAJudgeTraining',ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
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
                'label' => "Certificat medical : ",
                'required' => false,
                'data_class' => null,
            ))
            ->add('bulletinN2Allianz', FileType::class, array(
                'required' => false,
                'data_class' => null,
            ))
            ->add('healthQuestionnaireFile', FileType::class, array(
                'required' => false,
                'data_class' => null,
            ));
        $builder->get('medicalCertificate')->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            if (null === $event->getData()) {
                $event->setData($event->getForm()->getData());
            }
        });
        $builder->get('bulletinN2Allianz')->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            if (null === $event->getData()) {
                $event->setData($event->getForm()->getData());
            }
        });
        $builder->get('healthQuestionnaireFile')->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
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