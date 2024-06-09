<?php

namespace App\Form;

use App\Entity\Entree;
//use App\Form\ActiveCaisseType;
use App\Entity\TypeEntree;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('caisse_operation',ActiveCaisseType::class, ['data_class' => Entree::class, "session"=>$options["session"]])
           ->add('typeEntree',EntityType::class,[ "class"=>TypeEntree::class, 'label'=>"Type d'entrée"])
           ->add('libelle',TextType::class,["label"=>"Libellé de l'entrée"])
           ->add('montant',)
           ->add('commentaire', TextareaType::class,["help"=>"S'il y a des informations supplémentaires, mettez les dans les commentaires"]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Entree::class,
        ))->setRequired("session");
    }


}
