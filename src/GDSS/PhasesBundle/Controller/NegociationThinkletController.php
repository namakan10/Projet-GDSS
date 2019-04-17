<?php
/**
 * Created by PhpStorm.
 * User: Namakan
 * Date: 25/10/2018
 * Time: 11:40
 */

namespace GDSS\PhasesBundle\Controller;


use GDSS\PhasesBundle\Entity\GenerationComment;
use GDSS\PhasesBundle\Entity\MakersGroup;
use GDSS\PhasesBundle\Entity\NegociationCategories;
use GDSS\PhasesBundle\Entity\NegociationCategorieSelection;
use GDSS\PhasesBundle\Entity\NegociationFormVote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class NegociationThinkletController extends Controller
{

    public function negociationvoteformAction($id, $phase, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();

        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem', array('id' => $id));
        }

        if(isset($maker)){
            if($maker->getSelection() == 1){
                return $this->redirectToRoute('problem', array('id' => $id));
            }
        }

        $data = $this->container->get('problemdata')->problemdata($id);
        $problem = $data["problem"];
        $criteria = $data["criteria"];
        $makers = null;
        if($admin){
            $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
                'process' => $problem->getProcess(),
                'selection' => 1
            ));
        }
        $now = new \DateTime();

        $time = null;
        $finish = false;
        if($phase == "phase1"){
            $time = $this->container->get('timer')->getime($data["PreNego1"]);
            if($data["PreNego1"]->getDateEnd() < $now){
                $finish = true;
            }
        }
        else if($phase == "phase2"){
            $time = $this->container->get('timer')->getime($data["PreNego2"]);
            if($data["PreNego2"]->getDateEnd() < $now){
                $finish = true;
            }
        }

        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];

        if($finish == true){
            return $this->redirectToRoute('problem', array('id' => $id));
        }



        if($request->isMethod('POST')){
            if($phase == "phase1"){
                $formulation = $_POST["formulation"];
                $expert = $_POST["expert"];

                $ambiguity = $_POST["ambiguity"];

                $vote = new NegociationFormVote();
                $vote->setProblem($problem);
                $vote->setPhase("1");
                $vote->setAmbiguity($ambiguity);
                $vote->setExpert($expert);
                $vote->setFormulation($formulation);
                if(isset($_POST["revelant_list"])){
                    $revelant_list = $_POST["revelant_list"];
                    $vote->setRevelantlist($revelant_list);
                }
                else{
                    $vote->setRevelantlist(0);
                }
                $vote->setMakers($maker);

                $maker->setSelection(1);

                $repository->persist($vote);
                $repository->persist($maker);
            }
            elseif($phase == "phase2"){
                $choice = $_POST["choice"];

                $vote = new NegociationFormVote();
                $vote->setProblem($problem);
                $vote->setPhase("2");
                $vote->setMakers($maker);
                if($choice == "category"){
                    $vote->setCategory(1);
                }
                else if ($choice == "categorizer"){
                    $vote->setCategorizer(1);
                }

                $maker->setSelection(1);

                $repository->persist($vote);
                $repository->persist($maker);

            }
            $repository->flush();

            return $this->redirectToRoute('problem', array('id' => $id));
        }

        return $this->render('@GDSSPhases/phases_view/Negociation_ThinkLet/negociation_form_vote.hml.twig', array(
            'id' => $id,
            'admin' => $admin,
            'criteria' => $criteria,
            'makers' => $makers,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'phase' => $phase,
            'finish' => $finish
        ));
    }

    /**
     * @param $id
     * @param $phase
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function negociationAction($id, $phase){


        $user = $this->getUser();

        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem');
        }

        $data = $this->container->get('problemdata')->problemdata($id);

        if($phase == '1'){
            $negophase = $data["Nego1"];
            if($negophase->getThinklet() ==  "FastFocus"){
                return $this->redirectToRoute('FastFocus', array('id' => $id, 'thinklet' => "FastFocus"));
            }
            else if($negophase->getThinklet() ==  "BroomWagon"){
                return $this->redirectToRoute('BroomWagon', array('id' => $id));
            }
            else if($negophase->getThinklet() ==  "ExpertChoice"){
                return $this->redirectToRoute('ExpertChoice', array('id' => $id));
            }
            else if($negophase->getThinklet() ==  "Pin-The-Tail-On-The-Donkey"){
                return $this->redirectToRoute('PinTheTailOntheDonkey', array('id' => $id));
            }
            else if($negophase->getThinklet() ==  "OneUp"){
                return $this->redirectToRoute('OneUp', array('id' => $id));
            }
            else if($negophase->getThinklet() ==  "Concentration"){
                return $this->redirectToRoute('', array('id' => $id));
            }
            else if($negophase->getThinklet() ==  "BucketBriefing"){
                return $this->redirectToRoute('BucketBriefing', array('id' => $id));
            }
            else if($negophase->getThinklet() == "GoldMiner"){
                return $this->redirectToRoute('FastFocus', array('id' => $id, 'thinklet' => "GoldMiner"));
            }
            else if($negophase->getThinklet() == "ThemeSeeker"){
                return $this->redirectToRoute('ThemeSeeker', array('id' => $id));
            }
        }
        else if($phase == '2'){
            $negophase = $data["Nego2"];
            if ($negophase->getThinklet() == "ThemeSeeker"){
                return $this->redirectToRoute('ThemeSeeker', array(
                    'id' => $id,
                ));
            }
            else if ($negophase->getThinklet() == "Evolution"){
                return $this->redirectToRoute('Evolution', array(
                    'id' => $id,
                ));
            }
            else if($negophase->getThinklet() == "ChauffeurSort"){
                return $this->redirectToRoute('Chauffeur_Sort', array(
                    'id' => $id,
                ));
            }
            else if($negophase->getThinklet() == "PopCornSort"){
                return $this->redirectToRoute('PopCornSort', array(
                    'id' => $id,
                ));
            }
        }

       return new Response();
    }

    public function listnegociationthinkletAction($id){

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet:List_Negociation_Thinklet.html.twig', array(
            'id' => $id
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function bucketbriefingAction($id, Request $request){
        $error = '';

        if(isset($_GET['error'])){
            $error = $_GET['error'];
        }

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
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

        $allmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $data["process"]
        ));


        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego"]->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Gene"]);

        $categorieslist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego"],
        ));

        $allowcategorielist = array();
        $allowcategorie = null;
        $comp = 0;
        if($maker != null){
            foreach ($categorieslist as $cat){
                $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
                    'categorie' => $cat,
                ));
                if(!empty($group)){
                    foreach ($group as $gp){
                        if($gp->getMaker() == $maker){
                            $allowcategorielist[$comp] = $cat->getId();
                            $comp++;
                        }
                    }
                }
            }
            if(!empty($allowcategorielist)){
                $allowcategorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'id' => $allowcategorielist,
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

                $categories = new NegociationCategories();
                $categories->setName($form["Titre"]->getData());
                $categories->setPhase($data["Nego"]);
                $repository->persist($categories);
                $repository->flush();


                return $this->redirectToRoute('BucketBriefing', array('id' => $id));

            }

            else{
                var_dump($_POST);
            }
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BucketBriefing:BucketBriefing.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'finish' => $finish,
            'allmakers' => $allmakers,
            'progress' => $progress,
            'categorielist' => $categorieslist,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'error' => $error,
            'allowcategorie' => $allowcategorie,
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function bucketbriefingcategirizerAction($id, Request $request){
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
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
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('PinTheTailOntheDonkey', array(
                'id' => $problem->getId(),
            ));
        }

        $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
            'categorie' => $categorie
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
            "process" => $process
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

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'phase' => $phaseGene,
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateEnd() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){
                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie("void");
                        $repository->persist($contrib);
                    }
                }
            }

            /*
             * Catégoriser
             */
            else if(isset($_POST['Catégoriser'])){
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie($categorie->getName());
                        $repository->persist($contrib);
                    }
                }
            }
            else if(isset($_POST['Affecter'])){
                foreach ($_POST as $key){
                    if($key != "Affecter"){
                        $makers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($key);
                        $group = new MakersGroup();
                        $group->setName($categorie->getName());
                        $group->setMaker($makers);
                        $group->setCategorie($categorie);
                        $group->setPhase("Nego");
                        $repository->persist($group);
                    }
                }
            }
            else if (isset($_POST['Désaffecter'])){
                foreach ($_POST as $key){
                    if($key != "Désaffecter"){
                        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($key);
                        $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findOneBy(array(
                            'categorie' => $categorie,
                            'maker' => $maker
                        ));
                        $repository->remove($group);
                    }
                }
            }
            $repository->flush();

            return $this->redirectToRoute('BucketBriefing_Categorizer', array(
                'id' => $id,
            ));

        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/BucketBriefing:BucketBriefingCategorizer.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'allmakers' => $allmakers,
            'backid' => $problem->getId(),
            'allowmakers' => $allowmakers,
            'notallowmakers' => $notallowmakers,
        ));
    }


    /**
     * @param $id
     * @param $thinklet
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fastfocusAction($id, $thinklet,Request $request){

        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene'],
            'selection' => 0,
        ));
        $contributionselect = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene'],
            'selection' => 1
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'phase' => $data["Gene"],
        ));


        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);


        if($request->isMethod('POST')){
            foreach ($contribution as $contrib){
                if(isset($_POST[$contrib->getId()])){
                    $contrib->setSelection(1);
                    $repository->persist($contrib);
                }
            }
            $maker->setSelection(true);
            $repository->flush();
            if($thinklet == "GoldMiner"){
                return $this->redirectToRoute('FastFocus', array('id' => $id, 'thinklet' => "GoldMiner"));
            }
            else if($thinklet ==  "FastFocus"){
                return $this->redirectToRoute('FastFocus', array('id' => $id, 'thinklet' => "FastFocus"));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:fastfocus2.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'thinklet' => $thinklet,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'contributionselect' => $contributionselect,
            'maker' => $maker,
        ));
    }


    /**
     * @param $id
     * @param Request $request
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
    public function oneupAction($id, Request $request){
        $error = '';

        if(isset($_GET['error'])){
            $error = $_GET['error'];
        }
        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateend() < $now){
            $finish = true;
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego1"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);

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
                $categorie->setPhase($data["Nego1"]);
                $repository->persist($categorie);
            }
            $repository->flush();
            return $this->redirectToRoute('OneUp', array('id' => $id));
        }

        return $this->render('@GDSSPhases/phases_view/Negociation_ThinkLet/OneUp/oneup.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'form' => $form->createView(),
            'categorielist' => $categorielist,
            'error' => $error,
            'problem' => $data['problem'],
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function oneupcategorizerAction($id, Request $request){

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
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
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('OneUp', array(
                'id' => $problem->getId(),
            ));
        }
        if($categorie->getAllow() == 1){
            return $this->redirectToRoute('OneUp', array(
                'id' => $problem->getId(),
            ));
        }
        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'phase' => $phaseGene
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){

                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie("void");
                        $repository->persist($contrib);
                    }
                }
                $repository->flush();
            }

            /*
             * Catégoriser
             */
            else{

                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie($categorie->getName());
                        $repository->persist($contrib);
                    }
                }
                $repository->flush();

            }
            return $this->redirectToRoute('OneUpCategorizer', array(
                'id' => $id,
            ));
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/OneUp:oneupcategorizer.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $problem->getId(),
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function oneupselectionAction($id, Request $request){
        $user = $this->getUser();

        $repository = $this->getDoctrine()->getManager();

        $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
        ));


        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phaseGene,
            'categorie' => $categorie->getName(),
        ));

        /*
         * CHECK ACCESS
         */
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == true){
            return $this->redirectToRoute('OneUp', array(
                'id' => $problem->getId(),
            ));
        }
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($problem->getId(), $user);

        if($maker == null){
            return $this->redirectToRoute('problem_list');
        }

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'phase' => $phaseGene
        ));

        $selectionlist = $repository->getRepository('GDSSPhasesBundle:NegociationCategorieSelection')->findBy(array(
            'makers' => $maker,
            'categories' => $categorie,
        ));

        $alreadyselect = false;

        if(count($selectionlist)> 0){
            $alreadyselect = true;
        }

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateend() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        if($request->isMethod('POST')){
            $contribid = $_POST['customRadio'];
            $argument = $_POST['argument'];
            $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($contribid);
            $contrib->setLiked($contrib->getLiked()+1);
            $repository->persist($contrib);
            if ($argument != ''){
                $comment = new GenerationComment();
                $comment->setPhase($phaseNego);
                $comment->setComment($argument);
                $comment->setContribution($contrib);
                $comment->setReaction('Raison');
                $comment->setUser($user);
                $comment->setPseudo($maker->getPseudoMaker());
                $repository->persist($comment);
            }
            $maker->setSelection(1);
            $repository->persist($maker);
            $repository->flush();
            return $this->redirectToRoute('OneUpSelection', array('id' => $id));
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/OneUp:OneUpSelection.html.twig', array(
            'id' => $id,
            'problem' => $problem,
            'alreadyselect' => $alreadyselect,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'maker' => $maker,
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateEnd() < $now){
            $finish = true;
        }

        if ($finish == false){
            $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                'phases' => $data["Gene"],
            ), array(
                'liked' => "DESC",
            ));

        }
        else {
            if($data["Nego1"]->getSelection() != -1){
                if($admin){
                    $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                        'phases' => $data["Gene"],
                    ), array(
                        'liked' => "DESC",
                    ));

                    $pourcent = $data["Nego1"]->getSelection();

                    $nbrecontrib = count($contribution);

                    $nbreselection = ($pourcent*$nbrecontrib)/100;
                    $nbreselection = round($nbreselection);

                    if($nbreselection == 0){
                        $nbreselection=1;
                    }

                    $comp = 0;
                    foreach ($contribution as $contrib){
                        if($comp<$nbreselection){
                            $contrib->setSelection(1);
                            $repository->persist($contrib);
                        }
                        $comp++;
                    }
                    foreach ($data['allmakers'] as $mak) {
                        $mak->setSelection(0);
                        $repository->persist($mak);
                    }

                    $data["Nego1"]->setSelection(-1);
                    $repository->persist($data["Nego1"]);
                    $repository->flush();
                }
            }
            $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                'phases' => $data["Gene"],
                'selection' => 1
            ), array(
                'liked' => "DESC",
            ));
        }

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));


        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);

        /*
         * Calcul du nombre exact de proposition à sélectionner en fonction du pourcentage
         */
        $nbrecontrib = 0;
        foreach ($contribution as $contrib){
            $nbrecontrib++;
        }
        $nbreselection = ($data["Nego1"]->getSelection()*$nbrecontrib)/100;
        $nbreselection = round($nbreselection);


        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Pourcentage', IntegerType::class)
            ->add('Definir', SubmitType::class)
            ->getForm();

        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $data["Nego1"]->setSelection($form["Pourcentage"]->getData());
                $repository->persist($data["Nego1"]);
            }
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setLiked($contrib->getLiked()+1);
                        $repository->persist($contrib);

                    }
                }
                $maker->setSelection(true);
            }

            $repository->flush();
            return $this->redirectToRoute('BroomWagon', array('id' => $id));
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
            'nego' => $data["Nego1"],
            'maker' => $maker,
            'selection' => $nbreselection,
            'pourcentage' => $data["Nego1"]->getSelection(),
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        if($data["Nego1"]->getSelection() != -1){
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

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateEnd() < $now){
            $finish = true;
        }

        if($request->isMethod("POST")){
            $pourcent = $_POST['pourcent'];

            $nbrecontrib = count($contribution);

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
            'Nego' => $data["Nego1"]
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ));

        $odercontribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene']
        ), array(
            'liked' => 'DESC'
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateEnd() < $now){
            $finish = true;
        }


        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);



        if($request->isMethod('POST')){
            if(isset($_POST["Votez"])){
                foreach ($contribution as $contrib){
                    if(isset($_POST['star'.$contrib->getId()])){
                        $vote = $_POST['star'.$contrib->getId()];
                        $contrib->setLiked($contrib->getLiked()+$vote);
                        $maker->setSelection(1);
                        $repository->persist($contrib);
                        $repository->persist($maker);
                        $repository->flush();
                    }
                }
            }

            return $this->redirectToRoute('PinTheTailOntheDonkey', array(
                'id' => $id,
            ));
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
            'maker' => $maker,
            'ordercontribution' => $odercontribution,
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

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
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
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false){
            return $this->redirectToRoute('PinTheTailOntheDonkey', array(
                'id' => $problem->getId(),
            ));
        }

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'phase' => $phaseGene,
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateEnd() < $now){
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
                        $contrib->setCategorie("void");
                        $repository->persist($contrib);
                    }
                }
            }

            /*
             * Catégoriser
             */
            else{
                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie($categorie->getName());
                            $repository->persist($contrib);
                        }
                }
            }
            $repository->flush();

            return $this->redirectToRoute('PinTheTailOntheDonkey_Categorizer', array(
                'id' => $id,
            ));

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
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $problem->getId(),
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

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($problem->getId(), $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }



        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contributioncat
        ));

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateEnd() < $now){
            $finish = true;
        }

        $selectionlist = $repository->getRepository('GDSSPhasesBundle:NegociationCategorieSelection')->findBy(array(
            'makers' => $maker,
            'categories' => $categorie,
        ));

        $alreadyselect = false;

        if(count($selectionlist)> 0){
            $alreadyselect = true;
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
                        $select->setMakers($maker);
                        $repository->persist($select);
                        $repository->persist($contrib);
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
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $problem->getId(),
            'maker' => $maker,
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        //Savoir si l'expert est definie
        $expertdefined = false;
        if ($data["Nego1"]->getexpert() == "definied"){
            $expertdefined = "definied";
        }

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($data["Nego1"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego1"]->getDateEnd() < $now){
            $finish = true;
        }
        //Delai pour le vote de l'expert 5 min
        $delaychoice = false;
        //Si le facilitateur doit selectionner
        $adminchoice = false;
        //Les makers avec mm nbre de vote
        $makerequalvote = null;

        if($finish == false){
            if($minutes>24){
                $delaychoice = true;
            }

            else{
                if ($data["Nego1"]->getexpert() == "definied"){
                    $expertdefined = "definied";
                }
                else{
                    $allmakers = $data["allmakers"];
                    $expert = null;
                    foreach ($allmakers as $mak){
                        if($expert == null){
                            $expert = $mak;
                        }
                        else{
                            if($expert->getVote() < $mak->getVote()){
                                $expert = $mak;
                            }
                        }
                    }
                    $makerequalvote = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
                        'vote' => $expert->getVote()
                    ));
                    if(count($makerequalvote) > 1){
                        $expertdefined = false;
                        $adminchoice = true;
                    }
                    else{
                        $expert->setExpert(1);
                        $data["Nego1"]->setExpert("definied");
                        foreach ($allmakers as $mak){
                            $mak->setSelection(0);
                            $repository->persist($mak);
                        }
                        $repository->persist($expert);
                        $repository->flush();
                    }
                }

            }
        }

        $categorielist = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego1"]
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);

        $description = array();

        $form = $this->createFormBuilder($description)
            ->add('Nom', TextType::class)
            ->add('Creer', SubmitType::class)
            ->getForm();

        $makerlist = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $data["process"],
        ));

        /*
         * FORM SUBMIT
         */
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $categorie = new NegociationCategories();
                $categorie->setName($form["Nom"]->getData());
                $categorie->setPhase($data["Nego1"]);

                $repository->persist($categorie);
                $repository->flush();

                return $this->redirectToRoute('ExpertChoice', array('id' => $id));
            }
            else if (isset($_POST['customRadio'])){
                $makid = $_POST['customRadio'];
                $makerselec = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($makid);
                $makerselec->setVote($makerselec->getSelection()+1);
                $maker->setSelection(1);
                $repository->persist($makerselec);
                $repository->persist($maker);
                $repository->flush();

                return $this->redirectToRoute('ExpertChoice', array('id' => $id));
            }
        }

        $makerexpert = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $data["process"],
            'expert' => 1
        ));


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
            'maker' => $maker,
            'delaychoice' => $delaychoice,
            'makerlist' => $makerlist,
            'expertdefinied' => $expertdefined,
            'adminchoice' => $adminchoice,
            'makerequalvote' => $makerequalvote,
            'problem' => $data["problem"],
            'makerexpert' => $makerexpert,
            'user' => $this->getUser(),
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

        $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phaseNego->getProcess());

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process' => $process,
            'name' => 'Gene',
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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($problem->getId(), $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($problem->getId(), $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }
        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();

        $now = new \DateTime();

        $time = $this->container->get('timer')->getime($phaseNego);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($phaseNego->getDateEnd() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($phaseNego);

        $expert = false;
        if($maker != null){
            if($maker->getExpert() == 1){
                $expert = true;
            }
        }
        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Décatégoriser"])){

                foreach ($contributioncat as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie("void");
                        $repository->persist($contrib);
                    }
                }
                $repository->flush();
            }

            /*
             * Catégoriser
             */
            else{

                foreach ($contribution as $contrib){
                    if(isset($_POST[$contrib->getId()])){
                        $contrib->setCategorie($categorie->getName());
                        $repository->persist($contrib);
                    }
                }
                $repository->flush();
            }

            return $this->redirectToRoute('ExpertChoice_Categorizer', array(
                'id' => $id,
            ));

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
            'categorie' => $categorie,
            'contributioncat' => $contributioncat,
            'backid' => $problem->getId(),
            'expert' => $expert,
        ));
    }



    public function themeseekerAction($id, Request $request){
        /*
         * CHECK ACCESS
         */
        $user = $this->getUser();
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene'],
            'selection' => 1,
        ));
        $contributionselect = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data['Gene'],
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contributionselect,
        ));


        $dureemax = 30;
        $now = new \DateTime();
        $now2 = new \DateTime();
        $end = $now2->modify("+".$dureemax." minutes");

        $time = $this->container->get('timer')->getime($data["Nego2"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego2"]->getDateend() < $now){
            $finish = true;
        }
        if($finish == true){
            $data["Nego2"]->setDateStart($now);
            $data["Nego2"]->setDateend($end);
            if($data["Nego1"]->getThinklet() == "FastFocus"){
                $data["Nego2"]->setThinklet("PopCornSort");
                $repository->persist($data["Nego2"]);
                $repository->flush();
                return $this->redirectToRoute('PopCornSort', array('id' => $id));
            }
            else{
                $data["Nego2"]->setThinklet("ChauffeurSort");
                $repository->persist($data["Nego2"]);
                $repository->flush();
                return $this->redirectToRoute('Chauffeur_Sort', array('id' => $id));
            }
        }

        $data0 = array();
        $form = $this->createFormBuilder($data0)
            ->add('name', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('Creer', SubmitType::class)
            ->getForm();
        $progress = $this->container->get('platform.progress')->progression($data["Nego2"]);

        $category = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego2"]
        ));


        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $find = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
                    'phase' => $data["Nego2"],
                    'name' => $form['name']->getData(),
                ));
                if(count($find) == 0){
                    $negocategory = new NegociationCategories();
                    $negocategory->setName($form['name']->getData());
                    $negocategory->setPhase($data["Nego2"]);
                    $repository->persist($negocategory);
                    $repository->flush();
                }
                return $this->redirectToRoute('ThemeSeeker', array('id' => $id));
            }
        }

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ThemeSeeker:themeseeker.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'form' => $form->createView(),
            'contribution' => $contribution,
            'comment' => $comment,
            'finish' => $finish,
            'progress' => $progress,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'contributionselect' => $contributionselect,
            'maker' => $maker,
            'category' => $category,
        ));
    }



    public function concentrationAction($id){

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
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }


        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $phaseNego = $data["Nego2"];

        $problem = $data["problem"];

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
        if($phaseNego->getDateEnd() < $now){
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
                    'backid' => $problem->getId(),
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
            'backid' => $problem->getId(),
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


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function chauffeursortAction($id, Request $request){

        $user = $this->getUser();
        $makers=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $makers == null){
            return $this->redirectToRoute('problem_list');
        }

        $data = $this->container->get('problemdata')->problemdata($id);

        $repository = $this->getDoctrine()->getManager();

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findOneBy(array(
            'phases' => $data['Gene'],
            'selection' => 1,
            'categorie' => "void",
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        if($admin){
            $pseudo = "Facilitateur";
        }
        else{
            $pseudo = $makers->getPseudoMaker();
        }

        $chat = $repository->getRepository('GDSSPhasesBundle:Chat')->findBy(array(
            'phase' => $data["Nego2"]
        ));

        $categories = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego2"]
        ));

        $time = $this->container->get('timer')->getime($data["Nego2"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego2"]->getDateend() < $now){
            $finish = true;
        }

        if($request->isMethod("POST")){
            if(isset($_POST["categorie"])){
                $catid = $_POST["categorie"];
                $cat = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($catid);
                $contribution->setCategorie($cat->getName());
                $repository->persist($contribution);
                $repository->flush();

                return $this->redirectToRoute('Chauffeur_Sort', array(
                    'id' => $id
                ));
            }
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/ChauffeurSort:ChauffeurSort.html.twig', array(
            'id' => $id,
            'contribution' => $contribution,
            'comment' => $comment,
            'admin' => $admin,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'pseudo' => $pseudo,
            'chat' => $chat,
            'user' => $user,
            'categories' => $categories,
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function evolutionAction($id, Request $request){

        $user = $this->getUser();
        $makers=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $makers == null){
            return $this->redirectToRoute('problem_list');
        }

        $data = $this->container->get('problemdata')->problemdata($id);

        $repository = $this->getDoctrine()->getManager();

        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findOneBy(array(
            'phases' => $data['Gene'],
            'selection' => 1,
            'categorie' => "void",
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        $now = new \DateTime();

        if($admin){
            $pseudo = "Facilitateur";
        }
        else{
            $pseudo = $makers->getPseudoMaker();
        }

        $chat = $repository->getRepository('GDSSPhasesBundle:Chat')->findBy(array(
            'phase' => $data["Nego2"]
        ));

        $categories = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->findBy(array(
            'phase' => $data["Nego2"]
        ));

        $time = $this->container->get('timer')->getime($data["Nego2"]);
        $hours = $time["hours"];
        $minutes = $time["minutes"];
        $seconds = $time["seconds"];
        $finish = false;
        if($data["Nego2"]->getDateend() < $now){
            $finish = true;
        }

        if($request->isMethod("POST")){
            if(isset($_POST["categorizer"])){
                $catid = $_POST["categorie"];
                $cat = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($catid);
                $contribution->setCategorie($cat->getName());
                $repository->persist($contribution);
                $repository->flush();

            }
            else if(isset($_POST["Creer"])){
                $cat = new NegociationCategories();
                $cat->setPhase($data["Nego2"]);
                $cat->setName($_POST['catname']);
                $repository->persist($cat);
                $contribution->setCategorie($cat->getName());
                $repository->persist($cat);
                $repository->flush();
            }

            return $this->redirectToRoute('Evolution', array(
                'id' => $id
            ));
        }


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Evolution:Evolution.html.twig', array(
            'id' => $id,
            'contribution' => $contribution,
            'comment' => $comment,
            'admin' => $admin,
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'pseudo' => $pseudo,
            'chat' => $chat,
            'user' => $user,
            'categories' => $categories,
        ));

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pointcounterpointAction($id, Request $request){

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

        $decideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findBy(array(
            'sujet' => $data['subject']
        ));

        $listdecideurs = array();
        $comp = 0;


        $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $data["Gene"],
            'status' => "Posté",
        ));

        $contribshow = round(count($contribution)/3);

        $contribidlist = array();
        $contribidlistshow = array();
        $comp = 0 ;

        foreach ($contribution as $ct){
            $contribidlist[$comp] = $ct->getId();
            $comp++;
        }

        $rand_keys = array_rand($contribidlist, $contribshow);
        $comp = 0;
        foreach ($rand_keys as $key){

            $contribidlistshow[$comp] = $contribidlist[$rand_keys[$comp]];
            $k = array_search($contribidlist[$rand_keys[$comp]], $contribidlist);
            unset($contribidlist[$k]);
            $comp++;
        }

        //$contribidlist = array_merge(array("0" => ''), $contribidlist);


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


        return $this->render('@GDSSPhases/phases_view/Negociation_ThinkLet/PointCounterPoint/pointcounterpoint.html.twig', array(
            'contribution' => $contribution,
            'comment' => $comment,
            'users' => $this->getUser(),
            'finish' => $finish,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'id' => $id,
            'contriblistshow' => $contribidlistshow
        ));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function redligthgreenligthAction($id, Request $request){
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
        ), array(
            'color' => 'ASC',
            'liked' => 'DESC',
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

            if($ct->getColor() == null){
                if($agrement[$comp]>=70){
                    $ct->setColor("green");
                }
                else if($agrement[$comp]<70 AND $agrement[$comp]>=50){
                    $ct->setColor("lightgreen");
                }
                else if($agrement[$comp]<50 AND $agrement[$comp]>=30){
                    $ct->setColor("yellow");
                }
                else if($agrement[$comp]<30 AND $agrement[$comp]>=10){
                    $ct->setColor("orange");
                }
                else if($agrement[$comp]<10){
                    $ct->setColor("red");
                }
            }
            $repository->persist($ct);
            $repository->flush();
            $comp++;
        }

        if($data["Nego"]->getDateFin() < $now){
            $finish = true;
        }

        $progress = $this->container->get('platform.progress')->progression($data["Nego"]);

        if($request->isMethod('POST')){
            $comp = 0;

            /*
             * Décatégoriser
             */
            if(isset($_POST["Change"])){
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
                            $contrib->setColor($_POST["color"]);

                            $repository->persist($contrib);
                            $repository->flush();
                        }
                    }
                }
            }



            return $this->redirectToRoute('RedLigthGreenLigth', array(
                    'id' => $id,
                ));


        }




        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/RedLightGreenLight:redlightgreenlight.html.twig', array(
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


    public function crowbarAction($id, Request $request){
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


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/CrowBar:crowbar.html.twig', array(
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

}