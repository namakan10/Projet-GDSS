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

        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($id);

        if($problem->getUser() == $users){
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
     * @return \GDSS\PlatformBundle\Entity\DecisionMakers|null|object
     */
    public function decideursAccess($id, $users){
        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($id);

        $makers = $this->em->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'user' => $users,
            'process' => $problem->getProcess(),
        ));

        return $makers;
    }

}