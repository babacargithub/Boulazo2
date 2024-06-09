<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\OpBanque;

class OpBanqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compte')
            ->add('typeOp', ChoiceType::class, [
                "label" => "Type Opération",
                "choices" => [
                    "Versement Sortant" => OpBanque::VERSEMENT,
                    "Versement Entrant" => OpBanque::RETRAIT,
                    "Virement Emis" => OpBanque::VIREMENT_EMIS,
                    "Virement Reçu" => OpBanque::VIREMENT_RECU
                ]
            ])
            ->add('montant')
            ->add('justif')
            ->add('caisse_operation', ActiveCaisseType::class, [
                'data_class' => OpBanque::class,
                "session" => $options["session"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpBanque::class,
        ])->setRequired('session');
    }

    public function getBlockPrefix(): string
    {
        return 'go_caissebundle_opbanque';
    }
}
