<?php


namespace App\Form;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\TimeSlot;
use App\Repository\CategoryRepository;
use App\Repository\TimeSlotRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\AdherentType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'choice_label' => 'firstName',
                'label' => 'Choix adherents',
                'expanded' => true,
                'multiple' => true,
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