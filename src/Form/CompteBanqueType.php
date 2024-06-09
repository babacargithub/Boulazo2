<?php

namespace App\Form;

use App\Entity\CompteBanque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteBanqueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('libelle',TextType::class,["label"=>"Appellation du compte","help"=>"Mettez ce qui vous permet de reconnaitre facilement ce compte"])
            ->add('numCompte',TextType::class,['label'=>"Numéro compte banque"])
            ->add('banque')
            ->add('solde',MoneyType::class,["label"=>"Solde de départ","currency"=>"CFA"]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => CompteBanque::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'go_caissebundle_comptebanque';
    }


}
