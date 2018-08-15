<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 31/05/2018
 * Time: 10:07
 */

namespace GDSS\PlatformBundle\Controller;

use GDSS\PlatformBundle\Entity\Contraintes;
use GDSS\PlatformBundle\Entity\Criteres;
use GDSS\PlatformBundle\Entity\Phases;
use GDSS\PlatformBundle\Entity\Processus;
use GDSS\PlatformBundle\Entity\Repertoire;
use GDSS\PlatformBundle\Entity\Sujet;
use GDSS\PlatformBundle\Form\ContraintesType;
use GDSS\PlatformBundle\Form\CriteresType;
use GDSS\PlatformBundle\Form\ProcessusType;
use GDSS\PlatformBundle\Form\SujetType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;


class addController extends Controller
{
    public function addSujetAction(Request $request){
        $sujet = new Sujet();
        $user = $this->getUser();

        //Creation du formulaire
        $form = $this->createForm(SujetType::class, $sujet);

        //Si la requete est en post
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            //On verifie si les donneées entrée sont bonnes
            if($form->isValid()){

                $datemin = $form["DateDebut"]->getData();
                $datemax = $form["DateFin"]->getData();

                $now1 = new \DateTime('now');

                if($datemin<$now1 OR $datemin>$datemax OR $datemin==$datemax){
                    $erreur = "Les dates ne sont pas conformes ! ";
                    return $this->render('@GDSSPlatform/Sujets_Basic_View/addSujet.html.twig', array(
                        'form' => $form->createView(),
                        'erreur' => $erreur
                    ));
                }

                else{
                    $sujet->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sujet);
                    $em->flush();
                    $id=$sujet->getId();
                    return $this->redirectToRoute('gdss_platform_contrainte', array('id'=> $id));
                }
            }
        }

        return $this->render('@GDSSPlatform/Sujets_Basic_View/addSujet.html.twig', array('form'=>$form->createView()));
    }

    public function addContraiteCritereAction(Request $request, $id){

        $user = $this->getUser();
        $critere = new Criteres();
        $contrainte = new Contraintes();

        //Pour casté la chaine en entier
        $id2 = intval($id);

        //On cherche l'entité correspondant à l'id envoyé
        $repository = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Sujet');
        $sujet = $repository->find($id2);

        /*
         * CREATOR TEST
         */
        if($user != $sujet->getUser()){
            return $this->redirectToRoute('gdss_platform_sujets');
        }



        //Creation des deux formulaires
        $formContraintes = $this->createForm(ContraintesType::class, $contrainte);
        $formCriteres = $this->createForm(CriteresType::class, $critere);

        //On recupère le sujet correspondant aux contraintes et aux critères
        $em = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Criteres');
        $criterelist = $em->findBy(array(
            'sujet' => $sujet,
        ));

        $em1 = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Contraintes');
        $contraintelist = $em1->findBy(array(
            'sujet' => $sujet
        ));


        //Si un des deux formulaire est envoyé
        if($request->isMethod('POST')){
            $formCriteres->handleRequest($request);
            $formContraintes->handleRequest($request);

            //On verifie si les donneées entrée sont bonnes
            if($formContraintes->isValid()) {
                $contrainte->setSujet($sujet);
                $em1 = $this->getDoctrine()->getManager();
                $em1->persist($contrainte);
                $em1->flush();

                $em1 = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Contraintes');
                $contraintelist = $em1->findBy(array(
                    'sujet' => $sujet
                ));


                return $this->render('@GDSSPlatform/Sujets_Basic_View/addContrainteCritere.html.twig', array(
                    'formCritere' => $formCriteres->createView(),
                    'formContrainte'=>$formContraintes->createView(),
                    'id' => $id,
                    'criterelist' => $criterelist,
                    'contraintelist' => $contraintelist,
                ));
            }

            if($formCriteres->isValid()){
                $critere->setSujet($sujet);
                $em = $this->getDoctrine()->getManager();
                $em->persist($critere);
                $em->flush();

                $em = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Criteres');
                $criterelist = $em->findBy(array(
                    'sujet' => $sujet,
                ));


                return $this->render('@GDSSPlatform/Sujets_Basic_View/addContrainteCritere.html.twig', array(
                    'formCritere'=>$formCriteres->createView(),
                    'formContrainte'=>$formContraintes->createView(),
                    'id'=>$id,
                    'criterelist' => $criterelist,
                    'contraintelist' => $contraintelist,
                ));


            }


        }

        //Sinon la vue est simplement renvoyé
        return $this->render('@GDSSPlatform/Sujets_Basic_View/addContrainteCritere.html.twig', array(
            'formCritere'=>$formCriteres->createView(),
            'formContrainte'=>$formContraintes->createView(),
            'id'=>$id,
            'criterelist' => $criterelist,
            'contraintelist' => $contraintelist,
        ));

    }

    public function addprocessusAction(Request $request, $id)
    {
        $defaultdata = array('name' => 'description');
        $form = $this->createFormBuilder($defaultdata)
            ->add('Nom', TextType::class)
            ->add('Description', TextareaType::class)
            ->add('anonyme', ChoiceType::class, array(
                'choices' => array(
                    'Anonyme' => 'Oui',
                    'Non anonyme' => 'Non'
                )
            ))
            ->add('Suivant', SubmitType::class)
            ->getForm();


        $process = new Processus();
        $repository = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Sujet');
        $sujet = $repository->find($id);

        if($sujet->getUser() == $this->getUser()){
            //Creation du formulaire

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);

                //On verifie si les donneées entrée sont bonnes
                if ($form->isValid()) {

                        $process->setNom($form['Nom']->getData());
                        $process->setDescription($form['Description']->getData());
                        $process->setAnonyme($form['anonyme']->getData());
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($process);
                        $em->flush();
                        $sujet->setProcessus($process);
                        $em->persist($sujet);
                        $em->flush();
                        return $this->redirectToRoute('gdss_platform_add_phase', array(
                            'id'=>$id
                        ));
                }
            }
        }
        else{
            return $this->redirectToRoute('gdss_platform_sujets', array(
                'erreur' => "Acces refuser. Vous n'avez pas créer ce sujet !"
            ));
        }



        return $this->render('GDSSPlatformBundle:Sujets_Basic_View:addprocessus.html.twig', array('form'=>$form->createView()));
    }

    public function addphasesAction(Request $request, $id){

        /*
         * Recuperation du processus concernée
         */
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository('GDSSPlatformBundle:Sujet')->find($id);
        $process = $sujet->getProcessus();


        /*
         * Verification creator
         */
        if($sujet->getUser() != $this->getUser()){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $defaultdata = array('name' => 'description');

        //Creation du formulaire
        $form = $this->createFormBuilder($defaultdata)
            ->add('DateDebut1', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                //FOMATE LA DATE DIRECTEMENT EN TIMESTAMP POUR EFFECTUER DES OPERATIONS AVEC
                'input' => 'timestamp',
                'widget' => 'single_text',
            ))
            ->add('Duree1min', NumberType::class)
            ->add('Periode1min', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('Duree1max', NumberType::class)
            ->add('Periode1max', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDebut2', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                'input' => 'timestamp',
                'widget' => 'single_text',
            ))
            ->add('Duree2min', NumberType::class)
            ->add('Periode2min', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('Duree2max', NumberType::class)
            ->add('Periode2max', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDebut3', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                'input' => 'timestamp',
                'widget' => 'single_text',
            ))
            ->add('Duree3min', NumberType::class)
            ->add('Periode3min', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('Duree3max', NumberType::class)
            ->add('Periode3max', ChoiceType::class, array(
                'choices' => array(
                    'Minutes' => 60,
                    'Heures' => 3600,
                    'Jours' => 86400
                )
            ))
            ->add('DateDecision', DateTimeType::class, array(
                'date_format' => 'ddMMMMyyyy',
                'widget' => 'single_text',
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
                var_dump($startComp);
               /*
                $add = $startComp;
                $startComp = date('Y-m-d H:i:s', $startComp);
                $startComp = new \DateTime(trim($startComp));
                $dureecomp = $form["Duree1max"]->getData()*$form["Periode1max"]->getData();
                $endComp = $add + $dureecomp;
                $endComp = date('Y-m-d H:i:s', $endComp);
                $endComp = new \DateTime(trim($endComp));
                $Comp = new Phases();
                $Comp->setDureemin($form["Duree1min"]->getData());
                $Comp->setPeriodemin($form["Periode1min"]->getData());
                $Comp->setDureemax($form["Duree1max"]->getData());
                $Comp->setPeriodemax($form["Periode1max"]->getData());
                $Comp->setNom('Phase de Comprehension Collective du problème');
                $Comp->setDateStart($startComp);
                $Comp->setProcessus($process);
                $Comp->setDateEnd($endComp);*/

                /*
                 *Phase de generations
                 */
                $startGene = $form["DateDebut2"]->getData();
                var_dump($startGene);
                die();
                $add = $startGene;
                $startGene = date('Y-m-d H:i:s', $startGene);
                $startGene = new \DateTime(trim($startGene));
                $dureeGene = $form["Duree2max"]->getData()*$form["Periode2max"]->getData();
                $endGene = $add + $dureeGene;
                $endGene = date('Y-m-d H:i:s', $endGene);
                $endGene = new \DateTime(trim($endGene));
                $Gene = new Phases();
                $Gene->setDureemin($form["Duree2min"]->getData());
                $Gene->setPeriodemin($form["Periode2min"]->getData());
                $Gene->setDureemax($form["Duree2max"]->getData());
                $Gene->setPeriodemax($form["Periode2max"]->getData());
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
                $dureeNego = $form["Duree3max"]->getData()*$form["Periode3max"]->getData();
                $endNego = $add + $dureeNego;
                $endNego = date('Y-m-d H:i:s', $endNego);
                $endNego = new \DateTime(trim($endNego));
                $Nego = new Phases();
                $Nego->setDureemin($form["Duree3min"]->getData());
                $Nego->setPeriodemin($form["Periode3min"]->getData());
                $Nego->setDureemax($form["Duree3max"]->getData());
                $Nego->setPeriodemax($form["Periode3max"]->getData());
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


                if($startComp<$sujet->getDateDebut()){
                    $erreur = "Erreur sur la date de debut de la phase de Comprehension collective du probème. Elle dois pas etre ulterieure à la date de debut du sujet";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                        'sujet' => $sujet
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
                        'sujet' => $sujet
                    ));
                }
                else if($endGene > $startNego){
                    $erreur = "Erreur sur la date de debut de la phase de Negociation. Elle dois commencer après la fin de la phase de génération des solutions";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                        'sujet' => $sujet
                    ));
                }
                else if($endNego>$startDecis){
                    $erreur = "Erreur sur la date de prise de décision. Elle doit se faire après la phase de Negociation";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                        'sujet' => $sujet
                    ));
                }
                else if($startDecis>$sujet->getDateFin()){
                    $erreur = "Erreur sur la date de prise de decision, celle-ci être ulterieure à la date de fin du sujet";
                    return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
                        'form' => $form->createView(),
                        'id' => $id,
                        'erreur' => $erreur,
                        'sujet' => $sujet
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

                    return $this->redirectToRoute('gdss_platform_sujets');
                }

            }

        }
        return $this->render('@GDSSPlatform/Phases/definitions_phases.html.twig', array(
            'form' => $form->createView(),
            'sujet' => $sujet
        ));
    }

}