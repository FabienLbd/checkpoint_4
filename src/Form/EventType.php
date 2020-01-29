<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Price;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('country', CountryType::class)
            ->add('town', TextType::class)
            ->add('performanceDate', DateType::class)
            ->add('prices', EntityType::class, [
                'class'        => Price::class,
                'choice_label' => 'name',
                'expanded'     => true,
                'multiple'     => true,
                'by_reference' => false,
            ])
            ->add('eventImageFile', FileType::class, [
                'label' => 'Image de l\'évènement',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'application/pdf'
                        ],
                        'mimeTypesMessage' => 'Formats de fichier acceptés : Jpeg, Png',
                    ])
                ],
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
