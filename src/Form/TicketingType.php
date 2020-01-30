<?php


namespace App\Form;

use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberOfAdult', IntegerType::class, [
                'label' => 'Nombre d\'adultes'
            ])
            ->add('numberOfChild', IntegerType::class, [
                'label' => 'Nombre d\'enfants'
            ])
            ->add('numberOfSenior', IntegerType::class, [
                'label' => 'Nombre de sénior'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}