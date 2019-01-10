<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 16/08/2018
 * Time: 17:02
 */

namespace GDSS\PhasesBundle\Services;


class Timer
{
    /**
     * @param $obj
     * @return array
     */
    public function getime($obj){

        $now = new \DateTime('now');
        $dateend = $obj->getDateFin();
        $diff = $now->diff($dateend);


        //var_dump($diff);
        //die();
        if($diff->d > 0){
            if($diff->h == 0){
                $hours = $diff->d * 24;
            }
            else{
                $hours = ($diff->d * 24) + $diff->h;
            }
        }
        else{
            $hours = $diff->h;
        }
        $time = array(
            "days" => $diff->d,
            "hours" => $hours,
            "minutes" => $diff->i,
            "seconds" => $diff->s
        );

        return $time;
    }
}