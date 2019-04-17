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
use GDSS\PhasesBundle\Entity\GenerationSubSubject;
use GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution;
use GDSS\PhasesBundle\Entity\MakersGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GenerationThinkletController extends Controller
{

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function generationAction($id){

        $user = $this->getUser();

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('problem_list');
        }



        $data = $this->container->get('problemdata')->problemdata($id);

        if($data["Gene"]->getThinklet() == "TheLobbyist"){
            return $this->redirectToRoute('thelobbyist', array('id' => $id));
        }

        if($data["Gene"]->getStart() == false){
            if($data["Gene"]->getThinklet() != "OnePage"){
                $nbreactive = 0;
                $repository = $this->getDoctrine()->getManager();
                $allmakers = $data["allmakers"];
                foreach ($allmakers as $maker){
                    $active = $maker->getUser()->isActiveNow();
                    if($active == true){
                        $nbreactive++;
                    }
                }

                /*
                 *  A effacer
                 */
                $nbreactive = 6;

                if($nbreactive <= 5){
                    $data["Gene"]->setThinklet("OnePage");
                    $repository->persist($data["Gene"]);
                    $repository->flush();
                    return $this->redirectToRoute("one_page", array('id' => $id));
                }
                else{
                    $data["Gene"]->setStart(true);
                    $repository->persist($data["Gene"]);
                    $repository->flush();
                }
            }
        }




        if($data["Gene"]->getThinklet() == "FreeBrainstorming"){
            return $this->redirectToRoute('freebrainstorming', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "Plus-Minus-Interesting"){
            return $this->redirectToRoute('plusminus', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "LeafHopper"){
            return $this->redirectToRoute('leafhopper', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "OnePage"){
            return $this->redirectToRoute('one_page', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "BranchBuilder"){
            return $this->redirectToRoute('branchbuilder', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "ComparativeBraintorming"){
            return $this->redirectToRoute('ComparativeBrainstorming', array('id' => $id));
        }
        else if ($data["Gene"]->getThinklet() == "DealersChoice"){
            return $this->redirectToRoute('dealerschoice', array('id' => $id));
        }

        return new Response();
    }


    public function definiedsubproblemAction($id, $thinklet, Request $request){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Gene"];
        $problem = $data["problem"];

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));
        $definedcontrib = false;


        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'user' => $user
        ));

        if($maker == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateend() < $now){
            $finish = true;
        }

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        $form = null;
        $admin = false;
        if($problem->getUser() == $user){

            $data = array('nom' => 'description');

            $form = $this->createFormBuilder($data)
                ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
                ->add('Envoyer', SubmitType::class, array('attr' => array('class' => 'fas fa-paper-plane')))
                ->getForm();
            $admin = true;
        }

        if($request->isMethod('POST')){
            if(isset($_POST['submit'])){
                $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $phase,
                ));
                foreach ($contribution as $ct){
                    $ct->setStatus("Posté");
                    $repository->persist($ct);
                    $repository->flush();
                }
                return $this->redirectToRoute('plusminus',array('id' => $id));
            }
        }

        if($admin == true){
            return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet:definiedsubproblem.html.twig', array(
                'user' => $user,
                'id' => $id,
                'form' => $form->createView(),
                'chat' => $chat,
                'admin' =>$admin,
                'definied' => $definedcontrib,
                'finish' => $finish,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
            ));
        }
        else{
            return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet:definiedsubproblem.html.twig', array(
                'user' => $user,
                'id' => $id,
                'chat' => $chat,
                'admin' =>$admin,
                'definied' => $definedcontrib,
                'finish' => $finish,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
            ));
        }
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function freebrainstormingAction($id){

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $maker =$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }


        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);


        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/FreeBrainstorming.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function freebrainstormingContributionAction($id, Request $request){

        $now = new \DateTime();
        $alert = false;

        if(isset($_GET["alert"])){
            $alert = true;
        }

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Gene"];

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($decideurs == null){
            if($admin){
                return $this->redirectToRoute('generation', array('id' => $id));
            }
            else{
                return $this->redirectToRoute('problem_list');
            }

        }

        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }
        if ($finish == true){
            return $this->redirectToRoute('generation', array('id' => $id));
        }


        $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
            'user' => $this->getUser()
        ));




        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        $data = array('nom' => 'description');

        $form = $this->createFormBuilder($data)
            ->add('Proposition', TextareaType::class)
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        $submitForm = $this->createFormBuilder($data)
            ->add('Submit', SubmitType::class, array(
                'label' => 'SOUMETTRE LA PAGE'
            ))
            ->getForm();

        if($request->isMethod('POST')) {
            /*
             * Recuperatin du temps restants
             */
            $submitForm->handleRequest($request);
            if ($submitForm->isValid()) {
                foreach ($proposition as $propo) {
                    $propo->setStatus("Posté");
                    $repository->persist($propo);
                    $repository->flush();
                }
                $alert = true;
                return $this->redirectToRoute('freebrainstorming_contribution', array('id' => $id, 'alert' => $alert));
            }
        }

        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/FreeBrainstorm_contribution.html.twig', array(
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
            'proposition' => $proposition,
            'submit' => $submitForm->createView(),
            'admin' => $admin,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'finish' => $finish,
            'alert' => $alert
        ));
    }


    /**
     * @param $id
     * @return Response
     */
    public function freebrainstormingcontribAction($id){


        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Gene"];

        $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
            'user' => $this->getUser()
        ));

        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/contrib_list.html.twig', array(
            'proposition' => $proposition,
        ));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function freebrainstormingCommentAction($id){

        $user = $this->getUser();

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($decideurs == null){
            if($admin){
                return $this->redirectToRoute('generation', array('id' => $id));
            }
            else{
                return $this->redirectToRoute('problem_list');
            }
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);
        $now = new \DateTime();
        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }

        if ($finish == true){
            return $this->redirectToRoute('generation', array('id' => $id));
        }

        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $data['process']
        ));

        $makerslist = array();
        $comp = 0;

        foreach ($makers as $maker){
            $makerslist[$comp] = $maker->getUser();
            $comp++;
        }

        $find = false;

        foreach ($makerslist as $maker){
            $makers = $repository->getRepository('GDSSPlatformBundle:User')->find($maker);

            if($makers != $user){
                $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $data["Gene"],
                    'status' => "Posté",
                    'user' => $makers
                ));
                foreach ($contribution as $contrib){
                    $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
                        'contribution' => $contrib,
                        'user' => $user,
                    ));

                    if($comment == null){
                        $find = true;
                    }
                    else{
                        $find = false;
                        break;
                    }
                }

            }

            if($find==true){
                break;
            }
        }

        if($find == false){
            $contribution = null;
        }


        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/FreeBrainstorm_comment.html.twig', array(
            'contribution' => $contribution,
            'comment' => $comment,
            'users' => $this->getUser(),
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'id' => $id,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function onePageAction($id, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();


        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Gene"];
        $problem = $data["problem"];

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));

        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'user' => $user
        ));

        if($maker == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }

        if($user == $problem->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $maker->getPseudoMaker();
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateEnd() < $now){
            $finish = true;
        }

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Gene"]);
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
                if($phase->getDateEnd() < $now){
                    $finish = true;
                }

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

                $Gene = new GenerationContribution();
                $Gene->setUser($this->getUser());
                $Gene->setContribution($form['Proposition']->getData());
                $Gene->setPhases($phase);
                $Gene->setPseudo($pseudo);
                $Gene->setNumero($nbre);
                $Gene->setStatus("Posté");
                $repository->persist($Gene);
                $repository->flush();

                $form = $this->createFormBuilder($data)
                    ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
                    ->add('Envoyer', SubmitType::class)
                    ->getForm();

                return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/OnePage:OnePage.html.twig', array(
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

        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/OnePage:OnePage.html.twig', array(
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


    /**
     * @param $id
     * @return Response
     */
    public function onePageScriptAction($id){

        $phase = $this->container->get('problemdata')->problemdata($id);
        $phase = $phase["Gene"];

        $repository = $this->getDoctrine()->getManager();

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));

        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/OnePage:script_one_page.html.twig',array(
            'chat' => $chat,
            'id' => $id,
            'user' => $this->getUser(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function leafhopperAction($id, Request $request){

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ));


        $problem = $data["problem"];
        $admin = false;
        if ($this->getUser() == $problem->getUser()){
            $admin = true;
        }

        /*$comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));*/

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);

        $subsubjectlist = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->findBy(array(
            'phases' => $data["Gene"],
        ));

        $description = array('name' => 'description');

        $form = $this->createFormBuilder($description)
            ->add('Titre', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){

                $subsubject = new GenerationSubSubject();
                $subsubject->setName($form["Titre"]->getData());
                $subsubject->setPhases($data["Gene"]);
                $repository->persist($subsubject);
                $repository->flush();

                $subcontrib = new GenerationSubSubjectContribution();
                $subcontrib->setContrib("Bienvenue sur la discussion : ".$form["Titre"]->getData());
                $subcontrib->setPseudo("Facilitateur");
                $subcontrib->setUser($this->getUser());
                $subcontrib->setSubsubject($subsubject);

                $repository->persist($subcontrib);
                $repository->flush();

                return $this->redirectToRoute('leafhopper', array('id' => $id));

            }
        }


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/LeafHopper/leafhopper.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'finish' => $finish,
            'progress' => $progress,
            'subjectlist' => $subsubjectlist,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
        ));
    }


    /**
     * @param $id
     * @param $thinklet
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function leafhoppersubjectAction($id, $thinklet){

        $user = $this->getUser();
        $categorie = $this->getDoctrine()->getManager()->getRepository('GDSSPhasesBundle:GenerationSubSubject')->find($id);
        $data = $this->container->get('leafhopper')->data($id, $user);
        $subjectcontrib = $data['subjectcontrib'];
        $pseudo = $data['pseudo'];
        $maker = $data['maker'];
        $problem = $data['problem'];
        $subproblem = $data['subproblem'];
        $phase = $data['phase'];

        if($maker == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateend() < $now){
            $finish = true;
        }

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($phase);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        $data = array('nom' => 'description');

        $form = $this->createFormBuilder($data)
            ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
            ->add('Envoyer', SubmitType::class)
            ->getForm();


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/LeafHopper/leafhopper_subject.html.twig', array(
            'user' => $user,
            'id' => $id,
            'form' => $form->createView(),
            'chat' => $subjectcontrib,
            'pseudo' => $pseudo,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'problem' => $problem,
            'subproblem' => $subproblem,
            'categorie' => $categorie,
            'thinklet' => $thinklet,
            'backid' => $problem->getId(),
        ));
    }


    /**
     * @param $id
     * @return Response
     */
    public function leafhopperScriptAction($id){

        $data = $this->container->get('leafhopper')->data($id, $this->getUser());


        $subjectcontrib = $data['subjectcontrib'];

        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/LeafHopper/script_leafhopper_subject.html.twig',array(
            'chat' => $subjectcontrib,
            'id' => $id,
            'user' => $this->getUser(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function dealerschoiceAction($id, Request $request){

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }
        $error = '';
        if(isset($_GET['error'])){
            $error = $_GET['error'];
        }
        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ));


        $problem = $data["problem"];
        $admin = false;
        if ($this->getUser() == $problem->getUser()){
            $admin = true;
        }

        $allmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $data["process"]
        ));


        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);

        $subsubjectlist = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->findBy(array(
            'phases' => $data["Gene"],
        ));


        $allowsubsubjectlist = array();
        $allowsubsubject = null;
        $comp = 0;
        if($maker != null){
            foreach ($subsubjectlist as $cat){
                $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
                    'subproblem' => $cat,
                ));
                if(!empty($group)){
                    foreach ($group as $gp){
                        if($gp->getMaker() == $maker){
                            $allowsubsubjectlist[$comp] = $cat->getId();
                            $comp++;
                        }
                    }
                }
            }
            if(!empty($allowsubsubjectlist)){
                $allowsubsubject = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->findBy(array(
                    'id' => $allowsubsubjectlist,
                ));
            }
        }

        $description = array('name' => 'description');

        $form = $this->createFormBuilder($description)
            ->add('Titre', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){

                $subsubject = new GenerationSubSubject();
                $subsubject->setName($form["Titre"]->getData());
                $subsubject->setPhases($data["Gene"]);
                $subsubject->setProblem($problem);
                $repository->persist($subsubject);
                $repository->flush();

                $subcontrib = new GenerationSubSubjectContribution();
                $subcontrib->setContrib("Bienvenue sur la discussion : ".$form["Titre"]->getData());
                $subcontrib->setPseudo("Facilitateur");
                $subcontrib->setUser($this->getUser());
                $subcontrib->setSubsubject($subsubject);

                $repository->persist($subcontrib);
                $repository->flush();

                return $this->redirectToRoute('dealerschoice', array('id' => $id));

            }
        }


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/DealersChoice/dealerschoice.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'finish' => $finish,
            'allmakers' => $allmakers,
            'progress' => $progress,
            'subjectlist' => $subsubjectlist,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'error' => $error,
            'allowsubsubject' => $allowsubsubject,
            'maker' => $maker,
            'allmakers' => $allmakers,
        ));
    }

    public function dealerschoiceassignmentAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();
        $subproblem = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->find($id);
        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($subproblem->getProblem());
        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('leafhopper')->data($id, $user);
        $allmakers = $data['allmakers'];

        $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
            'subproblem' => $subproblem
        ));

        /*---------------------------MAKERS ALLOW AND NOT ALLOW -------------------------------------------------*/
        $allowlist = array();
        $comp = 0;
        foreach ($group as $gp){
            $allowlist[$comp] = $gp->getMaker();
            $comp++;
        }
        $allowmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'id' => $allowlist
        ));
        $allmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            "process" => $data["process"]
        ));

        $notallowlist = array();
        $comp = 0;

        foreach ($allmakers as $maker){
            $notallowlist[$comp] = $maker->getId();
            $comp++;
        }
        $comp = 0;
        $allowlist = array();
        foreach ($allowmakers as $maker){
            $allowlist[$comp] = $maker->getId();
            $comp++;
        }
        $notallowlist = array_diff($notallowlist, $allowlist);
        $notallowmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'id' => $notallowlist
        ));

        /*---------------------------------------------END---------------------------------------------------------------------*/

        $now = new \DateTime();
        $time = $this->container->get('timer')->getime($data["phase"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["phase"]->getDateend() < $now){
            $finish = true;
        }


        if ($request->isMethod('POST')){

            if(isset($_POST['Affecter'])){
                foreach ($_POST as $key){
                    if($key != "Affecter"){
                        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($key);
                        $group = new MakersGroup();
                        $group->setName($subproblem->getName());
                        $group->setMaker($makers);
                        $group->setSubproblem($subproblem);
                        $group->setPhase("Gene");
                        $repository->persist($group);
                    }
                }
            }
            else if (isset($_POST['Désaffecter'])){
                foreach ($_POST as $key){
                    if($key != "Désaffecter"){
                        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($key);
                        $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findOneBy(array(
                            'subproblem' => $subproblem,
                            'maker' => $maker
                        ));
                        $repository->remove($group);
                    }
                }
            }
            $repository->flush();

            return $this->redirectToRoute('Dealers_Choice_Assignment', array(
                'id' => $id,
            ));
        }
        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/DealersChoice:Dealers_Choice_Assignment_Makers.html.twig', array(
            'subproblem' => $subproblem,
            'allmakers' => $allmakers,
            'backid' => $problem->getId(),
            'allowmakers' => $allowmakers,
            'notallowmakers' => $notallowmakers,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ));


    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function plusminusinterestingAction(Request $request, $id){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);
        $phase = $data["Gene"];
        $problem = $data["problem"];

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));
        $definedcontrib = false;
        foreach ($chat as $ct){
            if($ct->getStatus() == "Posté"){
                $definedcontrib = true;
            }
        }

        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'user' => $user
        ));

        if($maker == null AND $problem->getUser() != $user ){
            return $this->redirectToRoute('problem_list');
        }


        $now = new \DateTime();

        $finish = false;
        if($phase->getDateend() < $now){
            $finish = true;
        }

        /*
         * Recuperatin du temps restants
         */
        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        $form = null;
        $admin = false;
        if($problem->getUser() == $user){

            $data = array('nom' => 'description');

            $form = $this->createFormBuilder($data)
                ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
                ->add('Envoyer', SubmitType::class, array('attr' => array('class' => 'fas fa-paper-plane')))
                ->getForm();
            $admin = true;
        }

        if($request->isMethod('POST')){
            if(isset($_POST['submit'])){
                $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $phase,
                ));
                foreach ($contribution as $ct){
                    $ct->setStatus("Posté");
                    $repository->persist($ct);
                    $repository->flush();
                }
                return $this->redirectToRoute('plusminus',array('id' => $id));
            }
        }


        if($admin == true){
            return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/PlusMinusInteresting:plusminusinteresting.html.twig', array(
                'user' => $user,
                'id' => $id,
                'form' => $form->createView(),
                'chat' => $chat,
                'admin' =>$admin,
                'definied' => $definedcontrib,
                'finish' => $finish,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
            ));
        }
        else{
            return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/PlusMinusInteresting:plusminusinteresting.html.twig', array(
                'user' => $user,
                'id' => $id,
                'chat' => $chat,
                'admin' =>$admin,
                'definied' => $definedcontrib,
                'finish' => $finish,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
            ));
        }
    }

    /**
     * @param $id
     * @return Response
     */
    public function plusminusScriptAction($id){
        $phase = $this->container->get('problemdata')->problemdata($id);
        $phase = $phase["Gene"];


        $repository = $this->getDoctrine()->getManager();

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));

        $definedcontrib = false;

        foreach ($chat as $ct){
            if($ct->getStatus() == "Posté"){
                $definedcontrib = true;
            }
            $reaction = $repository->getRepository('GDSSPhasesBundle:Reaction')->findBy(array(
                'contrib' => $ct
            ));

            $like = 0;
            $dislike = 0;
            $interesting = 0;

            if($reaction != null){
                foreach ($reaction as $reac){
                    if($reac->getReaction() == "Like"){
                        $like++;
                    }
                    else if ($reac->getReaction() == "Dislike"){
                        $dislike++;
                    }
                    else if ($reac->getReaction() == "Interesting"){
                        $interesting++;
                    }
                }
                $ct->setLiked($like);
                $ct->setDislike($dislike);
                $ct->setInteresting($interesting);
                $repository->persist($ct);
                $repository->flush();
            }


        }

        $reaction = $repository->getRepository('GDSSPhasesBundle:Reaction')->findAll();
        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/PlusMinusInteresting:script_plus_minus_interesting.html.twig',array(
            'chat' => $chat,
            'id' => $id,
            'user' => $this->getUser(),
            'reaction' => $reaction,
            'contribreply' => $comment,
            'definied' => $definedcontrib,
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
   public function contriblistAction(Request $request, $id){

       $repository = $this->getDoctrine()->getManager();
       $data = $this->container->get('platform.sujectdata')->sujetdata($id);

       //if ($request->isXmlHttpRequest()){
           $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
               'phases' => $data[6],
           ));
           $idx=0;
           $jsondata=array();
           foreach($contribution as $contrib){
                $temp = array("pseudo"=>$contrib->getPseudo(), "contrib"=>$contrib->getContribution());
                $jsondata[$idx] = $temp;
                $idx++;
           }
           return new JsonResponse($jsondata);
       //}
       //return new Response("Erreur : Ce n'est pas une requete ajax", 400);
   }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
   public function branchbuilterAction($id, Request $request){
       $user = $this->getUser();

       $repository = $this->getDoctrine()->getManager();

       $data = $this->container->get('problemdata')->problemdata($id);
       $phase = $data["Gene"];
       $problem = $data["problem"];
       $process = $data["process"];

       $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
           'phases' => $phase,
       ));

       $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
           'process' => $process,
           'user' => $user
       ));

       $definedcontrib = false;
       $admin = false;

       foreach ($chat as $ct){
           if($ct->getStatus() == "Posté"){
               $definedcontrib = true;
           }
       }

       if($maker == null AND $problem->getUser() != $user ){
           return $this->redirectToRoute('problem_list');
       }

       if($user == $problem->getUser()){
           $pseudo = 'Facilitateur';
           $admin = true;
       }
       else{
           $pseudo = $maker->getPseudoMaker();
       }

       $now = new \DateTime();

       $finish = false;
       if($phase->getDateend() < $now){
           $finish = true;
       }

       /*
        * Recuperatin du temps restants
        */
       $time = $this->container->get('timer')->getime($data["Gene"]);
       $hours = $time["hours"];
       $minutes = $time["minutes"];
       $seconds = $time["seconds"];

       $data = array('nom' => 'description');

       $form = $this->createFormBuilder($data)
           ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
           ->add('Envoyer', SubmitType::class, array('attr' => array('class' => 'fas fa-paper-plane')))
           ->getForm();

       if($request->isMethod('POST')){
           if(isset($_POST['submit'])){
               $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                   'phases' => $phase,
               ));
               foreach ($contribution as $ct){
                   $ct->setStatus("Posté");
                   $repository->persist($ct);
                   $repository->flush();
               }
               return $this->redirectToRoute('branchbuilder',array('id' => $id));
           }
       }

       return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/BranchBuilder:branchbuilder.html.twig', array(
           'user' => $user,
           'id' => $id,
           'form' => $form->createView(),
           'chat' => $chat,
           'pseudo' => $pseudo,
           'finish' => $finish,
           'hours' => $hours,
           'minutes' => $minutes,
           'seconds' => $seconds,
           'definied' => $definedcontrib,
           'admin' => $admin
       ));
   }


   public function thelobbyistAction($id){
       $user = $this->getUser();

       $repository = $this->getDoctrine()->getManager();
       $data = $this->container->get('problemdata')->problemdata($id);
       $phase = $data["Gene"];
       $process = $data["process"];
       $problem = $data["problem"];

       $now = new \DateTime();

       $finish = false;
       if($phase->getDateend() < $now){
           $finish = true;
       }

       if($phase->getThinklet() != "TheLobbyist"){
           if($finish == false){
               return $this->redirectToRoute('generation', array('id' => $id));
           }

           if($data["problem"]->getUser() == $user){
               $constraint = $repository->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
                   'problem' => $problem
               ));
               $processduration = $process->getdurationmax();
               $phase->setThinklet("TheLobbyist");

               $repository->persist($phase);
               $repository->flush();
               if($constraint->getDescription() == "2"){
                   $dureemax = 10;
               }
               else{
                   $dureemax = 30;
               }
               if($processduration == null){
                   $process->setdurationmax($dureemax);
               }
               else{
                   $process->setdurationmax($dureemax+$processduration);
               }
               $now = new \DateTime();
               $now2 = new \DateTime();
               $end = $now2->modify("+".$dureemax." minutes");
               $phase->setDatestart($now);
               $phase->setDateend($end);
               $repository->persist($phase);
               $repository->persist($process);
               $repository->flush();
           }
           else{
               return $this->redirectToRoute('generation', array('id' => $id));
           }
       }
       $problem = $data["problem"];

       $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
           'phases' => $phase,
       ));



       $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
           'process' => $data["process"],
           'user' => $user
       ));

       if($maker == null AND $problem->getUser() != $user ){
           return $this->redirectToRoute('problem_list');
       }

       if($user == $problem->getUser()){
           $pseudo = 'Facilitateur';
       }
       else{
           $pseudo = $maker->getPseudoMaker();
       }


       /*
        * Recuperatin du temps restants
        */
       $time = $this->container->get('timer')->getime($data["Gene"]);
       $hours = $time["hours"];
       $minutes = $time["minutes"];
       $seconds = $time["seconds"];

       $data = array('nom' => 'description');

       $form = $this->createFormBuilder($data)
           ->add('Proposition', TextareaType::class, array('attr' => array('class' => 'propo')))
           ->add('Envoyer', SubmitType::class, array('attr' => array('class' => 'fas fa-paper-plane')))
           ->getForm();


       return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/The-Lobblyist:thelobbyist.html.twig', array(
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


   public function comparativebrainstormingAction($id){
        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $maker =$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }


        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);


        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Gene"]->getDateend() < $now){
            $finish = true;
        }

        if($finish == false){
            if($maker != null){
                if($minutes < 15){
                    return $this->redirectToRoute('ComparativeBrainstormingComment', array('id' => $id));
                }
                else if ($minutes >= 15){
                    return $this->redirectToRoute('ComparativeBrainstormingContrib', array('id' => $id));
                }
            }
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/ComparativeBraintorming/ComparativeBrainstorming.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ));
    }


   public function comparativebraincontribAction($id){
       $now = new \DateTime();
       $alert = false;

       if(isset($_GET["alert"])){
           $alert = true;
       }

       $user = $this->getUser();
       $repository = $this->getDoctrine()->getManager();

       /*
        * Recuperation des informations complètes sur le sujet
        */
       $data = $this->container->get('problemdata')->problemdata($id);
       $phase = $data["Gene"];

       /*
        * CHECK ACCESS
        */
       $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
       $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
       if($decideurs == null){
           if($admin){
               return $this->redirectToRoute('generation', array('id' => $id));
           }
           else{
               return $this->redirectToRoute('problem_list');
           }

       }

       $finish = false;
       if($data["Gene"]->getDateend() < $now){
           $finish = true;
       }
       if ($finish == true){
           return $this->redirectToRoute('ComparativeBrainstorming', array('id' => $id));
       }


       $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
           'phases' => $phase,
           'user' => $this->getUser()
       ));




       /*
        * Recuperatin du temps restants
        */
       $time = $this->container->get('timer')->getime($data["Gene"]);
       $hours = $time["hours"];
       $minutes = $time["minutes"];
       $seconds = $time["seconds"];

       if($minutes < 15 ){
           return $this->redirectToRoute('ComparativeBrainstormingComment', array('id' =>$id));
       }

       $data = array('nom' => 'description');

       $form = $this->createFormBuilder($data)
           ->add('Proposition', TextareaType::class)
           ->add('Envoyer', SubmitType::class)
           ->getForm();

       return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/ComparativeBraintorming/Comparative_Braintorming_Contrib.html.twig', array(
           'user' => $user,
           'id' => $id,
           'form' => $form->createView(),
           'proposition' => $proposition,
           'admin' => $admin,
           'hours' => $hours,
           'minutes' => $minutes,
           'seconds' => $seconds,
           'finish' => $finish,
           'alert' => $alert
       ));
   }


   public function comparativebraincommentAction($id){
       $user = $this->getUser();

       /*
        * CHECK ACCESS
        */
       $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
       $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
       if($decideurs == null){
           if($admin){
               return $this->redirectToRoute('generation', array('id' => $id));
           }
           else{
               return $this->redirectToRoute('problem_list');
           }
       }

       $repository = $this->getDoctrine()->getManager();

       $data = $this->container->get('problemdata')->problemdata($id);
       $now = new \DateTime();
       $finish = false;
       if($data["Gene"]->getDateend() < $now){
           $finish = true;
       }

       if ($finish == true){
           return $this->redirectToRoute('ComparativeBrainstorming', array('id' => $id));
       }

       $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
           'process' => $data['process']
       ));

       $makerslist = array();
       $comp = 0;

       foreach ($makers as $maker){
           $makerslist[$comp] = $maker->getUser();
           $comp++;
       }

       $find = false;

       $contribution = null;
       foreach ($makerslist as $maker){
           $makers = $repository->getRepository('GDSSPlatformBundle:User')->find($maker);

           if($makers != $user){
               $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                   'phases' => $data["Gene"],
                   'status' => "Posté",
                   'user' => $makers
               ));
               foreach ($contribution as $contrib){
                   $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
                       'contribution' => $contrib,
                       'user' => $user,
                   ));

                   if($comment == null){
                       $find = true;
                   }
                   else{
                       $find = false;
                       break;
                   }
               }

           }

           if($find==true){
               break;
           }
       }

       if($find == false){
           $contribution = null;
       }


       $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

       /*
        * Recuperatin du temps restants
        */
       $time = $this->container->get('timer')->getime($data["Gene"]);
       $hours = $time["hours"];
       $minutes = $time["minutes"];
       $seconds = $time["seconds"];

       if($minutes > 15){
           return $this->redirectToRoute('ComparativeBrainstormingContrib', array('id' => $id));
       }


       return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/ComparativeBraintorming/Comparative_Brainstorming_Comment.html.twig', array(
           'contribution' => $contribution,
           'comment' => $comment,
           'users' => $this->getUser(),
           'finish' => $finish,
           'hours' => $hours,
           'minutes' => $minutes,
           'seconds' => $seconds,
           'id' => $id,
       ));
   }

}