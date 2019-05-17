<?php


namespace App\Form;


use App\Entity\Activity;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TimeSlotRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminActivityType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class)
            ->add('startDate',\Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('type',TextType::class)

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('categorie')
                        ->orderBy('categorie.name', 'ASC');
                },
                'choice_label' => 'name',
                "multiple" => false
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
            'data_class' => Activity::class
        ]);
    }

}