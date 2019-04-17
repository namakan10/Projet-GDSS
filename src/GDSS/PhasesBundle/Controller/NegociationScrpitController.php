<?php
/**
 * Created by PhpStorm.
 * User: Namakan
 * Date: 10/30/2018
 * Time: 10:34 AM
 */

namespace GDSS\PhasesBundle\Controller;


use Symfony\Component\BrowserKit\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NegociationScrpitController extends Controller
{
    /**
     * @param $id
     * @param $backid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setexpertAction($id, $backid){
        $repository = $this->getDoctrine()->getManager();


        $problem = $repository->getRepository('GDSSPlatformBundle:Problem')->find($backid);

        if($problem->getUser() != $this->getUser()){
            return $this->redirectToRoute('problem_list');
        }

        $maker = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->find($id);

        $maker->setExpert(1);
        $repository->persist($maker);

        $allmakers = $repository->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $problem->getProcess(),
        ));

        $phase = $repository->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'process' => $problem->getProcess(),
            'name' => 'Nego1'
        ));

        $phase->setExpert('definied');
        $repository->persist($phase);

        foreach ($allmakers as $mak){
            $mak->setSelection(0);
            $repository->persist($mak);
        }

        $repository->flush();

        return $this->redirectToRoute('ExpertChoice', array('id' => $backid));

    }


    /**
     * @param $action
     * @param $id
     * @param $thinklet
     * @param $backid
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function end_delete_categorieAction($action, $id, $thinklet, $backid){

        if($action == "end"){
            $repository = $this->getDoctrine()->getManager();
            $categorie = $repository->getRepository('GDSSPhasesBundle:NegociationCategories')->find($id);
            $find = 0;
            $phaseNego = $repository->getRepository('GDSSPhasesBundle:Phase')->find($categorie->getPhase());
            $phaseGene = $repository->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
                'process' => $phaseNego->getProcess(),
                'name' => "Gene"
            ));
            $contribution = $repository->getRepository('GDSSPhasesBundle:GenerationContribution')->findBy(array(
                'phases' => $phaseGene,
            ));

            foreach ($contribution as $contrib){
                if($contrib->getCategorie() == $categorie->getName()){
                    $find++;
                }
            }
            if($thinklet == "oneup"){
                if($find == 0){
                    return $this->redirectToRoute('OneUp', array('id' => $backid, 'error' => 'Catégorie vide !'));
                }
                else{
                    $categorie->setAllow(1);
                    $repository->persist($categorie);
                    $repository->flush();
                    return $this->redirectToRoute('OneUp', array('id' => $backid));
                }
            }
            else if($thinklet == "PIN"){
                if($find == 0){
                    return $this->redirectToRoute('PinTheTailOntheDonkey', array('id' => $backid, 'error' => 'Catégorie vide !'));
                }
                else{
                    $categorie->setAllow(1);
                    $repository->persist($categorie);
                    $repository->flush();
                    return $this->redirectToRoute('PinTheTailOntheDonkey', array('id' => $backid));
                }
            }
            else if($thinklet == "bucketbriefing"){
                if($find == 0){
                    return $this->redirectToRoute('BucketBriefing', array('id' => $backid, 'error' => 'Catégorie vide !'));
                }
                else{
                    $group = $repository->getRepository('GDSSPhasesBundle:MakersGroup')->findBy(array(
                        'categorie' => $categorie
                    ));
                    $group = count($group);
                    if($group < 3 ){
                        return $this->redirectToRoute('BucketBriefing', array('id' => $backid, 'error' => 'Vous devez affécter au moins deux décideurs dans la catégorie "'.$categorie->getName().'" !'));
                    }
                    else{
                        $categorie->setAllow(1);
                        $repository->persist($categorie);
                        $repository->flush();
                        return $this->redirectToRoute('BucketBriefing', array('id' => $backid));
                    }

                }
            }
        }

        return new Response();


    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function fortknoxlistAction($id){

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
            'phase' => $data['Gene'],
        ));

        $progress = $this->container->get('platform.progress')->progression($data["Nego1"]);


        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/FastFocus:public_list_fort_knox.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'progress' => $progress,
            'contributionselect' => $contributionselect,
            'maker' => $maker,
        ));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pinratinglistAction($id){
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
        ), array(
            'liked' => 'DESC'
        ));

        $comment = $repository->getRepository('GDSSPhasesBundle:GenerationComment')->findBy(array(
            'contribution' => $contribution
        ));

        return $this->render('GDSSPhasesBundle:phases_view/Negociation_ThinkLet/Pin-the-Tail-on-the-Donkey:Pin_rating_list.html.twig', array(
            'id' => $id,
            'admin' => $admin,
            'contribution' => $contribution,
            'comment' => $comment,
            'maker' => $maker,
        ));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function categorylistAction($id){
        $user = $this->getUser();
        $maker=$this->container->get('platform.checkaccess')->decideursAccess($id, $user);
        $admin = $this->container->get('platform.checkaccess')->adminAccess($id, $user);
        if($admin == false AND $maker == null){
            return $this->redirectToRoute('problem_list');
        }

        $data = $this->container->get('problemdata')->problemdata($id);

        $category = $this->getDoctrine()
            ->getManager()
            ->getRepository('GDSSPhasesBundle:NegociationCategories')
            ->findBy(array(
                'phase' => $data["Nego2"]
            ));

        return $this->render('@GDSSPhases/phases_view/Negociation_ThinkLet/ThemeSeeker/category_list.html.twig', array(
            'category' => $category
        ));
    }

    public function contribution_one_by_oneAction($id){

    }

}