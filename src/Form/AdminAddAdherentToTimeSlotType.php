<?php


namespace App\Form;

use App\Entity\TimeSlot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\AdherentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAddAdherentToTimeSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city')
            ->add('adherents', EntityType::class, array(
                'class' => 'App\Entity\Adherent',
                'choice_label' => 'id',
                'label' => 'Choix adherents',
                'expanded' => true,
                'multiple' => true,
                'attr' => ['class' => 'select2', 'data-autocomplete-url' => '']
                //'mapped' => false
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimeSlot::class,
        ]);
    }


}