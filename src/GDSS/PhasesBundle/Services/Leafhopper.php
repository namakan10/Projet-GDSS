<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 12/09/2018
 * Time: 11:12
 */

namespace GDSS\PhasesBundle\Services;

use Doctrine\ORM\EntityManager;


class Leafhopper
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }

    public function data($id, $user){


        $subjectcontrib = $this->em->getRepository('GDSSPhasesBundle:GenerationSubSubjectContribution')->findBy(array(
            'subsubject' => $id
        ));

        $subproblem = $this->em->getRepository('GDSSPhasesBundle:GenerationSubSubject')->find($subjectcontrib[0]->getSubsubject());

        $phase = $this->em->getRepository('GDSSPhasesBundle:Phase')->find($subproblem->getPhases());

        $process = $this->em->getRepository('GDSSPlatformBundle:Process')->find($phase->getProcess());

        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($process->getProblem());

        $maker = $this->em->getRepository('GDSSPlatformBundle:DecisionMakers')->findOneBy(array(
            'process' => $process,
            'user' => $user
        ));

        $allmakers = $this->em->getRepository('GDSSPlatformBundle:DecisionMakers')->findBy(array(
            'process' => $process
        ));

        if($user == $problem->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $maker->getPseudoMaker();
        }

        return array(
            'subjectcontrib' => $subjectcontrib,
            'subproblem' => $subproblem,
            'pseudo' => $pseudo,
            'maker' => $maker,
            'problem' => $problem,
            'phase' => $phase,
            'process' => $process,
            'allmakers' => $allmakers,
            );
    }
}