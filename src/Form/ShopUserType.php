<?php

namespace App\Form;

use App\Entity\Shop;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ShopUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop',EntityType::class, array("label"=>"Boutique", "class"=>Shop::class, "placeholder"=>"Sélectionner Une Boutique"))
                ->add('user',EntityType::class, array('class'=>User::class,"placeholder"=>"Préciser l'utilisateur", "choice_label"=>"prenom"));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\ShopUser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'go_caissebundle_shopuser';
    }


}
