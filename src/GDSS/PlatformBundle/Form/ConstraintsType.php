<?php

namespace GDSS\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstraintsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', ChoiceType::class, array(
                'label' => 'Option',
                'choices' => array(
                    'Le nombre minimum de participant est 6 :' => array("-Décision prise à partir de zéro" => '1', "-Durée de chaque étape est inférieur ou égale à 10" => '2'),
                    'Contrainte sur le nombre de contribution' => array("-Nombre de contribution supérieure à 80" => "3"),
                    'Contrainte sur la base de décision :' => array('-Pas de contrainte sur un ordre à suivre' => '4', '-Contrainte sur un ordre spécifique à suivre ET Pas de contrainte sur le nombre maximum de partcipants' => '5'),
                    'Pas de contraintes sur le nombre de participants :' => array('-Il y a au moins deux sous problème' => '7', "-Contrainte sur la base de décision qui soit bien élaborée ET Contrainte sur l'état d'évaluation de la base" => '6' )

                )
            ))
            ->add('Suivant', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GDSS\PlatformBundle\Entity\Constraints'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gdss_platformbundle_constraints';
    }


}
