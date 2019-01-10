<?php

namespace GDSS\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SujetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Titre', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('But', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('Domaine', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('DateDebut', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, array(
                'format' => 'dd-MM-yyyy H:m',
                'widget' => 'single_text',
            ))
            ->add('DateFin', \Symfony\Component\Form\Extension\Core\Type\DateTimeType::class, array(
                'format' => 'dd-MM-yyyy H:m',
                'widget' => 'single_text',
            ))
            ->add('Contexte', TextareaType::class)
            ->add('Suivant', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GDSS\PlatformBundle\Entity\Sujet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gdss_platformbundle_sujet';
    }


}
