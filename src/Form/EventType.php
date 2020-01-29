<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Price;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('country')
            ->add('town')
            ->add('performanceDate')
            ->add('price', EntityType::class, [
                'class'        => Price::class,
                'choice_label' => 'name',
                'required'     => false,
                'expanded'     => true,
                'multiple'     => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
