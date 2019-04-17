<?php


namespace GDSS\PhasesBundle\Controller;


use GDSS\PhasesBundle\Entity\GenerationComment;
use GDSS\PhasesBundle\Entity\GenerationCommentReply;
use GDSS\PhasesBundle\Entity\GenerationContribution;
use GDSS\PhasesBundle\Entity\GenerationSubSubject;
use GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution;
use GDSS\PhasesBundle\Entity\Reaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenerationScriptController extends Controller
{
    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function addpropoAction($id, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()){

            $prop = $_POST["proposition"];

            if(isset($_POST['leaf'])){

                $user = $this->getUser();
                $data = $this->container->get('leafhopper')->data($id, $user);
                $subproblem = $data['subproblem'];
                $pseudo = $data['pseudo'];


                $Gene = new GenerationSubSubjectContribution();
                $Gene->setPseudo($pseudo);
                $Gene->setContrib($prop);
                $Gene->setSubsubject($subproblem);
                $Gene->setUser($this->getUser());

            }
            else{


                $data = $this->container->get('problemdata')->problemdata($id);
                $phase = $data["Gene"];
                $problem = $data["problem"];

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
                $Gene->setPseudo($pseudo);
                $Gene->setUser($user);
                $Gene->setPhases($phase);
                $Gene->setContribution($prop);
                $Gene->setNumero($nbre);
                if(isset($_POST['status'])){
                    $Gene->setStatus($_POST['status']);
                }
                else{
                    $Gene->setStatus("Posté");
                }
            }




            $repository->persist($Gene);
            $repository->flush();
        }

        return new Response();
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function reactionAction(Request $request, $id){

        $repository = $this->getDoctrine()->getManager();

        $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($id);

        if($request->isXmlHttpRequest()){
            $reac = $_POST['reac'];

            $check = $repository->getRepository('GDSSPhasesBundle:Reaction')->findOneBy(array(
                'user' =>$this->getUser(),
                'contrib' => $contrib,
            ));

            if($check != null){
                $check->setReaction($reac);
                $repository->persist($check);
                $repository->flush();
            }
            else{
                $reaction = new Reaction();

                $reaction->setUser($this->getUser());
                $reaction->setContrib($contrib);
                $reaction->setReaction($reac);
                $repository->persist($reaction);
                $repository->flush();
            }

        }

        die();
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function commentreplyAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();


        $reply = $_POST['reply'];
        $reaction = $_POST['reaction'];
        $comment = null;

        if($reaction == 'CommentReply'){
            $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->find($id);
            $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($comment->getContribution());
        }
        else{
            $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($id);
        }


        $phase = $repository->getRepository('GDSSPhasesBundle:Phase')->find($contrib->getPhases());

        $process = $repository->getRepository('GDSSPlatformBundle:Process')->find($phase->getProcess());

        $problem =$repository->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $process,
            'user' => $this->getUser(),
        ));
        if($this->getUser() == $problem->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
                $pseudo = $maker->getPseudoMaker();
            }

        if($request->isXmlHttpRequest()){


            if($reaction == 'CommentReply'){
                $commentreply = new GenerationCommentReply();
                $commentreply->setReply($reply);
                $commentreply->setComment($comment);
                $commentreply->setPseudo($pseudo);
                $commentreply->setUser($this->getUser());
                $commentreply->setPhase($phase);

                $repository->persist($commentreply);

            }
            else{
                $contribreply = new GenerationComment();
                $contribreply->setUser($this->getUser());
                $contribreply->setContribution($contrib);
                $contribreply->setComment($reply);
                $contribreply->setPseudo($pseudo);
                $contribreply->setReaction($reaction);
                $contribreply->setPhase($phase);

                $repository->persist($contribreply);

            }
            $repository->flush();
        }

        return new Response();
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentlistAction($id){
        $repository = $this->getDoctrine()->getManager();

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $id,
        ));


        return $this->render('@GDSSPhases/phases_view/Generation_ThinkLet/Brainstorming/commentlist.html.twig', array(
            'comment' => $comment,
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function branchbuilderscrpitAction($id){
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
        }


        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();
        $commentreply = $repository->getRepository('GDSSPhasesBundle:GenerationCommentReply')->findAll();

        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/BranchBuilder:scprit_branchbuilder.html.twig',array(
            'chat' => $chat,
            'id' => $id,
            'user' => $this->getUser(),
            'contribreply' => $comment,
            'commentreply' => $commentreply,
            'definied' => $definedcontrib,
        ));
    }

    /**
     * @param $id
     * @return Response
     */
    public function thelobbyistscpritAction($id){
        $phase = $this->container->get('problemdata')->problemdata($id);
        $phase = $phase["Gene"];


        $repository = $this->getDoctrine()->getManager();

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));


        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();


        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/The-Lobblyist:scrpit_thelobbyist.html.twig',array(
            'chat' => $chat,
            'id' => $id,
            'user' => $this->getUser(),
            'contribreply' => $comment,

        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function checkthelobbyistAction($id){
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

        $phase = $data["Gene"];

        if($phase->getThinklet() == "TheLobbyist"){
            return $this->redirectToRoute('thelobbyist', array('id' => $id));
        }

        return new Response();
    }


    public function end_delete_sub_problemAction($action, $id, $thinklet, $backid){

        $user = $this->getUser();
        /*
         * CHECK ACCESS
         */
        $admin = $this->container->get('platform.checkaccess')->adminAccess($backid, $user);
        if($admin == false){
            return $this->redirectToRoute('problem_list');
        }


        $repository = $this->getDoctrine()->getManager();

        if($action == "end"){
            $subproblem = $repository->getRepository('GDSSPhasesBundle:GenerationSubSubject')->find($id);
            $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
                'subproblem' => $subproblem
            ));

            $group = count($group);

            if($thinklet == 'dealerschoice'){
                if($group < 2){
                    return $this->redirectToRoute('dealerschoice', array(
                        'id' => $backid,
                        'error' => "Vous devez affecter au moins deux décideurs dans le groupe de discussion : ".$subproblem->getName()." !",
                    ));
                }
                else{
                    $subproblem->setAllow(1);
                    $repository->persist($subproblem);
                    $repository->flush();
                    return $this->redirectToRoute('dealerschoice', array(
                        'id' => $backid,
                    ));
                }
            }
        }
        return new Response();
    }

}