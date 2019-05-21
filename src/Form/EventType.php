<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Job;
use App\Repository\JobRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('startDate', DateTimeType::class)
            ->add('endDate', DateTimeType::class)
            ->add('address', TextType::class)
            ->add('description', TextareaType::class)
            ->add('jobs', EntityType::class, array(
                    'class' => Job::class,
                    'choice_label' => 'getName',
                    'query_builder' => function (JobRepository $er) {
                        return $er->createQueryBuilder('j')
                            ->orderBy('j.name', 'ASC');
                    },
                    'expanded' => true,
                    'multiple' => true
                )
            )
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
