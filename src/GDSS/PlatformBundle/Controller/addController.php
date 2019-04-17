<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 31/05/2018
 * Time: 10:07
 */

namespace GDSS\PlatformBundle\Controller;


use GDSS\PhasesBundle\Entity\Phase;
use GDSS\PlatformBundle\Entity\Constraints;
use GDSS\PlatformBundle\Entity\Criteria;
use GDSS\PlatformBundle\Entity\Problem;
use GDSS\PlatformBundle\Entity\Process;
use GDSS\PlatformBundle\Form\CriteriaType;
use GDSS\PlatformBundle\Form\ProblemType;
use GDSS\PlatformBundle\Form\ProcessType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class addController extends Controller
{
    /**
     * @param $id
     * @param $action
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addProblemAction($id, $action, Request $request){

        $problem = null;
        if($action == "create"){
            $problem = new Problem();
        }
        elseif ($action == "edit"){
            $problem = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Problem')->find($id);
        }


        $user = $this->getUser();

        //Creation du formulaire
        $form = $this->createForm(ProblemType::class, $problem);

        //Si la requete est en post
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            //On verifie si les donneées entrée sont bonnes
            if($form->isValid()){

                $datemin = $form["datestart"]->getData();
                $datemax = $form["dateend"]->getData();

                $diff = $datemax->diff($datemin);
                if($diff->d > 0){
                    if($diff->h == 0){
                        $hours = $diff->d * 24;
                    }
                    else{
                        $hours = ($diff->d * 24) + $diff->h;
                    }
                }
                else{
                    $hours = $diff->h;
                }
                $delay = array(
                    "month" => $diff->m,
                    "days" => $diff->d,
                    "hours" => $hours,
                    "minutes" => $diff->i,
                    "seconds" => $diff->s
                );
                if($delay["month"] == 0){
                    if($delay["days"] == 0){
                        if($delay["hours"] < 2){
                            $error = "La durée minimale de la reunion est de 2 heures";
                        }
                    }
                }

                $now1 = new \DateTime('now');

                if($datemin>$datemax OR $datemin==$datemax){
                    $erreur = "Les dates ne sont pas conformes ! ";
                    return $this->render('@GDSSPlatform/CreateProblem/addproblem.html.twig', array(
                        'form' => $form->createView(),
                        'erreur' => $erreur,
                        'action' => $action,
                        'id' => $id,
                    ));
                }
                elseif (isset($error)){
                    return $this->render('@GDSSPlatform/CreateProblem/addproblem.html.twig', array(
                        'form' => $form->createView(),
                        'erreur' => $error,
                        'action' => $action,
                        'id' => $id,
                    ));
                }

                else{
                    $em = $this->getDoctrine()->getManager();
                    if($action == "create"){
                        $problem->setUser($user);

                        $em->persist($problem);
                        $em->flush();

                        $id=$problem->getId();
                        return $this->redirectToRoute('add_criteria', array('id'=> $id, 'action' => "create"));
                    }
                    elseif ($action == "edit"){
                        $em->persist($problem);
                        $em->flush();

                        return $this->redirectToRoute('problem', array('id' => $id));
                    }


                }
            }
        }

        return $this->render('@GDSSPlatform/CreateProblem/addproblem.html.twig', array('form'=>$form->createView(), 'action' => $action, 'id' => $id));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addprocessusAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager();
        $process = new Process();
        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);
        if($this->getUser() != $problem->getUser()){
            return $this->redirectToRoute('problem_list');
        }

        $data = $this->container->get('getconstraintsthinklets')->nbreparticipduration($id);

        $process->setName($problem->getName());
        $process->setDescription($problem->getContext());
        $process->setParticipantMax($data["nbremax"]);
        $process->setParticipantMin($data["nbremin"]);

        $form = $this->createForm(ProcessType::class, $process);

            //Creation du formulaire

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

                //On verifie si les donneées entrée sont bonnes
            if ($form->isValid()) {


                if($process->getParticipantMax() == null){
                    $process->setParticipantMax($data["nbremax"]);
                }
                $process->setName($problem->getName());
                $process->setDescription($problem->getContext());
                $process->setParticipantMin($data["nbremin"]);
                $process->setProblem($problem);
                $repository->persist($process);
                $problem->setProcess($process);
                $repository->persist($problem);
                $repository->flush();
                return $this->redirectToRoute('problem', array(
                    'id'=>$id,
                ));
            }
        }


        return $this->render('@GDSSPlatform/CreateProblem/addprocess.html.twig', array('form'=>$form->createView(), 'action' => 'create', 'process' => $process ));
    }

    /**
     * @param $id
     * @param $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addCriteriaAction($id, $action){

        $user = $this->getUser();
        $critere = new Criteria();

        //Pour casté la chaine en entier
        $id = intval($id);

        //On cherche l'entité correspondant à l'id envoyé
        $repository = $this->getDoctrine()->getManager();
        $problem =  $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        /*
         * CREATOR TEST
         */
        if($user != $problem->getUser()){
            return $this->redirectToRoute('problem_list');
        }


        //Creation des deux formulaires
        $formCriteria = $this->createForm(CriteriaType::class, $critere);

        //On recupère le sujet correspondant aux contraintes et aux critères


        $criterialist = $repository->getRepository('GDSSPlatformBundle:Criteria')->findBy(array(
            'problem' => $problem,
        ));


        return $this->render('@GDSSPlatform/CreateProblem/add_criteria.html.twig', array(
            'formCriteria'=>$formCriteria->createView(),
            'id'=>$id,
            'criterialist' => $criterialist,
            'action' => $action,
        ));

    }


    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function addCriteriascrpitAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        if($request->isXmlHttpRequest()){
            $description = $_POST['description'];

            $critere = new Criteria();
            $critere->setDescription($description);
            $critere->setProblem($problem);

            $repository->persist($critere);
            $repository->flush();
        }

        return new Response();
    }


    /**
     * @param $id
     * @return Response
     */
    public function criterialistAction($id){
        $repository = $this->getDoctrine()->getManager();

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        $criterialist =$repository->getRepository('GDSSPlatformBundle:Criteria')->findBy(array(
            'problem' => $problem,
        ));

        return $this->render('@GDSSPlatform/CreateProblem/criteria_list.html.twig', array(
            'criterialist' => $criterialist,
        ));

    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function addconstraintAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        //$form = $this->createForm(ConstraintsType::class, $constraint);
        $constraints = new Constraints();

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('nbre', IntegerType::class, array(
                'label' => 'Nombre maximum de participant'
            ))
            ->add('Valider', SubmitType::class)
            ->getForm();

        if($request->isMethod('Post')){
            $form->handleRequest($request);

            if($form->isValid()){
                $number = $form['nbre']->getData();
                if($number <= 5){
                    $constraints->setThinklet('OnePage');
                    $constraints->setDescription('0');
                    $constraints->setProblem($problem);
                    $repository->persist($constraints);
                    $repository->flush();
                    return $this->redirectToRoute('add_process', array('id' => $problem->getId()));
                }
                else{
                    return $this->redirectToRoute('add_constaint2', array('action' => '6', 'id' => $problem->getId()));
                }
            }
        }

        return $this->render('@GDSSPlatform/CreateProblem/add_constraints.html.twig', array(
            'form' => $form->createView(),
            'id' => $problem->getId(),
        ));
    }


    public function addconstraints2Action($id, $action, Request $request){
        $repository = $this->getDoctrine()->getManager();

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        $constraint = new Constraints();
        $data = array();
        $form = null;
        if($action == '6'){
            $form = $this->createFormBuilder($data)
                ->add('description', ChoiceType::class, array(
                    'label' => 'Option',
                    'choices' => array(
                        "Décision prise à partir de zéro" => '1',
                        "Durée de chaque étape inférieur ou égale à 10" => '2',
                    )
                ))
                ->add('Suivant', SubmitType::class)->getForm();
        }
        elseif ($action == 'not definied'){
            $form = $this->createFormBuilder($data)
                ->add('description', ChoiceType::class, array(
                    'label' => 'Option',
                    'choices' => array(
                        'Contrainte sur le nombre de contribution' => array("-Nombre de contribution supérieure à 80" => "3"),
                        'Contrainte sur la base de décision :' => array('-Pas de contrainte sur un ordre à suivre' => '4', '-Contrainte sur un ordre spécifique à suivre ET Pas de contrainte sur le nombre maximum de partcipants' => '5'),
                        'Pas de contraintes sur le nombre de participants :' => array('-Il y a au moins deux sous problème' => '7', "-Contrainte sur la base de décision qui soit bien élaborée ET Contrainte sur l'état d'évaluation de la base" => '6' )

                    )
                ))
                ->add('Suivant', SubmitType::class)->getForm();
        }
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if ($form->isValid()){
                $number = $form["description"]->getData();
                if($number == '1'){
                    $constraint->setThinklet("FreeBrainstorming");
                }
                else if($number == '2'){
                    $constraint->setThinklet('OnePage');
                }
                else if ($number == '3'){
                    $constraint->setThinklet('ComparativeBraintorming');
                }
                else if ($number == '4'){
                    $constraint->setThinklet('LeafHopper');
                }
                else if($number == '5'){
                    $constraint->setThinklet('DealersChoice');
                }
                else if($number == '6'){
                    $constraint->setThinklet('Plus-Minus-Interesting');
                }
                else if ($number == '7'){
                    $constraint->setThinklet('BranchBuilder');
                }
                $constraint->setDescription($form['description']->getData());
                $constraint->setProblem($problem);
                $repository->persist($constraint);
                $repository->flush();
                return $this->redirectToRoute('add_process', array('id' => $problem->getId()));
            }
        }

        return $this->render('@GDSSPlatform/CreateProblem/add_constraints2.html.twig', array(
            'form' => $form->createView(),
            'id' => $problem->getId(),
        ));

    }


    public function addphasesAction($id, $action, $phasename){


        $data = $this->container->get('problemdata')->problemdata($id);
        $allmakers = $data["allmakers"];
        $problem = $data["problem"];
        $process = $data["process"];
        $fiveminutes = false;
        if($problem->getUser() != $this->getUser()){
            return $this->redirectToRoute('problem');
        }

        if(count($allmakers) < 3){
            return $this->redirectToRoute('problem', array('id' => $id, 'error' => "Il faut au moins trois décideurs pour la réunion de prise de décision !"));
        }


        $repository = $this->getDoctrine()->getManager();
        $constraint = $repository->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
            'problem' => $problem
        ));
        $processduration = $process->getdurationmax();

        if ($action ==  "create"){

            $phase = new Phase();
            if($phasename == "Gene"){
                $phase->setThinklet($constraint->getThinklet());
            }
            else if($phasename == "PreNego1"){
                $Gene = $data["Gene"];
                $nbrecontrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                    'phases' => $Gene
                ));
                $nbrecomment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
                    'phase' => $Gene,
                ));

                $tot = count($nbrecomment) + count($nbrecontrib);
                if($Gene->getThinklet() == "LeafHopper" OR $Gene->getThinklet() == "DealersChoice"){
                    $phase->setThinklet("BucketBriefing");
                    $nego = true;
                }
                else if ($tot >= 100 AND $tot <= 300 ){
                    $phase->setThinklet("BroomWagon");
                    $nego = true;
                }
                else if ($tot > 300){
                    $phase->setThinklet("Pin-The-Tail-On-The-Donkey");
                    $nego = true;
                }
                else{
                    $fiveminutes = true;
                }
            }
            else if($phasename == "PreNego2"){
                $fiveminutes = true;
                foreach ($allmakers as $maker) {
                    $maker->setSelection(0);
                    $repository->persist($maker);
                }
            }
            else if($phasename == "Nego1"){
                $data0 = $this->container->get('percentage')->choosethinkletnego1($problem);
                $phase->setThinklet($data0["thinklet"]);
                foreach ($allmakers as $maker) {
                    $maker->setSelection(0);
                    $repository->persist($maker);
                }
            }
            else if($phasename == "Nego2"){
                $data0 = $this->container->get('percentage')->choosethinkletnego2($problem);
                $phase->setThinklet($data0["thinklet"]);
                foreach ($allmakers as $maker) {
                    $maker->setSelection(0);
                    $repository->persist($maker);
                }
            }
            else{
                $phase->setThinklet("Not definied");
            }
            $phase->setProcess($process);
            if($constraint->getDescription() == "2"){
                $phase->setDurationMax(5);
                $phase->setDurationMin(10);
                $dureemax = 10;
            }
            else if ($fiveminutes == true){
                $phase->setDurationMax(3);
                $phase->setDurationMin(5);
                $dureemax = 5;
            }
            else{
                $phase->setDurationMax(30);
                $phase->setDurationMin(20);
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


            $phase->setName($phasename);

            $repository->persist($process);
            $repository->persist($phase);
            $repository->flush();

            return $this->redirectToRoute('problem', array('id' => $id));
        }
        else if ($action=="edit"){
            $phase = $repository->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
                'process' => $process,
                'name' =>$phasename
            ));

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

            return $this->redirectToRoute('problem', array('id' => $id));
        }
        else{
            return new Response();
        }
    }

}