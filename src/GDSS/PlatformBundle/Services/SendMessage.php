<?php
/**
 * Created by PhpStorm.
 * User: Ghost
 * Date: 04/08/2018
 * Time: 10:27
 */

namespace GDSS\PlatformBundle\Services;

use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Sender;
use FOS\MessageBundle\Composer;


class SendMessage
{
    private $em = null;
    private $composer = null;
    private $send = null;

    public function __construct(EntityManager $es, Composer\Composer $composer, Sender\Sender $send) {
        $this->em = $es;
        $this->send = $send;
        $this->composer = $composer;
    }

    /**
     * @param $form
     * @param $problem
     * @param $user
     * @return string
     */
    public function sendInvitation($form, $problem, $user){
        $guest = $form["Contact"]->getData();
        $em = $this->em->getRepository('GDSSPlatformBundle:User');


        foreach ($guest as $gs){
            $recipient = $em->findOneBy(array(
                'id' => $gs,
            ));
            $sender = $user;
            $threadBuilder = $this->composer->newThread();
            $threadBuilder
                ->addRecipient($recipient)
                ->setSubject($problem->getName())
                ->setSender($sender)
                ->setBody($problem->getContext());
            $this ->send->send($threadBuilder->getMessage());

        }

        return "Invitation(s) envoyée(s) !";
    }
}