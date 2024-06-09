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

class ActiveCaisseType extends AbstractType
{
    protected $session;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->session=$options['session'];
        $builder->add('caisse', EntityType::class, 
                        array("class"=>Caisse::class, 
                            "choice_label"=>"libelle",
                            "query_builder"=>function(EntityRepository $repository){
                            $qb = $repository->createQueryBuilder('c');
                             return $qb
                            // find all users where 'deleted' is NOT '1'
                            ->where("c.id=:id")
                            ->setParameter('id', $this->session->get("caisse_id"));
                            }
            ));
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true
        ))->setRequired("session");
                
    }

    

}
