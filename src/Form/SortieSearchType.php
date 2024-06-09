<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SortieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_search', ChoiceType::class, [
                'placeholder' => 'Rechercher par',
                'choices' => [
                    'Total Sortie' => 'total',
                    'Liste Des Sorties' => 'liste',
                    'Type de Sortie' => 'poste_depense',
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

    public function getBlockPrefix(): string
    {
        return 'go_caissebundle_sortie_search_type';
    }
}
