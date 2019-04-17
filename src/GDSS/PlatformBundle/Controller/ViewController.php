<?php

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Repertoire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;



class ViewController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(){
        return $this->render('@FOSUser/Security/login.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function problemlistAction(){

        //Recuperation de l'utilisateur courant c'est facultatif
        $user = $this->getUser();

        //Creation de l'entity manager
        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des sujets crées par le users courant
         */
        $em = $repository->getRepository('GDSSPlatformBundle:Problem');
        $problemlist = $em->findBy(array(
            'user'=> $user,
        ));

        /*
         * Recuperation des sujets auxqeuls le user courant est decideur
         */
        $makerslist = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'user' => $this->getUser(),
        ));

        $comp = 0;
        $tab = array();
        foreach ($makerslist as $makers){
            $problemmakers = $repository->getRepository('GDSSPlatformBundle:Problem')->findOneBy(array(
                'id' => $makers->getProcess()->getProblem(),
            ));
            $tab[$comp] = $problemmakers->getId();
            $comp++;
        }
        $problemmakers = $repository->getRepository('GDSSPlatformBundle:Problem')->findBy(array(
            'id' => $tab,
        ));


        $now1 = new \DateTime('now');

        return $this->render('@GDSSPlatform/Problem/list_of_problem.html.twig', array(
            'problemlist' => $problemlist,
            'problemmakers' => $problemmakers,
            'now1' => $now1
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function problemAction($id, Request $request){

        $users = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('problemdata')->problemdata($id);
        $problem = $data["problem"];
        $process = $data["process"];


        $criteria = $data["criteria"];
        $constraints = $this->container->get('getconstraintsthinklets')->constraintslist($id);
        $constraintsdescription = $repository->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
            'problem' => $problem
        ));
        $phase = $data["phase"];

        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($problem, $users);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem, $users);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem');
        }

        $error = '';
        if(isset($_GET['error'])){
           $error = $_GET['error'];
        }

        $definedprocess = true;
        if($process == null){
            $definedprocess = null;
        }

        $Comp = $data["Comp"];
        $Gene = $data["Gene"];
        $Nego1 = $data["Nego1"];
        $PreNego1 = $data["PreNego1"];
        $Nego2 = $data["Nego2"];
        $PreNego2 = $data["PreNego2"];
        $Nego3 = $data["Nego3"];
        $PreNego3 = $data["PreNego3"];
        $Decison = $data["Decision"];

        /*
         * Calcul de progression
         */
        $progress = $this->container->get('platform.progress')->progression($problem);

        $progressComp = null;
        $progressGene = null;
        $progressPreNego1 = null;
        $progressNego1 = null;
        $progressPreNego2 = null;
        $progressNego2 = null;
        $progressPreNego3 = null;
        $progressNego3 = null;
        $progressDecision = null;

        if ($Comp != null){
            $progressComp = $this->container->get('platform.progress')->progression($Comp);
        }
        if($Gene != null){
            $progressGene = $this->container->get('platform.progress')->progression($Gene);
        }

        if($PreNego1 != null){
            $progressNego1 = $this->container->get('platform.progress')->progression($PreNego1);
        }
        if($Nego1 != null){
            $progressNego1 = $this->container->get('platform.progress')->progression($Nego1);
        }

        if($PreNego2 != null){
            $progressPreNego2 = $this->container->get('platform.progress')->progression($PreNego2);
        }
        if($Nego2 != null){
            $progressNego2 = $this->container->get('platform.progress')->progression($Nego2);
        }

        if($PreNego3 != null){
            $progressPreNego3 = $this->container->get('platform.progress')->progression($PreNego3);
        }
        if($Nego3 != null){
            $progressPreNego2 = $this->container->get('platform.progress')->progression($Nego3);
        }
        if($Decison != null){
            $progressDecision = $this->container->get('platform.progress')->progression($Decison);
        }


        /*
         * Envoie d'invitation
         */
        $em = $repository->getRepository('GDSSPlatformBundle:Repertoire');
        $listrepertoire = $em->findBy(array(
            'userproprietaire' => $this->getUser()->getId(),
        ));

        //Recuperation des id des contacts
        $compteur = 0;
        $listid = array();
        foreach ($listrepertoire as $nbre){
            $listid['id'. $compteur] = $nbre->getUser();
            $compteur++;
        }
        /*
         * Formulaire pour envoie d'invitation
         */
        $defaultdata = array('name' => 'description');
        $form = $this->createFormBuilder($defaultdata)
            ->add('Contact', EntityType::class, array(
                'class' => 'GDSSPlatformBundle:User',
                'choices' => $listid,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('Inviter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $result = null;
        if ($form->isSubmitted() && $form->isValid()){
            $result = $this->container->get('platform.sendmsg')->sendInvitation($form, $problem, $users);
        }

        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $process,
        ));


        $now = new \DateTime("now");

        return $this->render('@GDSSPlatform/Problem/problem.html.twig', array(
            'id' => $id,
            'problem' => $problem,
            'process' => $process,
            'admin' => $admin,
            'definedprocess' => $definedprocess,
            'criteria' => $criteria,
            'constraints' => $constraints,
            'constraintsdescript' => $constraintsdescription,
            'Comp' => $Comp,
            'Gene' => $Gene,
            'PreNego1' => $PreNego1,
            'Nego1' => $Nego1,
            'PreNego2' => $PreNego2,
            'Nego2' => $Nego2,
            'PreNego3' => $PreNego3,
            'Nego3' => $Nego3,
            'Decision' => $Decison,
            'now' => $now,
            'progress' => $progress,
            'progressComp' => $progressComp,
            'progressGene' => $progressGene,
            'progressPreNego1' => $progressPreNego1,
            'progressNego1' => $progressNego1,
            'progressPreNego2' => $progressPreNego2,
            'progressNego2' => $progressNego2,
            'progressPreNego3' => $progressPreNego3,
            'progressNego3' => $progressNego3,
            'progressDecision' => $progressDecision,
            'form' => $form->createView(),
            'result' => $result,
            'makers' => $makers,
            'maker' => $maker,
            'error' => $error,
        ));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function scriptproblemAction($id){

        $users = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('problemdata')->problemdata($id);
        $problem = $data["problem"];
        $process = $data["process"];


        $criteria = $data["criteria"];
        $constraints = $this->container->get('getconstraintsthinklets')->constraintslist($id);
        $constraintsdescription = $repository->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
            'problem' => $problem
        ));
        $phase = $data["phase"];

        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($problem, $users);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem, $users);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem');
        }

        $error = '';
        if(isset($_GET['error'])){
            $error = $_GET['error'];
        }

        $definedprocess = true;
        if($process == null){
            $definedprocess = null;
        }

        $Comp = $data["Comp"];
        $Gene = $data["Gene"];
        $Nego1 = $data["Nego1"];
        $PreNego1 = $data["PreNego1"];
        $Nego2 = $data["Nego2"];
        $PreNego2 = $data["PreNego2"];
        $Nego3 = $data["Nego3"];
        $PreNego3 = $data["PreNego3"];
        $Decison = $data["Decision"];

        /*
         * Calcul de progression
         */
        $progress = $this->container->get('platform.progress')->progression($problem);

        $progressComp = null;
        $progressGene = null;
        $progressPreNego1 = null;
        $progressNego1 = null;
        $progressPreNego2 = null;
        $progressNego2 = null;
        $progressPreNego3 = null;
        $progressNego3 = null;
        $progressDecision = null;

        if ($Comp != null){
            $progressComp = $this->container->get('platform.progress')->progression($Comp);
        }
        if($Gene != null){
            $progressGene = $this->container->get('platform.progress')->progression($Gene);
        }

        if($PreNego1 != null){
            $progressNego1 = $this->container->get('platform.progress')->progression($PreNego1);
        }
        if($Nego1 != null){
            $progressNego1 = $this->container->get('platform.progress')->progression($Nego1);
        }

        if($PreNego2 != null){
            $progressPreNego2 = $this->container->get('platform.progress')->progression($PreNego2);
        }
        if($Nego2 != null){
            $progressNego2 = $this->container->get('platform.progress')->progression($Nego2);
        }

        if($PreNego3 != null){
            $progressPreNego3 = $this->container->get('platform.progress')->progression($PreNego3);
        }
        if($Nego3 != null){
            $progressPreNego2 = $this->container->get('platform.progress')->progression($Nego3);
        }
        if($Decison != null){
            $progressDecision = $this->container->get('platform.progress')->progression($Decison);
        }


        /*
         * Envoie d'invitation
         */
        $em = $repository->getRepository('GDSSPlatformBundle:Repertoire');
        $listrepertoire = $em->findBy(array(
            'userproprietaire' => $this->getUser()->getId(),
        ));

        //Recuperation des id des contacts
        $compteur = 0;
        $listid = array();
        foreach ($listrepertoire as $nbre){
            $listid['id'. $compteur] = $nbre->getUser();
            $compteur++;
        }

        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $process,
        ));


        $now = new \DateTime("now");

        return $this->render('@GDSSPlatform/Problem/script_page_problem.html.twig', array(
            'id' => $id,
            'problem' => $problem,
            'process' => $process,
            'admin' => $admin,
            'definedprocess' => $definedprocess,
            'criteria' => $criteria,
            'constraints' => $constraints,
            'constraintsdescript' => $constraintsdescription,
            'Comp' => $Comp,
            'Gene' => $Gene,
            'PreNego1' => $PreNego1,
            'Nego1' => $Nego1,
            'PreNego2' => $PreNego2,
            'Nego2' => $Nego2,
            'PreNego3' => $PreNego3,
            'Nego3' => $Nego3,
            'Decision' => $Decison,
            'now' => $now,
            'progress' => $progress,
            'progressComp' => $progressComp,
            'progressGene' => $progressGene,
            'progressPreNego1' => $progressPreNego1,
            'progressNego1' => $progressNego1,
            'progressPreNego2' => $progressPreNego2,
            'progressNego2' => $progressNego2,
            'progressPreNego3' => $progressPreNego3,
            'progressNego3' => $progressNego3,
            'progressDecision' => $progressDecision,
            'makers' => $makers,
            'maker' => $maker,
            'error' => $error,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function repertoireAction(Request $request){

        //Declaration des differentes variables
        $reposiroty = $this->getDoctrine()->getManager();
        $error = null;
        $success = null;
        $defaultdata = array('name' => 'description');

        //Creation du formulaire
        $form = $this->createFormBuilder($defaultdata)
            ->add('Pseudo', TextType::class)
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        //Si la methode est un POST
        if ($form->isSubmitted() && $form->isValid()){

            //Recupère la valeur du champ et recherhe s'il existe déjà ou s'il n'est pas valide
            //ou encore c'est pas l'utilisateur courant
            $username = $form["Pseudo"]->getData();
            $em = $reposiroty->getRepository('GDSSPlatformBundle:User');
            $result = $em->findOneBy(array(
                'username' => $username
            ));

            //Erreur pour utilisateur déjà existant
            if($result == null){
                $error = 'Utilisateur inexistant !';
            }
            else{

                //On verifie s'il n'est pas déjà dans les contact
                $em = $reposiroty->getRepository('GDSSPlatformBundle:Repertoire');
                $result1 = $em->findOneBy(array(
                    'user' => $result,
                    'userproprietaire' => $this->getUser()->getId(),
                ));

                if($result1 == null){

                    $user = $this->getUser();
                    $id=$user->getId();
                    $utilisateur = new Repertoire();
                    $utilisateur->setUser($result);
                    $utilisateur->setUserproprietaire($id);

                    if($username == $user->getUsername()){
                        $error = 'Vous ne pouvez pas vous ajouté';
                    }
                    else{
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($utilisateur);
                        $em->flush();
                        $success = 'Ajouté !';
                    }
                }
                else{
                    $error = 'Contact déjà existant';
                }


            }
        }

        $em = $reposiroty->getRepository('GDSSPlatformBundle:Repertoire');
        $listid = $em->findBy(array(
            'userproprietaire' => $this->getUser(),
        ));

        //Creation du formulaire
        $form = $this->createFormBuilder($defaultdata)
            ->add('Pseudo', TextType::class)
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        return $this->render('@GDSSPlatform/contact.html.twig', array(
            'listcontact' => $listid,
            'form'=>$form->createView(),
            'error' => $error,
            'success' => $success,
        ));
    }


}