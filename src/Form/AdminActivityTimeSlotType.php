<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Category;
use App\Transformer\DateToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminActivityTimeSlotType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('startDate', TextType::class, array(
                    'attr' => array(
                        'class' => 'datepicker'
                    )
                )
            )
            ->add('type', ChoiceType::class, array(
                    'choices' => array(
                        'SECTEUR LOISIRS' => 'SECTEUR LOISIRS',
                        'SECTEUR COMPETITIF' => 'SECTEUR COMPETITIF',

                    ),
                    'multiple' => false,
                )
            )
            ->add('category', EntityType::class, array(
                    'class' => Category::class,
                    'choice_label' => 'name',
                    "multiple" => false
                )
            )
            ->add('timeSlot', CollectionType::class, array(
                    'entry_type' => TimeSlotType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                )
            );
        $builder->get('startDate')->addModelTransformer(new DateToStringTransformer());

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => Activity::class
            )
        );
    }

}