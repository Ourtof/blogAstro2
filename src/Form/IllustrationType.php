<?php

namespace App\Form;

use App\Entity\Illustration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IllustrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO : mettre en force filetype
        $builder
            ->add('nomFichier', FileType::class)
            // ->add('article', ChoiceType::class, [
            //     'attr' => [
            //         'class' => 'form-select w-25 h-25'
            //     ],
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            //     // 'class' => Tag::class,
            //     'multiple' => true,
            // ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4 rounded-pill btn-lg'
                ],
                'label' => 'Soumettre'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Illustration::class,
        ]);
    }
}
