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

class GenerationScriptController extends Controller
{
    /**
     * @param $id
     * @param Request $request
     */
    public function addpropoAction($id, Request $request){

        $user = $this->getUser();
        $repository = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()){

            $prop = $_POST["proposition"];

            if(isset($_POST['leaf'])){

                $user = $this->getUser();
                $data = $this->container->get('leafhopper')->data($id, $user);
                $subject = $data['subject'];
                $pseudo = $data['pseudo'];


                $Gene = new GenerationSubSubjectContribution();
                $Gene->setPseudo($pseudo);
                $Gene->setContrib($prop);
                $Gene->setSubsubject($subject);
                $Gene->setUser($this->getUser());

            }
            else{


                $data = $this->container->get('platform.sujectdata')->sujetdata($id);
                $phase = $data["Gene"];
                $sujet = $data["subject"];

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

        die();
        //Return 0;
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
     */
    public function commentreplyAction($id, Request $request){

        $repository = $this->getDoctrine()->getManager();


        $reply = $_POST['reply'];
        $reaction = $_POST['reaction'];

        if($reaction == 'CommentReply'){
            $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->find($id);
            $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($comment->getContribution());
        }
        else{
            $contrib = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->find($id);
        }


        $phase = $repository->getRepository('GDSSPlatformBundle:Phases')->find($contrib->getPhases());

        $process = $repository->getRepository('GDSSPlatformBundle:Processus')->find($phase->getProcessus());

        $sujet =$repository->getRepository('GDSSPlatformBundle:Sujet')->find($process->getSujet());

        $decideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findOneBy(array(
            'sujet' => $sujet,
            'user' => $this->getUser(),
        ));
        if($this->getUser() == $sujet->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
                $pseudo = $decideurs->getPseudodecideurs();
            }

        if($request->isXmlHttpRequest()){


            if($reaction == 'CommentReply'){
                $commentreply = new GenerationCommentReply();
                $commentreply->setReply($reply);
                $commentreply->setComment($comment);
                $commentreply->setPseudo($pseudo);
                $commentreply->setUser($this->getUser());

                $repository->persist($commentreply);

            }
            else{
                $contribreply = new GenerationComment();
                $contribreply->setUser($this->getUser());
                $contribreply->setContribution($contrib);
                $contribreply->setComment($reply);
                $contribreply->setPseudo($pseudo);
                $contribreply->setReaction($reaction);

                $repository->persist($contribreply);

            }
            $repository->flush();
        }

        die();
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
        $phase = $this->container->get('platform.sujectdata')->sujetdata($id);
        $phase = $phase["Gene"];


        $repository = $this->getDoctrine()->getManager();

        $chat = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
            'phases' => $phase,
        ));


        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findAll();
        $commentreply = $repository->getRepository('GDSSPhasesBundle:GenerationCommentReply')->findAll();

        return $this->render('GDSSPhasesBundle:phases_view/Generation_ThinkLet/BranchBuilder:scprit_branchbuilder.html.twig',array(
            'chat' => $chat,
            'id' => $id,
            'user' => $this->getUser(),
            'contribreply' => $comment,
            'commentreply' => $commentreply
        ));
    }

    public function thelobbyistscpritAction($id){
        $phase = $this->container->get('platform.sujectdata')->sujetdata($id);
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

}