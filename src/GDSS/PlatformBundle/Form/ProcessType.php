<?php

namespace GDSS\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcessType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('description', TextareaType::class)
            ->add('participantMin', IntegerType::class, array(
                'label' => 'Nombre de participants minimal'
            ))
            ->add('participantMax', IntegerType::class, array(
                'label' => 'Nombre de participants maximal'
            ))
            ->add('anonymous', ChoiceType::class, array(
                'label' => 'Anonyme',
                'choices' => array(
                    'Anonyme' => 1,
                    'Non Anonyme' => 0
                )
            ))
            ->add('Suivant', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GDSS\PlatformBundle\Entity\Process'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gdss_platformbundle_process';
    }


}
