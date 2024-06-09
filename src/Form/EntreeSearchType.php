<?php

namespace  App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
class EntreeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type_search', ChoiceType::class, [
                'placeholder' => 'Rechercher par',
                'choices' => [
                    'Total des Entrées' => 'total',
                    'Liste Des Entrées' => 'liste',
                    'Type D\'Entrée' => 'type_entree',
                ],
            ])
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ]);

    }


    public function getName()
    {
        return 'go_caissebundle_entree_search_type';
    }
}
