<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Painting;
use App\Entity\Technical;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaintingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
               'label' => 'Titre de l\'oeuvre'
            ])
           ->add('author' ,TextType::class, [
              'label' => 'Nom de l\'auteur'
           ])
            ->add('description', TextareaType::class, [
               'label' => 'Description de l\'oeuvre'
            ])
            ->add('createdAt', DateType::class, [
               'label' => 'Date de création du tableau',
               'widget' => 'single_text',
               'input' => 'datetime_immutable'
            ])
            ->add('height', IntegerType::class,[
               'label' => 'Hauteur de l\'oeuvre'
            ])
            ->add('width', IntegerType::class, [
               'label' => 'Largeur de l\'oeuvre'
            ])
//            ->add('image')
            ->add('isPublished', ChoiceType::class, [
               'label' => 'Publier',
               'choices' => ['Oui' => 1, 'Non' => 0]
            ])
            ->add('category', EntityType::class, [
               'class' => Category::class,
               'choice_label' => 'name',
               'placeholder' => 'Choisissez..'
            ])
            ->add('technical', EntityType::class, [
               'class' => Technical::class,
               'choice_label' => 'name',
               'placeholder' => 'Choisissez..'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Painting::class,
        ]);
    }
}
