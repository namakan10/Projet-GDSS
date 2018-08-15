<?php

namespace GDSS\PhasesBundle\Controller;



use GDSS\PhasesBundle\Entity\CompIdea;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ViewController extends Controller
{
    public function comprehensionAction($id, Request $request){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();
        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        $process = $sujet->getProcessus();

        $phase = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus' => $process,
            'nom' => 'Phase de Comprehension Collective du problème'
        ));

        $chat = $repository->getRepository('GDSSPhasesBundle:CompIdea')->findBy(array(
            'phases' => $phase,
        ));

        $decideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findOneBy(array(
            'sujet' => $sujet,
            'user' => $user
        ));

        if($decideurs == null AND $sujet->getUser() != $user ){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        if($user == $sujet->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $decideurs->getPseudodecideurs();
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateFin() < $now){
            $finish = true;
        }

        $data = array('nom' => 'description');

        $form = $this->createFormBuilder($data)
            ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $Comp = new CompIdea();
                $Comp->setUser($this->getUser());
                $Comp->setIdea($form['Proposition']->getData());
                $Comp->setPhases($phase);
                $Comp->setPseudo($pseudo);
                $repository->persist($Comp);
                $repository->flush();

                $form = $this->createFormBuilder($data)
                    ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
                    ->add('Envoyer', SubmitType::class)
                    ->getForm();

                return $this->render('@GDSSPhases/phases_view/comprehension_collective.html.twig', array(
                    'user' => $user,
                    'id' => $id,
                    'form'=>$form->createView(),
                    'chat' => $chat,
                    'pseudo' => $pseudo
                ));
            }
        }

        return $this->render('@GDSSPhases/phases_view/comprehension_collective.html.twig', array(
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
            'chat' => $chat,
            'pseudo' => $pseudo,
            'finish' => $finish
        ));
    }

    public function scriptCCPAction($id){


        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();
        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        $process = $sujet->getProcessus();

        $phase = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus' => $process,
            'nom' => 'Phase de Comprehension Collective du problème'
        ));

        $chat = $repository->getRepository('GDSSPhasesBundle:CompIdea')->findBy(array(
            'phases' => $phase,
        ));


        return $this->render('@GDSSPhases/phases_view/script_chat_CCP.html.twig', array(
            'user' => $user,
            'id' => $id,
            'chat' => $chat,

        ));
    }
}