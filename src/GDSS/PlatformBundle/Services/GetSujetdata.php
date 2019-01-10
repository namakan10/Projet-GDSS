<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 03/08/2018
 * Time: 13:58
 */

namespace GDSS\PlatformBundle\Services;

use Doctrine\ORM\EntityManager;


class GetSujetdata
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }

    /**
     * @param $id
     * @return array
     */
    public function sujetdata($id){

        $subjectView = $this->em->getRepository('GDSSPlatformBundle:Sujet')->find($id);

        $contrainte = $this->em->getRepository('GDSSPlatformBundle:Contraintes')->findBy(array(
            'sujet' => $subjectView
        ));

        $critere = $this->em->getRepository('GDSSPlatformBundle:Criteres')->findBy(array(
            'sujet' => $subjectView
        ));

        $process = $subjectView->getProcessus();

        $phase = $this->em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'processus'=> $process,
        ));

        $Comp = $this->em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'nom' => 'Phase de Comprehension Collective du problème',
            'processus' => $process
        ));

        $Gene = $this->em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'nom' => 'Phase de Generations des solutions',
            'processus' => $process
        ));

        $Nego = $this->em->getRepository('GDSSPlatformBundle:Phases')->findOneBy(array(
            'nom' => 'Phase de Negociations de confrontations des points de vue',
            'processus' => $process
        ));

        return array("subject" => $subjectView, "process" => $process, "critere" => $critere, "contrainte" => $contrainte, "phase" => $phase, "Comp" => $Comp, "Gene" => $Gene, "Nego" => $Nego);

    }

}