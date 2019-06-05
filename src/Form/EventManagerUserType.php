<?php

namespace App\Form;

use App\Entity\Job;
use App\Transformer\DatetimeToStringTransformer;
use App\Transformer\JobToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventManagerUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('startDate', TextType::class, array(
                    'attr' => array(
                        'class' => 'dateTimePickerEvent'
                    )
                )
            )
            ->add('endDate', TextType::class, array(
                    'attr' => array(
                        'class' => 'dateTimePickerEvent'
                    )
                )
            )
            ->add('job', ChoiceType::class, array(
                    'attr' => array(
                        'class' => 'selectJob'
                    ),
                    'choices' => $options['jobsEvent'],
                    'choice_label' => function (Job $job) {
                        return $job->getName();
                    },
                    'choice_value' => function (Job $job = null) {
                        return $job ? $job->getId() : '';
                    }
                )
            )
            ->add('submit', SubmitType::class, array('label' => 'Participer'));

        $builder->get('startDate')->addModelTransformer(new DatetimeToStringTransformer());
        $builder->get('endDate')->addModelTransformer(new DatetimeToStringTransformer());
        $builder->get('job')->addModelTransformer(new JobToStringTransformer());

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('jobsEvent');
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
