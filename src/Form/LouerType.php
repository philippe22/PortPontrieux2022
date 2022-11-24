<?php

namespace App\Form;

use App\Entity\Louer;
use App\Entity\Emplacement;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class LouerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomBateau', TextType::class)
            ->add('portAttache', TextType::class)
            ->add('dateArrivee', DateType::class)
            ->add('dateDepart', DateType::class)
            ->add('reglement', CheckboxType::class, array('required' => false))
            // Pour ne pas avoir les emplacements occupés dans les propositions
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->andWhere('e.disponible = 0');
                },
                'choice_label' => 'id'
            ]);
        $builder->add('save', SubmitType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Louer::class,
        ]);
    }
}
