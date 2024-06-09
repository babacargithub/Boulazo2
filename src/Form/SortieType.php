<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use App\Entity\Caisse;
use App\Entity\Sortie;

class SortieType extends AbstractType
{
    protected $session;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder->add('caisse_operation',ActiveCaisseType::class, array(
        'data_class' => Sortie::class, "session"=>$options["session"]))
                ->add('typeSortie')
                ->add('montant', MoneyType::class, array("currency"=>"CFA", "scale"=>0,"grouping"=>true))
                ->add('motif')
                ->add('beneficiaire')
                ->add('commentaire')
                ;
                
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Sortie'
        ))->setRequired("session");
        
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'go_caissebundle_sortie';
    }


}
