<?php

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Repertoire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;



class ViewController extends Controller
{

    public function loginAction(){
        return $this->render('@FOSUser/Security/login.html.twig');
    }

    public function sujet_panelAction(){

        //Recuperation de l'utilisateur courant c'est facultatif
        $user = $this->getUser();

        //Creation de l'entity manager
        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des sujets crées par le users courant
         */
        $em = $repository->getRepository('GDSSPlatformBundle:Sujet');
        $subjectlist = $em->findBy(array(
            'user'=> $user,
        ));

        /*
         * Recuperation des sujets auxqeuls le user courant est decideur
         */
        $sujetid = $user->getDecideurs();
        $tableau = array();
        $cmp = 0;
        foreach ($sujetid as $id){
            $sujetdecideurs = $em->findOneBy(array(
                'id' => $id->getSujet(),
            ));
            $tableau['id'.$cmp] = $sujetdecideurs;
            $cmp++;
        }
        $sujetdecideurs = $em->findBy(array(
            'id' => $tableau,
        ));



        $now1 = new \DateTime('now');

        return $this->render('@GDSSPlatform/Sujets_Basic_View/sujet_panel.html.twig', array(
            'subjectlist' => $subjectlist,
            'decideurslist' => $sujetdecideurs,
            'now1' => $now1
        ));

    }

    public function sujetdetailsAction($id){

        $em = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Sujet');
        $subjectView = $em->find($id);

        $decideurs = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Decideurs')->findOneBy(
            array(
                'user' => $this->getUser()
            )
        );

        $admin = null;

        if($subjectView->getUser() == $this->getUser()){
            $admin = true;
        }

        if ($admin == null AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $em1 = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Criteres');
        $criteres = $em1->findBy(array(
            'sujet' => $subjectView,
            )
        );

        $em2 = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Contraintes');
        $contraintes = $em2->findBy(array(
                'sujet' => $subjectView,
            )
        );


        if (null === $subjectView) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        /**if($subjectView != $user){
            throw new NotFoundHttpException("Vou n'avez pas publié ce sujet");
        }**/

        return $this->render('@GDSSPlatform/details/sujet_details.html.twig', array(
            'subjectView' => $subjectView,
            'critereslist' => $criteres,
            'contraintelist' => $contraintes,
            'id' => $id,
        ));
    }

    public function sujet_vueAction($id, Request $request){

        $users = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        /*
         * Recuperation des informations complètes sur le sujet
         */
        $data = $this->container->get('platform.sujectdata')->sujetdata($id);
        $subjectView = $data[0];
        $process = $data[1];
        $critere = $data[2];
        $contrainte = $data[3];
        $phase = $data[4];

        /*
         * CHECK ACCESS
         */
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($subjectView, $users);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($subjectView, $users);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $definedprocess = true;
        $defined = true;
        $Comp = array();
        $Nego=array();
        $Gene=array();


        if($process == null){
            $definedprocess = null;
        }

        if($phase == null){
            $defined=null;
        }


        /*
         *  Recuperations des phases s'ils sont definies
         */
        if($defined == true){
            $Comp = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
                'nom' => 'Phase de Comprehension Collective du problème',
                'processus' => $process
            ));

            $Gene = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
                'nom' => 'Phase de Generations des solutions',
                'processus' => $process
            ));

            $Nego = $repository->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
                'nom' => 'Phase de Negociations de confrontations des points de vue',
                'processus' => $process
            ));
        }

        /*
         * Calcul de progression
         */
        $progress = $this->container->get('platform.progress')->progression($subjectView);

        $progressComp = null;
        $progressGene = null;
        $progressNego = null;

        if ($defined == true){
            $progressComp = $this->container->get('platform.progress')->progression($Comp);
            $progressGene = $this->container->get('platform.progress')->progression($Gene);
            $progressNego = $this->container->get('platform.progress')->progression($Nego);
        }



        /*
         * Envoie d'invitation
         */

        $defaultdata = array('name' => 'description');

        $em = $repository->getRepository('GDSSPlatformBundle:Repertoire');
        $listrepertoire = $em->findBy(array(
            'userproprietaire' => $this->getUser()->getId(),
        ));

        //Recuperation des information sur le sujet
        $em = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Sujet');
        $subjectView = $em->find($id);

        //Recuperation des id des contacts
        $compteur = 0;
        $listid = array();
        foreach ($listrepertoire as $nbre){
            $listid['id'. $compteur] = $nbre->getUser();
            $compteur++;
        }
        //Formulaire
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
            $result = $this->container->get('platform.sendmsg')->sendInvitation($form, $subjectView, $users);
        }

        /*
         * FIN
         */


        $now = new \DateTime("now");

        return $this->render('GDSSPlatformBundle:Sujets_Basic_View:sujet_vue.html.twig', array(
            'id' => $id,
            'subjectView' => $subjectView,
            'admin' => $admin,
            'definedprocess' => $definedprocess,
            'defined' => $defined,
            'critere' => $critere,
            'contrainte' => $contrainte,
            'Comp' => $Comp,
            'Gene' => $Gene,
            'Nego' => $Nego,
            'now' => $now,
            'progress' => $progress,
            'progressComp' => $progressComp,
            'progressGene' => $progressGene,
            'progressNego' => $progressNego,
            'form' => $form->createView(),
            'result' => $result,
        ));
    }

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

        return $this->render('GDSSPlatformBundle:Carnet Contact:repertoire.html.twig', array(
            'listcontact' => $listid,
            'form'=>$form->createView(),
            'error' => $error,
            'success' => $success,
        ));
    }

    public function processusdetailsAction($id){

        $repository = $this->getDoctrine()->getManager();
        $em = $repository->getRepository('GDSSPlatformBundle:Sujet');
        $sujet = $em->find($id);

        /*
         * CHECK ALLOW ACCESS
         */
        if ($sujet->getUser() != $this->getUser()){
            return $this->redirectToRoute('gdss_platform_sujets');
        }
        $em= $repository->getRepository('GDSSPlatformBundle:Processus');
        $process = $em->findOneBy(array(
            'sujet' => $sujet,
        ));

        $secondemax = $process->getDureeMax();
        $heuresmax = null;
        $joursmax = null;

        $secondemin = $process->getDureeMin();
        $heuresmin = null;
        $joursmin  = null;

        $minutesmax = $secondemax/60;
        if($minutesmax>60){
            $heuresmax = $minutesmax/60;
        }

        if($heuresmax >24){
            $joursmax = $heuresmax/24;
        }

        $minutesmin = $secondemin/60;
        if($minutesmin>60){
            $heuresmin = $minutesmin/60;
        }

        if($heuresmin >24){
            $joursmin = $heuresmin/24;
        }


        return $this->render('@GDSSPlatform/details/processus_details.html.twig', array(
            'process'=> $process,
            'id'=>$id,
            'heuremax' => $heuresmax,
            'joursmax' => $joursmax,
            'minutesmax' => $minutesmax,
            'joursmin' => $joursmin,
            'heuremin' => $heuresmin,
            'minutemin' => $minutesmin
            ));
    }


}