<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 03/08/2018
 * Time: 10:09
 */

namespace GDSS\PlatformBundle\Services;


class Progress
{
    /**
     * @param object $obj
     * @return int
     */
    public function progression($obj){


        $now = new \DateTime("now");
        $datestrat = strtotime($obj->getDateDebut()->format("Y-m-d H:i:s"));
        $dateend = strtotime($obj->getDateFin()->format("Y-m-d H:i:s"));
        $nowint =$dateend - strtotime($now->format("Y-m-d H:i:s"));
        $diff = $dateend-$datestrat;

        if(strtotime($now->format("Y-m-d H:i:s"))<$datestrat){
            $progress=0;
        }
        else if($nowint<0){
            $progress = 100;
        }
        else{
            $progress =100 - intval(($nowint*100)/$diff);
        }

        return $progress;
    }
}