<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 29/05/2018
 * Time: 11:13
 */

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Decideurs;
use GDSS\PlatformBundle\Entity\DecisionMakers;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class RequestController extends Controller
{

    public function invValidAction($thread, Request $request, $user){

        $repository = $this->getDoctrine()->getManager();
        $user = $repository->getRepository('GDSSPlatformBundle:User')->findOneBy(array(
            'username' => $user
        ));
        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->findOneBy(array(
            'context' => $thread,
            'user' => $user,
        ));
        $process = $repository->getRepository('GDSSPlatformBundle:Process')->findOneBy(array(
            'problem'=>$problem
        ));

        $compmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $process
        ));

        $nbre = 1;
        foreach ($compmakers as $comp){
            $nbre++;
        }



        $maker = new DecisionMakers();
        $maker->setUser($this->getUser());
        $maker->setProcess($process);


        if($process->getAnonymous() == 0){
            $maker->setPseudoMaker($this->getUser()->getUsername());
        }
        else{
            $maker->setPseudoMaker('Decideur '.$nbre);
        }


        $search = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
           'user' => $maker->getUser(),
           'process' => $maker->getProcess(),
        ));

        $id = $problem->getId();


        if($search == null){
            $repository->persist($maker);
            $repository->flush();
            return $this->redirectToRoute('problem' ,array(
                'id' => $id
            ));
        }

        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'Vous participez déjà à ce sujet');

        return $this->redirectToRoute('gdss_platform_inbox', array(
            'id' => $id,
        ));
    }


}