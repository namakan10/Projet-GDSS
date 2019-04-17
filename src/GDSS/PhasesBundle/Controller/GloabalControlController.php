<?php

namespace GDSS\PhasesBundle\Controller;


use GDSS\PhasesBundle\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GloabalControlController extends Controller
{
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function chat_add_msg_Action($id){
        $msg = $_POST['msg'];
        $phase = $_POST['phase'];
        $pseudo = $_POST['pseudo'];
        $user = $this->getUser();

        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);

        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);

        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }
        $repository = $this->getDoctrine()->getManager();

        $data = $this->container->get('problemdata')->problemdata($id);

        $chat = new Chat();

        $chat->setMessage($msg);
        $chat->setPseudo($pseudo);
        if($phase == "Nego2"){
            $chat->setPhase($data["Nego2"]);
        }
        else if ($phase == "Nego1"){
            $chat->setPhase($data["Nego1"]);
        }
        else if ($phase == "Gene"){
            $chat->setPhase($data["Gene"]);
        }
        $chat->setUsers($user);

        $repository->persist($chat);
        $repository->flush();

        return new Response();
    }

    public function chat_list_msg_Action($id, $phase){

        $user = $this->getUser();

        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);

        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);

        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }
        $repository = $this->getDoctrine()->getManager();
        $data = $this->container->get('problemdata')->problemdata($id);
        if($phase == "Nego1"){
            $phase = $data["Nego1"];
        }
        if($phase == "Nego2"){
            $phase = $data["Nego2"];
        }
        else if($phase == "Gene"){
            $phase = $data["Gene"];
        }
        $chat = $repository->getRepository('GDSSPhasesBundle:Chat')->findBy(array(
            'phase' => $phase,
        ));

        return $this->render('@GDSSPhases/phases_view/chat.html.twig', array(
            'id' => $id,
            'chat' => $chat,
            'user' => $user,
        ));
    }

}