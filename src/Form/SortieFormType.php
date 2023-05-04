<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('duree', TimeType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/\b([2-9]|[1-9][0-9])\b/',
                        'match' => true,
                        'message' => 'Il doit y avoir au moins deux participants'
                    ])
                ]
            ])
            ->add('infosSortie')
            ->add('lieu', EntityType::class, [
                'class' => 'App\Entity\Lieu',
                'choice_label' => function($campus) {
                    return $campus->__toString();
                }
            ])
            ->add('campus', EntityType::class, [
                'class' => 'App\Entity\Campus',
                'choice_label' => 'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
