<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 03/08/2018
 * Time: 10:30
 */

namespace GDSS\PlatformBundle\Services;


use Doctrine\ORM\EntityManager;



class CheckAccess
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }

    /**
     * @param $id
     * @param $users
     * @return bool
     */
    public function adminAccess($id, $users){

        $subjectView = $this->em->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        if($subjectView->getUser() == $users){
            $admin = true;
        }
        else{
            $admin = false;
        }

        return $admin;
    }

    /**
     * @param $id
     * @param $users
     * @return \GDSS\PlatformBundle\Entity\Decideurs|null|object
     */
    public function decideursAccess($id, $users){
        $subjectView = $this->em->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        $decideurs = $this->em->getRepository('GDSSPlatformBundle:Decideurs')->findOneBy(array(
            'user' => $users,
            'sujet' => $subjectView,
        ));

        return $decideurs;
    }

}