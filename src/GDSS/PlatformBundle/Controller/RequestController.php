<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 29/05/2018
 * Time: 11:13
 */

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Decideurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class RequestController extends Controller
{
    public function invValidAction($thread, Request $request){
        $repository = $this->getDoctrine()->getManager();
        $em = $repository->getRepository('GDSSPlatformBundle:Sujet');
        $sujet = $em->findOneBy(array(
            'contexte' => $thread,
        ));

        $compdecideurs = $repository->getRepository('GDSSPlatformBundle:Decideurs')->findBy(array(
            'sujet' => $sujet
        ));

        $nbre = 1;
        foreach ($compdecideurs as $comp){
            $nbre++;
        }



        $decideurs = new Decideurs();
        $decideurs->setUser($this->getUser());
        $decideurs->setSujet($sujet);

        $phase  = $sujet->getProcessus();

        if($phase->getAnonyme()=='Non'){
            $decideurs->setPseudodecideurs($this->getUser()->getUsername());
        }
        else{
            $decideurs->setPseudodecideurs('Decideur '.$nbre);
        }


        $em = $repository->getRepository('GDSSPlatformBundle:Decideurs');
        $search = $em->findBy(array(
           'user' => $decideurs->getUser(),
           'sujet' => $decideurs->getSujet(),
        ));

        $id = $sujet->getId();


        if($search == null){
            $repository->persist($decideurs);
            $repository->flush();
            return $this->redirectToRoute('gdss_platform_sujet_vue' ,array(
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