<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '1',
                    'maxlenght' => '10',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('illustration', null, [
                'attr' => [
                    'class' => 'form-select w-25'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('tag', EntityType::class, [
                'attr' => [
                    'class' => 'form-select w-25 h-25'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'class' => Tag::class,
                'multiple' => true,
            ])
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
            'data_class' => Article::class,
        ]);
    }
}
