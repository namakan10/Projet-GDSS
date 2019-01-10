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
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["phase"],
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
        if($data["Gene"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);


        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet:generation.html.twig', array(
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function freebrainstormingAction($id){

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
        if($data["Gene"]->getDateFin() < $now){
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

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
        $phase = $data["Gene"];

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
            'user' => $this->getUser()
        ));

        $finish = false;
        if($data["Gene"]->getDateFin() < $now){
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
            ->add('Proposition', TextareaType::class)
            ->add('Envoyer', SubmitType::class)
            ->getForm();
        $submitForm = $this->createFormBuilder($data)
            ->add('Submit', SubmitType::class, array(
                'label' => 'SOUMETTRE LA PAGE'
            ))
            ->getForm();

        $alert = false;

        if($request->isMethod('POST')){
            /*
             * Recuperatin du temps restants
             */
            $data = $this->container->get('platform.sujectdata')->sujetdata($id);
            $time = $this->container->get('timer')->getime($data["Gene"]);
            $hours = $time["hours"];
            $minutes = $time["minutes"];
            $seconds = $time["seconds"];



            $form = $this->createFormBuilder($data)
                ->add('Proposition', TextareaType::class)
                ->add('Envoyer', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);
            $submitForm->handleRequest($request);
            if($submitForm->isValid()){
                foreach ($proposition as $propo){
                    $propo->setStatus("Posté");
                    $repository->persist($propo);
                    $repository->flush();
                }
                $alert = true;
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
                    'alert' => $alert,
                ));

            }
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
                $Comp->setStatus("non posté");
                $repository->persist($Comp);
                $repository->flush();

                $proposition = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $phase,
                    'user' => $this->getUser(),
                ));



                return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/FreeBrainstorm_contribution.html.twig', array(
                    'user' => $user,
                    'id' => $id,
                    'form'=>$form->createView(),
                    'proposition' => $proposition,
                    'admin' => $admin,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'finish' => $finish,
                    'submit' => $submitForm->createView(),
                    'alert' => $alert,
                ));
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
            'alert' => $alert,
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
        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
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
     * @param Request $request
     * @return Response
     */
    public function freebrainstormingCommentAction($id, Request $request){

        $user = $this->getUser();

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $decideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findBy(array(
            'sujet' => $data['subject']
        ));

        $listdecideurs = array();
        $comp = 0;

        foreach ($decideurs as $decid){
            $listdecideurs[$comp] = $decid->getUser();
            $comp++;
        }

        $find = false;

        foreach ($listdecideurs as $lstdecid){
            $decideurs = $repository->getRepository('GDSSPlatformBundle:User')->find($lstdecid);

            if($decideurs != $user){
                $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $data["Gene"],
                    'status' => "Posté",
                    'user' => $decideurs
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


        $now = new \DateTime();
        $finish = false;
        if($data["Gene"]->getDateFin() < $now){
            $finish = true;
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
     */
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


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function onePageAction($id, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();


        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
        $phase = $data["Gene"];
        $sujet = $data["subject"];

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
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
                if($phase->getDateFin() < $now){
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

        $phase = $this->container->get('platform.sujectdata')->sujetdata($id);
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
     * @return Response
     */
    public function leafhopperAction($id, Request $request){

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
            'phases' => $data["Gene"],
        ));


        $subject = $data["subject"];
        $admin = false;
        if ($this->getUser() == $subject->getUser()){
            $admin = true;
        }

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Gene"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Gene"]->getDateFin() < $now){
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

                $form = $this->createFormBuilder($description)
                    ->add('Titre', TextType::class)
                    ->add('Creer', SubmitType::class)
                    ->getForm();

                $now = new \DateTime();

                $time = $this->container->get('timer')->getime($data["Gene"]);
                $hours = $time["hours"];
                $minutes = $time["minutes"];
                $seconds = $time["seconds"];
                $finish = false;
                if($data["Gene"]->getDateFin() < $now){
                    $finish = true;
                }

                $subsubjectlist = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->findBy(array(
                    'phases' => $data["Gene"],
                ));

                return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/LeafHopper/leafhopper.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'subjectlist' => $subsubjectlist,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'form' => $form->createView(),
                ));

            }
        }


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/LeafHopper/leafhopper.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function leafhoppersubjectAction($id){

        $user = $this->getUser();
        $data = $this->container->get('leafhopper')->data($id, $user);
        $subjectcontrib = $data['subjectcontrib'];
        $pseudo = $data['pseudo'];
        $decideurs = $data['decideurs'];
        $sujet = $data['sujet'];
        $phase = $data['phase'];

        if($decideurs == null AND $sujet->getUser() != $user ){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $now = new \DateTime();

        $finish = false;
        if($phase->getDateFin() < $now){
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
            'sujet' => $sujet,
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
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function plusminusinterestingAction(Request $request, $id){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
        $phase = $data["Gene"];
        $sujet = $data["subject"];

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
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


        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/PlusMinusInteresting:plusminusinteresting.html.twig', array(
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
    public function plusminusScriptAction($id){
        $phase = $this->container->get('platform.sujectdata')->sujetdata($id);
        $phase = $phase["Gene"];


        $repository = $this->getDoctrine()->getManager();

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));

        foreach ($chat as $ct){
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
            'contribreply' => $comment
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
   public function branchbuilterAction($id){
       $user = $this->getUser();

       $repository = $this->getDoctrine()->getManager();

       $data = $this->container->get('platform.sujectdata')->sujetdata($id);
       $phase = $data["Gene"];
       $sujet = $data["subject"];

       $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
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
       ));
   }

   public function thelobbyistAction($id){
       $user = $this->getUser();

       $repository = $this->getDoctrine()->getManager();

       $data = $this->container->get('platform.sujectdata')->sujetdata($id);
       $phase = $data["Gene"];
       $sujet = $data["subject"];

       $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
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


}