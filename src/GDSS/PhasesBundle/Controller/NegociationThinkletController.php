<?php
/**
 * Created by PhpStorm.
 * User: Namakan
 * Date: 25/10/2018
 * Time: 11:40
 */

namespace GDSS\PhasesBundle\Controller;


use GDSS\PhasesBundle\Entity\NegociationCategories;
use GDSS\PhasesBundle\Entity\NegociationCategorieSelection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class NegociationThinkletController extends Controller
{
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function negociationAction($id){

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

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet:negocitation.html.twig', array(
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fastfocusAction($id, Request $request){

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Nom', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $categorie = new NegociationCategories();
                $categorie->setName($form["Nom"]->getData());
                $categorie->setPhase($data["Nego"]);

                $repository->persist($categorie);
                $repository->flush();

                $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'phase' => $data["Nego"]
                ));

                $description = array();
                $form = $this->createFormBuilder($description)
                    ->add('Nom', TextType::class)
                    ->add('Creer', SubmitType::class)
                    ->getForm();

                $time = $this->container->get('timer')->getime($data["Nego"]);
                $hours = $time["hours"];
                $minutes = $time["minutes"];
                $seconds = $time["seconds"];

                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:fastfocus.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'categorielist' => $categorielist,
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:fastfocus.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'categorielist' => $categorielist,
        ));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fastfocuscategorizerAction($id, Request $request){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPlatformBundle:Phases')->find($categorie->getPhase());

        $processus = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phaseNego->getProcessus());

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($processus->getSujet());

        $phaseGene = $repository->getRepository('GDSSPlatformBundle:Phases')->findBy(array(
            'processus' => $processus,
            'nom' => 'Phase de Generations des solutions',
        ));

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ));


        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $admin = $this->container->get('platform.checkaccess')->adminAccess($sujet->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('FastFocus', array(
                'id' => $sujet->getId(),
            ));
        }

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($phaseNego->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contributioncat as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie("void");
                            $repository->persist($contrib);

                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie($categorie->getName());
                            $repository->persist($contrib);
                        }
                    }
                    $repository->flush();
                    return $this->redirectToRoute('FastFocusCategorie', array(
                        'id' => $id,
                    ));
                }
            }

            /*
             * Retour de la vue
             */
            if(isset($error)){
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:fastfocuscategorie.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'selection' => $nbreselection,
                    'error' => $error,
                    'categorie' => $categorie,
                    'contributioncat' => $contributioncat,
                    'backid' => $sujet->getId(),
                ));
            }
            else{
                return $this->redirectToRoute('FastFocusCategorie', array(
                    'id' => $id,
                ));
            }


        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:fastfocuscategorie.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function broomwagonAction($id, Request $request){
        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }



        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Pourcentage', IntegerType::class)
            ->add('Definir', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $data["Nego"]->setSelection($form["Pourcentage"]->getData());
                $repository->persist($data["Nego"]);
                $repository->flush();


                $description = array();
                $form = $this->createFormBuilder($description)
                    ->add('Pourcentage', TextType::class)
                    ->add('Definir', SubmitType::class)
                    ->getForm();

                $time = $this->container->get('timer')->getime($data["Nego"]);
                $hours = $time["hours"];
                $minutes = $time["minutes"];
                $seconds = $time["seconds"];

                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BroomWagon:broomwagon.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'nego' => $data["Nego"],
                    'decideur' => $decideurs,
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BroomWagon:broomwagon.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'nego' => $data["Nego"],
            'decideur' => $decideurs,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function broomwagonratingAction($id, Request $request){

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

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($data["Nego"]->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        if($request->isMethod('POST')){
            $comp = 0;
            foreach ($contribution as $contrib){
                if(isset($_POST[$contrib->getId()])){
                    $comp++;
                }
            }
            if($comp > $nbreselection OR $comp < $nbreselection){
                if($comp < $nbreselection){
                    $error = "Vous avez sélectionné moins de ".$nbreselection." propositons !";
                }
                else{
                    $error = "Vous avez sélectionné plus de ".$nbreselection." propositons !";
                }
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BroomWagon:broomwagonselection.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'selection' => $nbreselection,
                    'pourcentage' => $data["Nego"]->getSelection(),
                    'error' => $error,
                ));
            }
            else{

                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                       $contrib->setLiked($contrib->getLiked()+1);
                       $repository->persist($contrib);

                    }
                }
                $decideurs->setSelection(true);
                $repository->flush();
                return $this->redirectToRoute('BroomWagon', array(
                    'id' => $id,
                ));
            }
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BroomWagon:broomwagonselection.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'pourcentage' => $data["Nego"]->getSelection(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function broomwagonclassementAction($id, Request $request){

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

        if($data["Nego"]->getSelection() != -1){
            $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                'phases' => $data["Gene"],
            ), array(
                'liked' => "DESC",
            ));
        }
        else{
            $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                'phases' => $data["Gene"],
                'categorie' => 'Select'
            ), array(
                'liked' => "DESC",
            ));
        }

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        if($request->isMethod("POST")){
            $pourcent = $_POST['pourcent'];

            $nbrecontrib = 0;

            foreach ($contribution as $contrib){
                $nbrecontrib++;
            }
            $nbreselection = ($pourcent*$nbrecontrib)/100;
            $nbreselection = round($nbreselection);

            if($nbreselection == 0){
                $nbreselection=1;
            }

            $comp = 0;
            foreach ($contribution as $contrib){
                if($comp<$nbreselection){
                    $contrib->setCategorie('Select');
                    $repository->persist($contrib);
                    $repository->flush();
                }
                $comp++;
            }

            $data["Nego"]->setSelection(-1);
            $repository->persist($data["Nego"]);
            $repository->flush();

            return $this->redirectToRoute('BroomWagon_classement', array('id' => $id));
        }
        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BroomWagon:broowagonratinglist.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'Nego' => $data["Nego"]
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pinthetailonthedonkeyAction($id, Request $request){

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Nom', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $categorie = new NegociationCategories();
                $categorie->setName($form["Nom"]->getData());
                $categorie->setPhase($data["Nego"]);

                $repository->persist($categorie);
                $repository->flush();

                $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'phase' => $data["Nego"]
                ));

                $description = array();
                $form = $this->createFormBuilder($description)
                    ->add('Nom', TextType::class)
                    ->add('Creer', SubmitType::class)
                    ->getForm();

                $time = $this->container->get('timer')->getime($data["Nego"]);
                $hours = $time["hours"];
                $minutes = $time["minutes"];
                $seconds = $time["seconds"];

                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:pin-the-Tail-on-the-Donkey.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'categorielist' => $categorielist,
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:pin-the-Tail-on-the-Donkey.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'categorielist' => $categorielist,
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pinthetailonthedonkeycategorizerAction($id, Request $request){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPlatformBundle:Phases')->find($categorie->getPhase());

        $processus = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phaseNego->getProcessus());

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($processus->getSujet());

        $phaseGene = $repository->getRepository('GDSSPlatformBundle:Phases')->findBy(array(
            'processus' => $processus,
            'nom' => 'Phase de Generations des solutions',
        ));

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ));


        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $admin = $this->container->get('platform.checkaccess')->adminAccess($sujet->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('PinTheTailOntheDonkey', array(
                'id' => $sujet->getId(),
            ));
        }

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($phaseNego->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contributioncat as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie("void");
                            $repository->persist($contrib);

                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie($categorie->getName());
                            $repository->persist($contrib);
                        }
                    }
                    $repository->flush();
                    return $this->redirectToRoute('PinTheTailOntheDonkey_Categorizer', array(
                        'id' => $id,
                    ));
                }
            }

            /*
             * Retour de la vue
             */
            if(isset($error)){
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:pin-the-tail-categorizer.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'selection' => $nbreselection,
                    'error' => $error,
                    'categorie' => $categorie,
                    'contributioncat' => $contributioncat,
                    'backid' => $sujet->getId(),
                ));
            }
            else{
                return $this->redirectToRoute('PinTheTailOntheDonkey_Categorizer', array(
                    'id' => $id,
                ));
            }

        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:pin-the-tail-categorizer.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pinthetailonthedonkeyratingAction($id, Request $request){


        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPlatformBundle:Phases')->find($categorie->getPhase());

        $processus = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phaseNego->getProcessus());

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($processus->getSujet());

        $phaseGene = $repository->getRepository('GDSSPlatformBundle:Phases')->findBy(array(
            'processus' => $processus,
            'nom' => 'Phase de Generations des solutions',
        ));

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ),
            array(
                'liked' => 'DESC'
            ));

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($sujet->getId(), $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($sujet->getId(), $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($phaseNego->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $selctionlist = $repository->getRepository('GDSSPhasesBundle:NegociationCategorieSelection')->findBy(array(
            'categories' => $categorie
        ));

        $alreadyselect = false;

        foreach ($selctionlist as $select){
            if($select->getDecideurs() == $decideurs){
                $alreadyselect = true;
            }
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){

            if(isset($_POST["Votez"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST['star'.$contrib->getId()])){
                        $vote = $_POST['star'.$contrib->getId()];
                        $contrib->setLiked($contrib->getLiked()+$vote);

                        $select = new NegociationCategorieSelection();

                        $select->setSelection(1);
                        $select->setCategories($categorie);
                        $select->setDecideurs($decideurs);

                        $repository->persist($select);
                        $repository->persist($contrib);
                        $repository->persist($decideurs);
                        $repository->flush();
                    }
                }
            }

            return $this->redirectToRoute('PinTheTailOntheDonkey_Rating', array(
                'id' => $id,
            ));

        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:pin-the-tail-rating.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
            'decideur' => $decideurs,
            'alreadyselect' => $alreadyselect,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expertchoiceAction($id, Request $request){

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $expertdefined = false;

        if ($data["Nego"]->getexpert() == "facilitateur" OR $data["Nego"]->getexpert() == "decideur"){
            $expertdefined = "definied";
        }

        if ($data["Nego"]->getexpert() == "facilitateur" AND $admin != false){
            $expertdefined = "facilitateur";
        }
        else if($data["Nego"]->getexpert() == "decideur" AND $decideurs !=null){
            if($decideurs->getExpert() == 1){
                $expertdefined = "decideur";
            }
        }

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Nom', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        $decideurslist = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findBy(array(
            'sujet' => $data["subject"],
        ));

        /*
         * FORM SUBMIT
         */
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $categorie = new NegociationCategories();
                $categorie->setName($form["Nom"]->getData());
                $categorie->setPhase($data["Nego"]);

                $repository->persist($categorie);
                $repository->flush();

                $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'phase' => $data["Nego"]
                ));

                $description = array();
                $form = $this->createFormBuilder($description)
                    ->add('Nom', TextType::class)
                    ->add('Creer', SubmitType::class)
                    ->getForm();

                $time = $this->container->get('timer')->getime($data["Nego"]);
                $hours = $time["hours"];
                $minutes = $time["minutes"];
                $seconds = $time["seconds"];

                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ExpertChoice:expertchoice.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'categorielist' => $categorielist,
                    'form' => $form->createView(),
                    'decideur' => $decideurs,
                    'expertdefinied' => $expertdefined,
                ));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ExpertChoice:expertchoice.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'categorielist' => $categorielist,
            'decideur' => $decideurs,
            'decideurslist' => $decideurslist,
            'expertdefinied' => $expertdefined,
        ));
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function expertchoicecategorizerAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPlatformBundle:Phases')->find($categorie->getPhase());

        $processus = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phaseNego->getProcessus());

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($processus->getSujet());

        $phaseGene = $repository->getRepository('GDSSPlatformBundle:Phases')->findBy(array(
            'processus' => $processus,
            'nom' => 'Phase de Generations des solutions',
        ));

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ));


        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($sujet->getId(), $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($sujet->getId(), $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $expertdefined = false;

        if ($phaseNego->getexpert() == "facilitateur" OR $phaseNego->getexpert() == "decideur"){
            $expertdefined = "definied";
        }

        if ($phaseNego->getexpert() == "facilitateur" AND $admin != false){
            $expertdefined = "facilitateur";
        }
        else if($phaseNego->getexpert() == "decideur" AND $decideurs !=null){
            if($decideurs->getExpert() == 1){
                $expertdefined = "decideur";
            }
        }

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($phaseNego->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contributioncat as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie("void");
                            $repository->persist($contrib);

                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectioner au moins une contribution ";
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie($categorie->getName());
                            $repository->persist($contrib);
                        }
                    }
                    $repository->flush();
                    return $this->redirectToRoute('ExpertChoice_Categorizer', array(
                        'id' => $id,
                    ));
                }
            }

            /*
             * Retour de la vue
             */
            if(isset($error)){
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ExpertChoice:expertchoiceCategorizer.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'selection' => $nbreselection,
                    'error' => $error,
                    'categorie' => $categorie,
                    'contributioncat' => $contributioncat,
                    'backid' => $sujet->getId(),
                    'expertdefinied' => $expertdefined,
                ));
            }
            else{
                return $this->redirectToRoute('ExpertChoice_Categorizer', array(
                    'id' => $id,
                ));
            }


        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ExpertChoice:expertchoiceCategorizer.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
            'expertdefinied' => $expertdefined,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function strawpoolhAction($id, Request $request){

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
        $contributionchart = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ), array(
            'liked' => 'DESC'
        ));

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($data["Nego"]->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        if($request->isMethod('POST')){

            if(isset($_POST["Evaluer"])){
                foreach ($contribution as $contrib){
                    if(isset($_POST['star'.$contrib->getId()])){
                        $vote = $_POST['star'.$contrib->getId()];
                        $contrib->setLiked($contrib->getLiked()+$vote);

                        $decideurs->setSelection(1);


                        $repository->persist($decideurs);
                        $repository->flush();
                    }
                }
            }

            return $this->redirectToRoute('StrawPoolh', array(
                'id' => $id,
            ));

        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/StrawPollh:strawpollh.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'pourcentage' => $data["Nego"]->getSelection(),
            'criteres' => $data['critere'],
            'decideurs' => $decideurs,
            'contributionchart' => $contributionchart,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function popcornsortAction($id, Request $request){

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }


        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $phaseNego = $data["Nego"];

        $sujet = $data["subject"];

        $phaseGene = $data["Gene"];

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
        ), array(
            'categorie' => 'DESC',
        ));


        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Nom', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        $categories = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $phaseNego,
        ));

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            $form->handleRequest($request);
            if($form->isValid()) {
                $categorie = new NegociationCategories();
                $categorie->setName($form["Nom"]->getData());
                $categorie->setPhase($data["Nego"]);

                $repository->persist($categorie);
                $repository->flush();

            }
            else if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contributioncat as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie("void");
                            $repository->persist($contrib);

                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie($_POST["categorie"]);
                            $repository->persist($contrib);
                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Retour de la vue
             */
            if(isset($error)){
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/PopcornSort:popcornsort.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    //'selection' => $nbreselection,
                    'error' => $error,
                    'categories' => $categories,
                    'contributioncat' => $contributioncat,
                    'backid' => $sujet->getId(),
                    'form' => $form->createView(),
                ));
            }
            else{
                return $this->redirectToRoute('PopCornSort', array(
                    'id' => $id,
                ));
            }

        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/PopcornSort:popcornsort.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            //'selection' => $nbreselection,
            'categories' => $categories,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
            'form' => $form->createView(),
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function multicriteriaAction($id, Request $request){
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
        $contributionchart = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
        ), array(
            'liked' => 'DESC'
        ));

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($data["Nego"]->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;

        $alldecideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findBy(array(
            'sujet' => $id,
        ));

        $alldecideurs = count($alldecideurs);

        $agrement = array();
        $comp = 0;

        foreach ($contributionchart as $ct){
            $agrement[$comp] = ($ct->getLiked()*100)/($alldecideurs*5);
            $comp++;
        }


        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        if($request->isMethod('POST')){

            if(isset($_POST["Evaluer"])){
                foreach ($contribution as $contrib){
                    if(isset($_POST['star'.$contrib->getId()])){
                        $vote = $_POST['star'.$contrib->getId()];
                        $contrib->setLiked($contrib->getLiked()+$vote);

                        $decideurs->setSelection(1);


                        $repository->persist($decideurs);
                        $repository->flush();
                    }
                }
            }

            return $this->redirectToRoute('MultiCriteria', array(
                'id' => $id,
            ));

        }




        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/MultiCriteria:multicriteria.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'pourcentage' => $data["Nego"]->getSelection(),
            'criteres' => $data['critere'],
            'decideurs' => $decideurs,
            'contributionchart' => $contributionchart,
            'agrement' => $agrement,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkMarkAction($id, Request $request){

        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $contributionorder = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ),
            array(
           'liked' => 'DESC'
        ));


        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }



        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Pourcentage', IntegerType::class)
            ->add('Definir', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){

            $form->handleRequest($request);
            if($form->isValid()){


                $data["Nego"]->setSelection($form["Pourcentage"]->getData());
                $repository->persist($data["Nego"]);
                $repository->flush();

                return $this->redirectToRoute('CheckMark', array('id' => $id));
            }
            else{
                $comp = 0;
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp > $data["Nego"]->getselection() OR $comp < $data["Nego"]->getselection()){
                    if($comp < $data["Nego"]->getselection()){
                        $error = "Vous avez sélectionné moins de ".$data["Nego"]->getselection()." propositons !";
                    }
                    else{
                        $error = "Vous avez sélectionné plus de ".$data["Nego"]->getselection()." propositons !";
                    }
                    $now = new \DateTime();

                    $time = $this->container->get('timer')->getime($data["Nego"]);
                    $hours = $time["hours"];
                    $minutes = $time["minutes"];
                    $seconds = $time["seconds"];
                    $finish = false;
                    if($data["Nego"]->getDateFin() < $now){
                        $finish = true;
                    }
                    return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/CheckMark:checkMark.html.twig', array(
                        'id' => $id,
                        'admin' => $admin,
                        'contributionorder' => $contributionorder,
                        'contribution' => $contribution,
                        'comment' => $comment,
                        'finish' => $finish,
                        'progress' => $progress,
                        'hours' => $hours,
                        'minutes' => $minutes,
                        'seconds' => $seconds,
                        'form' => $form->createView(),
                        'nego' => $data["Nego"],
                        'decideur' => $decideurs,
                        'allcontrib' => count($contribution),
                        'error' => $error,
                    ));
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setLiked($contrib->getLiked()+1);
                            $repository->persist($contrib);

                        }
                    }
                    $decideurs->setSelection(true);
                    $repository->flush();
                    return $this->redirectToRoute('CheckMark', array(
                        'id' => $id,
                    ));
                }
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/CheckMark:checkMark.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'nego' => $data["Nego"],
            'decideur' => $decideurs,
            'allcontrib' => count($contribution),
            'contributionorder' => $contributionorder,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function bucketvoteAction($id, Request $request){
        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('platform.sujectdata')->sujetdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Name', TextType::class)
            ->add('Priority', ChoiceType::class, array(
                'choices' => array(
                    'Très prioritaire' => '1',
                    'Moyennement prioritaire' => '2',
                    'Faiblement prioritaire' => '3',
                )
            ))
            ->add('Creer', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $categorie = new NegociationCategories();
                $categorie->setName($form["Name"]->getData());
                $categorie->setPriority($form["Priority"]->getData());
                $categorie->setPhase($data["Nego"]);

                $repository->persist($categorie);
                $repository->flush();

                $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'phase' => $data["Nego"]
                ));

               return $this->redirectToRoute('BucketVote', array('id' => $id));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BucketVote:bucketvote.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'categorielist' => $categorielist,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function bucketvotecategorieAction($id, Request $request){
        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPlatformBundle:Phases')->find($categorie->getPhase());

        $processus = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phaseNego->getProcessus());

        $sujet = $repository->getRepository('GDSSPlatformBundle:Sujet')->find($processus->getSujet());

        $phaseGene = $repository->getRepository('GDSSPlatformBundle:Phases')->findBy(array(
            'processus' => $processus,
            'nom' => 'Phase de Generations des solutions',
        ));

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => "void",
        ));

        $contributioncat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ));

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $decideurs=$this->container->get('platform.checkaccess')->decideursAccess($sujet->getId(), $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($sujet->getId(), $user);
        if($admin == false AND $decideurs == null){
            return $this->redirectToRoute('gdss_platform_sujets');
        }



        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($phaseNego->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectiooner au moins une contribution ";
                }
                else{

                    foreach ($contributioncat as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie("void");
                            $repository->persist($contrib);

                        }
                    }
                    $repository->flush();
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $comp++;
                    }
                }
                if($comp == 0 ){
                    $error = "Vous devez sélectioner au moins une contribution ";
                }
                else{

                    foreach ($contribution as $contrib){
                        if(isset($_POST[$contrib->getId()])){
                            $contrib->setCategorie($categorie->getName());
                            $repository->persist($contrib);
                        }
                    }
                    $repository->flush();
                    return $this->redirectToRoute('BucketVoteCategorie', array(
                        'id' => $id,
                    ));
                }
            }

            /*
             * Retour de la vue
             */
            if(isset($error)){
                return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BucketVote:bucketvotecategorie.html.twig', array(
                    'id' => $id,
                    'admin' => $admin,
                    'contribution' => $contribution,
                    'comment' => $comment,
                    'finish' => $finish,
                    'progress' => $progress,
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'seconds' => $seconds,
                    'selection' => $nbreselection,
                    'error' => $error,
                    'categorie' => $categorie,
                    'contributioncat' => $contributioncat,
                    'backid' => $sujet->getId(),
                ));
            }
            else{
                return $this->redirectToRoute('ExpertChoice_Categorizer', array(
                    'id' => $id,
                ));
            }


        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BucketVote:bucketvotecategorie.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'selection' => $nbreselection,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $sujet->getId(),
        ));
    }



}