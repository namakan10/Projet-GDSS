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

        $subject = $this->em->getRepository('GDSSPhasesBundle:GenerationSubSubject')->find($subjectcontrib[0]->getSubsubject());

        $phase = $this->em->getRepository('GDSSPlatformBundle:Phases')->find($subject->getPhases());

        $process = $this->em->getRepository('GDSSPlatformBundle:Processus')->find($phase->getProcessus());

        $sujet = $this->em->getRepository('GDSSPlatformBundle:Sujet')->find($process->getSujet());

        $decideurs = $this->em->getRepository('GDSSPlatformBundle:Decideurs')->findOneBy(array(
            'sujet' => $sujet,
            'user' => $user
        ));

        if($user == $sujet->getUser()){
            $pseudo = 'Facilitateur';
        }
        else{
            $pseudo = $decideurs->getPseudodecideurs();
        }

        return array('subjectcontrib' => $subjectcontrib, 'subject' => $subject, 'pseudo' => $pseudo, 'decideurs' => $decideurs, 'sujet' => $sujet, 'phase' => $phase);
    }
}