<?php
/**
 * Created by IntelliJ IDEA.
 * User: Namakan
 * Date: 20/02/2019
 * Time: 19:42
 */

namespace GDSS\PlatformBundle\Services;

use Doctrine\ORM\EntityManager;

class GetConstraintsThinklets
{
    private $em = null;

    public function __construct(EntityManager $es) {
        $this->em = $es;
    }


    /**
     * @param $id
     * @return array
     */
    public function nbreparticipduration($id){

        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($id);
        $constraint = $this->em->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
            'problem' => $problem
        ));

        $data = array();
        $descrpit = array('3', '4', '6', '7');
        if(in_array($constraint->getdescription(),$descrpit )){
            $data = array('nbremin' => 3, 'nbremax' => 20);
        }
        else if($constraint->getdescription() == '1'){
            $data = array('nbremin' => 6, 'nbremax' => 20);
        }
        else if($constraint->getdescription() == '2'){
            $data = array('nbremin' => 6, 'nbremax' => 20);
        }
        else if($constraint->getdescription() == '5'){
            $data = array('nbremin' => 3, 'nbremax' => null);
        }
        else if($constraint->getDescription() == '0'){
            $data = array('nbremin' => 3, 'nbremax' => 5);
        }
        return $data;
    }


    /**
     * @param $id
     * @return array
     */
    public function constraintslist($id){
        $problem = $this->em->getRepository('GDSSPlatformBundle:Problem')->find($id);
        $constraint = $this->em->getRepository('GDSSPlatformBundle:Constraints')->findOneBy(array(
            'problem' => $problem
        ));
        $data = array();
        if($constraint->getDescription() == '1'){
            $data = array("Le nombre minimum de participant est 6", "La décision est prise à partir de zéro");
        }
        else if($constraint->getDescription() == '2'){
            $data = array("Le nombre minimum de participant est 6", "La durée de chaque étape est inférieure ou égale à 10");
        }
        else if($constraint->getDescription() == '3'){
            $data = array("Le nombre de contribution doit être supérieure à 80");
        }
        else if($constraint->getDescription() == '4'){
            $data = array("Contrainte sur la base de décision", "Pas de contrainte sur un ordre à suivre");
        }
        else if($constraint->getDescription() == '5'){
            $data = array("Contrainte sur la base de décision", "Contrainte sur un ordre à suivre", "Pas de contrainte sur le nombre maximum de participants");
        }
        else if($constraint->getDescription() == '7'){
            $data = array("Pas de contrainte sur le nombre de participants", "Il y a au moins deux sous problème");
        }
        else if($constraint->getDescription() == '6'){
            $data = array("Pas de contrainte sur le nombre de participants", "Contrainte sur la base de décision qui soit bien élaborée", "Contrainte sur l'état d'evaluation de la base");
        }
        else if($constraint->getDescription() == '0'){
            $data = array("Nombre de participant maximum inférieur ou égale à 5");
        }

        return $data;
    }

}