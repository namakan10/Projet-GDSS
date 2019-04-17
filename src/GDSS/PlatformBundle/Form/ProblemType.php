<?php

namespace GDSS\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProblemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Titre'
            ))
            ->add('goal', TextType::class, array(
                'label' => 'But'
            ))
            ->add('domain', TextType::class, array(
                'label' => 'Domaine'
            ))
            ->add('context', TextareaType::class, array(
                'label' => 'Contexte'
            ))
            ->add('datestart', DateTimeType::class, array(
                'format' => 'dd-MM-yyyy H:m',
                'widget' => 'single_text'
            ))
            ->add('dateend', DateTimeType::class, array(
                'format' => 'dd-MM-yyyy H:m',
                'widget' => 'single_text'
            ))
            ->add('Suivant', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GDSS\PlatformBundle\Entity\Problem'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gdss_platformbundle_problem';
    }


}
