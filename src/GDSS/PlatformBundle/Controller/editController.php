<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 03/10/2018
 * Time: 13:24
 */

namespace GDSS\PlatformBundle\Controller;


use GDSS\PlatformBundle\Entity\Contraintes;
use GDSS\PlatformBundle\Entity\Criteres;
use GDSS\PlatformBundle\Entity\Phases;
use GDSS\PlatformBundle\Entity\Processus;
use GDSS\PlatformBundle\Entity\Repertoire;
use GDSS\PlatformBundle\Entity\Sujet;
use GDSS\PlatformBundle\Form\ContraintesType;
use GDSS\PlatformBundle\Form\CriteresType;
use GDSS\PlatformBundle\Form\ProcessusType;
use GDSS\PlatformBundle\Form\SujetType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class editController extends Controller
{
    public function editSujetAction(Request $request, $id){

        $sujet = $this->getDoctrine()->getManager()->getRepository('GDSSPlatformBundle:Sujet')->find($id);
        $user = $this->getUser();

        //Creation du formulaire
        $form = $this->createForm(SujetType::class, $sujet);

        //Si la requete est en post
        if($request->isMethod('POST')){
            $form->handleRequest($request);

            //On verifie si les donneées entrée sont bonnes
            if($form->isValid()){

                $datemin = $form["DateDebut"]->getData();
                $datemax = $form["DateFin"]->getData();

                $now1 = new \DateTime('now');

                if($datemin<$now1 OR $datemin>$datemax OR $datemin==$datemax){
                    $erreur = "Les dates ne sont pas conformes ! ";
                    return $this->render('@GDSSPlatform/Sujets_Basic_View/editSujet.html.twig', array(
                        'form' => $form->createView(),
                        'erreur' => $erreur
                    ));
                }

                else{
                    $sujet->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($sujet);
                    $em->flush();
                    $id=$sujet->getId();
                    return $this->redirectToRoute('gdss_platform_sujet_vue', array('id'=> $id));
                }
            }
        }

        return $this->render('@GDSSPlatform/Sujets_Basic_View/editSujet.html.twig', array('form'=>$form->createView()));
    }
}