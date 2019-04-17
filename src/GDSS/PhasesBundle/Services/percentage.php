<?php
/**
 * Created by IntelliJ IDEA.
 * User: Namakan
 * Date: 11/03/2019
 * Time: 19:20
 */

namespace GDSS\PhasesBundle\Services;
use Doctrine\ORM\EntityManager;

class percentage
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }


    public function choosethinkletnego1($problem){
        $vote = $this->em->getRepository('GDSSPhasesBundle:NegociationFormVote')->findBy(array(
            'problem' => $problem,
            'phase' => '1'
        ));
        $nbrevote = count($vote);
        $formulation = 0;
        $expert = 0;
        $ambiguity = 0;
        $revelant_list = 0;
        $Thinklet = null;
        foreach ($vote as $vt){
            if($vt->getFormulation() == 1){
                $formulation += 1;
            }
            if($vt->getExpert() == 1){
                $expert += 1;
            }
            if($vt->getAmbiguity() == 1){
                $ambiguity += 1;
            }
            if($vt->getRevelantlist() == 1){
                $revelant_list += 1;
            }
        }

        $formulation = ($formulation*100)/$nbrevote;
        $expert = ($expert*100)/$nbrevote;
        $ambiguity = ($ambiguity*100)/$nbrevote;
        $revelant_list = ($revelant_list*100)/$nbrevote;

        if($formulation <100){
            $Thinklet = "FastFocus";
        }
        else if($expert > 50){
            $Thinklet = "ExpertChoice";
        }
        else if($ambiguity > 50){
            $Thinklet = "Concentration";
        }
        else if($revelant_list > 50){
            $Thinklet = "OneUp";
        }
        else{
            $Thinklet = "GoldMiner";
        }

        return array(
            "formulation" => $formulation,
            "expert" => $expert,
            "ambiguity" => $ambiguity,
            "revelant_list" => $revelant_list,
            "thinklet" => $Thinklet,
        );
    }

    public function choosethinkletnego2($problem){

        $category = $this->em->getRepository('GDSSPhasesBundle:NegociationFormVote')->findBy(array(
            'problem' => $problem,
            'phase' => '2',
            'category' => 1,
        ));
        $categorizer = $this->em->getRepository('GDSSPhasesBundle:NegociationFormVote')->findBy(array(
            'problem' => $problem,
            'phase' => '2',
            'categorizer' => 1,
        ));
        $thinklet = null;
        $category = count($category);
        $categorizer = count($categorizer);

        if($categorizer < $category){
            $thinklet = "ThemeSeeker";
        }
        else if ($categorizer > $category){
            $thinklet = "Evolution";
        }
        else{
            $thinklet = array("ThemeSeeker", "Evolution");
            $rand_key = array_rand($thinklet);
            $thinklet = $thinklet[$rand_key];
        }

        return array(
            'thinklet' => $thinklet
        );

    }
}