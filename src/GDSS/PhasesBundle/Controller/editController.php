<?php
/**
 * Created by IntelliJ IDEA.
 * User: Namakan
 * Date: 29/01/2019
 * Time: 09:51
 */

namespace GDSS\PhasesBundle\Controller;


use GDSS\PlatformBundle\Form\PhasesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GDSS\PlatformBundle\Entity\Phases;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class editController extends Controller
{
    public function editphaseAction($id, $phasename){

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        if($phasename == "Comp"){
            $phase = $data["Comp"];
        }
        elseif ($phasename == "Gene"){
            $phase = $data["Gene"];
        }
        elseif ($phasename == "Nego"){
            $phase = $data["Nego"];
        };

        $defaultdata = array('name' => 'description');

        //Creation du formulaire
        $form = $this->createFormBuilder($defaultdata)
            ->add('DateDebut', DateTimeType::class, array(
                'format' => 'dd-MM-yyyy H:m',
                //FOMATE LA DATE DIRECTEMENT EN TIMESTAMP POUR EFFECTUER DES OPERATIONS AVEC
                'input' => 'timestamp',
                'widget' => 'single_text',
                'html5' => false,
            ))
            ->add('Dureemin', NumberType::class)
            ->add('Periodemin', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('Dureemax', NumberType::class)
            ->add('Periodemax', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('Terminer', SubmitType::class)
            ->getForm();


        return $this->render('@GDSSPhases/phases_view/phase_edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}