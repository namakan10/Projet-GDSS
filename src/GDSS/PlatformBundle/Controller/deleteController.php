<?php
/**
 * Created by IntelliJ IDEA.
 * User: Namakan
 * Date: 27/01/2019
 * Time: 09:23
 */

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Decideurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class deleteController extends Controller
{
    public function deleteproblemAction($id){

        $repository = $this->getDoctrine()->getManager();

        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($id);

        $repository->remove($problem);

        $repository->flush();

        return $this->redirectToRoute('problem_list');

    }


    public function deletecriteriaAction($id){

        $repository = $this->getDoctrine()->getManager();

        $criteria = $repository->getRepository('GDSSPlatformBundle:Criteria')->find($id);

        $repository->remove($criteria);

        $repository->flush();

        return new Response();
    }

    public function deleteconstraintAction($id){

        $repository = $this->getDoctrine()->getManager();

        $constraints = $repository->getRepository('GDSSPlatformBundle:Constraints')->find($id);

        $repository->remove($constraints);

        $repository->flush();

        return new Response();
    }
}