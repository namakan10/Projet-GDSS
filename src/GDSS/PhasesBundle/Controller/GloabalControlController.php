<?php

namespace GDSS\PhasesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GDSS\PlatformBundle\Entity\Phases;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GloabalControlController extends Controller
{
    public function modifPhaseAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        if($sujet->getUser() != $this->getUser()){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $process = $em->getRepository('GDSSPlatformBundle:Processus')->findOneBy(array(
            'sujet' => $sujet
        ));

        $phaseC = $em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus' => $process,
            'nom' => 'Phase de Comprehension Collective du problème'
        ));

        $phaseG = $em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus' => $process,
            'nom' => 'Phase de Generations des solutions'
        ));

        var_dump($phaseC->getDateStart());

        $defaultdata = array('name' => 'description');

        //Creation du formulaire
        $form = $this->createFormBuilder($defaultdata)
            ->add('DateDebut1', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                //FOMATE LA DATE DIRECTEMENT EN TIMESTAMP POUR EFFECTUER DES OPERATIONS AVEC
                'data' => $phaseC->getDateStart(),
            ))
            ->add('Duree1', NumberType::class, array(
                'data' => $phaseC->getDuree()
            ))
            ->add('Periode1', ChoiceType::class, array(
                'data' => $phaseC->getPeriode(),
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDebut2', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                'data' => $phaseG->getDateStart(),
                //'input' => 'timestamp',
            ))
            ->add('Duree2', NumberType::class, array(
                'data' => $phaseG->getDuree()
            ))
            ->add('Periode2', ChoiceType::class, array(
                'data' => $phaseG->getPeriode(),
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDebut3', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                'input' => 'timestamp',
            ))
            ->add('Duree3', NumberType::class)
            ->add('Periode3', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDecision', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
            ))
            ->add('Terminer', SubmitType::class)
            ->getForm();



        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            //On verifie si les donneées entrée sont bonnes
            if ($form->isValid()) {


                /*
                 *Phase de comprehension collective du problème
                 */

                $startComp = $form["DateDebut1"]->getData();
                $add = $startComp;
                $startComp = date('Y-m-d H:i:s', $startComp);
                $startComp = new \DateTime(trim($startComp));
                $dureecomp = $form["Duree1"]->getData()*$form["Periode1"]->getData();
                $endComp = $add + $dureecomp;
                $endComp = date('Y-m-d H:i:s', $endComp);
                $endComp = new \DateTime(trim($endComp));
                $Comp = new Phases();
                $Comp->setNom('Phase de Comprehension Collective du problème');
                $Comp->setDateStart($startComp);
                $Comp->setProcessus($process);
                $Comp->setDateEnd($endComp);

                /*
                 *Phase de generations
                 */
                $startGene = $form["DateDebut2"]->getData();
                $add = $startGene;
                $startGene = date('Y-m-d H:i:s', $startGene);
                $startGene = new \DateTime(trim($startGene));
                $dureeGene = $form["Duree2"]->getData()*$form["Periode2"]->getData();
                $endGene = $add + $dureeGene;
                $endGene = date('Y-m-d H:i:s', $endGene);
                $endGene = new \DateTime(trim($endGene));
                $Gene = new Phases();
                $Gene->setNom('Phase de Generations des solutions');
                $Gene->setDateStart($startGene);
                $Gene->setProcessus($process);
                $Gene->setDateEnd($endGene);

                /*
                 * Phase de Negociations
                 */
                $startNego = $form["DateDebut3"]->getData();
                $add = $startNego;
                $startNego = date('Y-m-d H:i:s', $startNego);
                $startNego = new \DateTime(trim($startNego));
                $dureeNego = $form["Duree3"]->getData()*$form["Periode3"]->getData();
                $endNego = $add + $dureeNego;
                $endNego = date('Y-m-d H:i:s', $endNego);
                $endNego = new \DateTime(trim($endNego));
                $Nego = new Phases();
                $Nego->setNom('Phase de Negociations de confrontations des points de vue');
                $Nego->setDateStart($startNego);
                $Nego->setProcessus($process);
                $Nego->setDateEnd($endNego);

                //prise de decision
                $startDecis = $form["DateDecision"]->getData();
                $Decision = new Phases();
                $Decision->setNom('Prise de Decision');
                $Decision->setDateStart($startDecis);
                $Decision->setProcessus($process);

                $dureemin = $process->getDureeMin();
                $dureemax = $process->getDureeMax();

                if($dureecomp < $dureemin OR $dureecomp > $dureemax OR $dureeGene < $dureemin OR $dureeGene > $dureemax
                    OR $dureeNego < $dureemin OR $dureeNego > $dureemax){
                    $erreur = "Erreur ! Les durées doivent être comprises entre la durée minimale et maximale du processus !";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }

                else if($startComp<$sujet->getDateDebut()){
                    $erreur = "Erreur sur la date de debut de la phase de Comprehension collective du probème. Elle dois pas etre ulterieure à la date de debut du sujet";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }
                else if($startComp>$sujet->getDateFin()){
                    $erreur = "Erreur sur la date de debut de la phase de Comprehension collective du probème. Elle dois pas être anterieure à la date de debut du sujet";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }
                else if($endComp>$startGene){
                    $erreur = "Erreur sur la date de debut de la phase de Génération. Elle dois commencer après la fin de la phase de comprehension collective du problème";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }
                else if($endGene > $startNego){
                    $erreur = "Erreur sur la date de debut de la phase de Negociation. Elle dois commencer après la fin de la phase de génération des solutions";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }
                else if($endNego>$startDecis){
                    $erreur = "Erreur sur la date de prise de décision. Elle doit se faire après la phase de Negociation";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }
                else if($startDecis>$sujet->getDateFin()){
                    $erreur = "Erreur sur la date de prise de decision, celle-ci être ulterieure à la date de fin du sujet";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                    ));
                }


                else{
                    $em->persist($Comp);
                    $em->flush();

                    $em->persist($Gene);
                    $em->flush();

                    $em->persist($Nego);
                    $em->flush();

                    $em->persist($Decision);
                    $em->flush();

                    return $this->redirectToRoute('gdss_platform_sujet_vue', array('id'=>$id));
                }

            }

        }
        return $this->render('@GDSSPhases/phases_view/phase_edit.html.twig', array(
            'form' => $form->createView()
        ));

    }
}