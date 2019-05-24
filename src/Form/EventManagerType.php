<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\EventManagement;
use App\Entity\Job;
use App\Repository\AccountRepository;
use App\Transformer\DatetimeToStringTransformer;
use App\Transformer\JobToStringTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('account', EntityType::class, array(
                    'attr' => array(
                        'class' => 'selectAccount'
                    ),
                    'class' => Account::class,
                    'choice_label' => 'getFullName',
                    'query_builder' => function (AccountRepository $rep) {
                        return $rep->createQueryBuilder('a')
                            ->orderBy('a.firstName', 'ASC');
                    }
                )
            )
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
            ->add('place', TextType::class)
            ->add('submit', SubmitType::class);

        $builder->get('startDate')->addModelTransformer(new DatetimeToStringTransformer());
        $builder->get('endDate')->addModelTransformer(new DatetimeToStringTransformer());
        $builder->get('job')->addModelTransformer(new JobToStringTransformer());

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('jobsEvent');
        $resolver->setDefaults([
            'data_class' => EventManagement::class,
        ]);
    }
}
