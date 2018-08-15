<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 16/07/2018
 * Time: 12:06
 */

namespace GDSS\PhasesBundle\Controller;


use GDSS\PhasesBundle\Entity\GenerationComment;
use GDSS\PhasesBundle\Entity\GenerationContribution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;


class GenerationThinkletController extends Controller
{
    public function freebrainstormingContributionAction($id, Request $request){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();
        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
        $subjectView = $data[0];
        $process = $data[1];
        $critere = $data[2];
        $contrainte = $data[3];
        $phase = $data[6];

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
            'user' => $this->getUser()
        ));


        $data = array('nom' => 'description');

        $form = $this->createFormBuilder($data)
            ->add('Proposition', TextareaType::class)
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $phase,
                    'user' => $this->getUser(),
                ));
                $nbre = 1;
                if($proposition != null){
                    foreach ($proposition as $propo){
                        if($propo->getNumero() >= $nbre){
                            $nbre = $propo->getNumero()+1;
                        }
                    }
                }


                $Comp = new GenerationContribution();
                $Comp->setUser($this->getUser());
                $Comp->setContribution($form['Proposition']->getData());
                $Comp->setPhases($phase);
                $Comp->setPseudo($decideurs->getPseudodecideurs());
                $Comp->setNumero($nbre);
                $repository->persist($Comp);
                $repository->flush();


                $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $phase,
                    'user' => $this->getUser(),
                ));

                $form = $this->createFormBuilder($data)
                    ->add('Proposition', TextareaType::class)
                    ->add('Envoyer', SubmitType::class)
                    ->getForm();
                return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/FreeBrainstorm_contribution.html.twig', array(
                    'user' => $user,
                    'id' => $id,
                    'form'=>$form->createView(),
                    'proposition' => $proposition,
                    'admin' => $admin
                ));
            }
        }

        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/FreeBrainstorm_contribution.html.twig', array(
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
            'proposition' => $proposition,
            'admin' => $admin
        ));
    }

    public function generationAction($id){

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data[6],
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $finish = false;
        if($data[6]->getDateFin() < $now){
            $finish = true;
        }


        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet:FreeBrainstorming.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish
        ));
    }

    public function freebrainstormingCommentAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data[6],
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));


        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet:FreeBrainstorm_comment.html.twig', array(
            'contribution' => $contribution,
            'comment' => $comment,
            'users' => $this->getUser(),
        ));
    }

    public function freebrainstormingAddCommentAction($id, Request $request){
        $repository = $this->getDoctrine()->getManager();

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        $process = $sujet->getProcessus();

        $phase = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus' => $process,
            'nom' => 'Phase de Generations des solutions'
        ));



        $contribution = $repository->getRepository('GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));

        $comment = new GenerationComment();



        $comment = $repository->getRepository('Comment')->findBy(array(
            'contribution' => $contribution
        ));


    }
}