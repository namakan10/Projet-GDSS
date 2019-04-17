<?php
/**
 * Created by IntelliJ IDEA.
 * User: Namakan
 * Date: 22/02/2019
 * Time: 20:34
 */

namespace GDSS\PlatformBundle\Services;

use Doctrine\ORM\EntityManager;

class problemdata
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }

    /**
     * @param $id
     * @return array
     */
    public function problemdata($id){

        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($id);

        $constraints = $this->em->getRepository('GDSSPlatformBundle:Constraints')->findBy(array(
            'problem' => $problem
        ));

        $criteria = $this->em->getRepository('GDSSPlatformBundle:Criteria')->findBy(array(
            'problem' => $problem
        ));

        $process = $this->em->getRepository('GDSSPlatformBundle:Process')->findOneBy(array(
            'problem' => $problem
        ));

        $phase = $this->em->getRepository('GDSSPhasesBundle:Phase')->findBy(array(
            'process'=> $process,
        ));

        $Comp = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Comp',
            'process' => $process
        ));

        $Gene = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Gene',
            'process' => $process
        ));

        $PreNego1 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'PreNego1',
            'process' => $process
        ));

        $Nego1 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Nego1',
            'process' => $process
        ));
        $PreNego2 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'PreNego2',
            'process' => $process
        ));

        $Nego2 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Nego2',
            'process' => $process
        ));
        $PreNego3 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'PreNego3',
            'process' => $process
        ));

        $Nego3 = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Nego3',
            'process' => $process
        ));

        $Decision = $this->em->getRepository('GDSSPhasesBundle:Phase')->findOneBy(array(
            'name' => 'Decision',
            'process' => $process
        ));

        $allmakers = $this->em->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $process
        ));

        return array(
            "problem" => $problem,
            "process" => $process,
            "criteria" => $criteria,
            "constraints" => $constraints,
            "phase" => $phase,
            "Comp" => $Comp,
            "Gene" => $Gene,
            "PreNego1" => $PreNego1,
            "Nego1" => $Nego1,
            "PreNego2" => $PreNego2,
            "Nego2" => $Nego2,
            "PreNego3" => $PreNego3,
            "Nego3" => $Nego3,
            'Decision' => $Decision,
            "allmakers" => $allmakers,
            );

    }
}