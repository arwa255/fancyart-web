<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


        ->add('idtype', EntityType::class, [
            'class' => Type::class,
            'choice_label' => 'type',
            'multiple' => false,
            'expanded' => false,
        ])
            ->add('text_rec', TextareaType::class, [
                'label' => 'Text reclamation',
                'attr' => [
                    'rows' => 15, // Set the number of visible rows to 5
                    'style' => 'font-size: 16px;', // Optional: Set a custom font size
                ],
            ])
            ->add('Reclamer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
