<?php
/**
 * Created by PhpStorm.
 * User: Namakan
 * Date: 10/30/2018
 * Time: 10:34 AM
 */

namespace GDSS\PhasesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NegociationScrpitController extends Controller
{
    public function setexpertAction($id, Request $request){
        $repository = $this->getDoctrine()->getManager();

        $decideur = $repository->getRepository('GDSSPlatformBundle:Decideurs')->find($id);

        $returid = $_POST['returnid'];

        $data = $this->container->get('platform.sujectdata')->sujetdata($returid);

        if($request->isXmlHttpRequest()){


            $decideur->setExpert(1);

            $data["Nego"]->setexpert('decideur');

            $repository->persist($decideur);

            $repository->persist($data["Nego"]);
            $repository->flush();

            return $this->redirectToRoute('ExpertChoice', array('id' => $returid));
        }

        return 0;
    }
}