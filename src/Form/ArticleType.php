<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['help' => "Votre titre doit faire entre 10 et 255 caractères"])
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'title'])
            ->add('content', null, ['help' => "Votre texte doit contenir au moins 10 caractères"])
            ->add('image', null, ['help' => "Vous devez saisir une url valide"])
//            ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
