<?php

namespace GDSS\PhasesBundle\Controller;



use GDSS\PhasesBundle\Entity\CompIdea;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    public function comprehensionAction($id, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();
        $data = $this->container->get('problemdata')->problemdata($id);

        $problem = $data["problem"];
        $phase = $data["Comp"];

        $chat = $repository->getRepository('GDSSPhasesBundle:CompIdea')->findBy(array(
            'phases' => $phase,
        ));

        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'user' => $user
        ));

        if($makers == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }

        if($user == $problem->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $makers->getPseudoMaker();
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateend() < $now){
            $finish = true;
        }

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Comp"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        $data = array('nom' => 'description');

        $form = $this->createFormBuilder($data)
            ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $now = new \DateTime();

                $finish = false;
                if($phase->getDateend() < $now){
                    $finish = true;
                }
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
                    'pseudo' => $pseudo,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'finish' => $finish,
                ));
            }
        }

        return $this->render('@GDSSPhases/phases_view/comprehension_collective.html.twig', array(
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
            'chat' => $chat,
            'pseudo' => $pseudo,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ));
    }

    public function addComprehensionChatAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = $this->container->get('problemdata')->problemdata($id);
        $problem = $data["problem"];

        $phase = $data["Comp"];

        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'user' => $user
        ));

        if($makers == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }

        if($user == $problem->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $makers->getPseudoMaker();
        }

        if($request->isXmlHttpRequest()){
            $prop = $_POST["proposition"];
            $Comp = new CompIdea();
            $Comp->setUser($this->getUser());
            $Comp->setIdea($prop);
            $Comp->setPhases($phase);
            $Comp->setPseudo($pseudo);
            $repository->persist($Comp);
            $repository->flush();


        }
        return new Response();
    }

    public function scriptCCPAction($id){
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();
        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Comp"];

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