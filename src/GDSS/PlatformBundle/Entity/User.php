<?php


namespace GDSS\PlatformBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ParticipantInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Date/Time of the last activity
     *
     * @var \Datetime
     * @ORM\Column(name="last_activity_at", type="datetime", nullable=true)
     */
    protected $lastActivityAt;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\DecisionMakers", mappedBy="user", cascade={"remove"})
     */
    private $makers;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Chat", mappedBy="users", cascade={"remove"})
     */
    private $chat;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Reaction", mappedBy="user", cascade={"remove"})
     */
    private $reac;



    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @param \Datetime $lastActivityAt
     */
    public function setLastActivityAt($lastActivityAt)
    {
        $this->lastActivityAt = $lastActivityAt;
    }

    /**
     * @return \Datetime
     */
    public function getLastActivityAt()
    {
        return $this->lastActivityAt;
    }

    /**
     * @return Bool Whether the user is active or not
     */
    public function isActiveNow()
    {
        // Delay during wich the user will be considered as still active
        $delay = new \DateTime('5 minutes ago');

        return ( $this->getLastActivityAt() > $delay );
    }


    /**
     * Add reac
     *
     * @param \GDSS\PhasesBundle\Entity\Reaction $reac
     *
     * @return User
     */
    public function addReac(\GDSS\PhasesBundle\Entity\Reaction $reac)
    {
        $this->reac[] = $reac;

        return $this;
    }

    /**
     * Remove reac
     *
     * @param \GDSS\PhasesBundle\Entity\Reaction $reac
     */
    public function removeReac(\GDSS\PhasesBundle\Entity\Reaction $reac)
    {
        $this->reac->removeElement($reac);
    }

    /**
     * Get reac
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReac()
    {
        return $this->reac;
    }

    /**
     * Add maker
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $maker
     *
     * @return User
     */
    public function addMaker(\GDSS\PlatformBundle\Entity\DecisionMakers $maker)
    {
        $this->makers[] = $maker;

        return $this;
    }

    /**
     * Remove maker
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $maker
     */
    public function removeMaker(\GDSS\PlatformBundle\Entity\DecisionMakers $maker)
    {
        $this->makers->removeElement($maker);
    }

    /**
     * Get makers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMakers()
    {
        return $this->makers;
    }

    /**
     * Add chat
     *
     * @param \GDSS\PhasesBundle\Entity\Chat $chat
     *
     * @return User
     */
    public function addChat(\GDSS\PhasesBundle\Entity\Chat $chat)
    {
        $this->chat[] = $chat;

        return $this;
    }

    /**
     * Remove chat
     *
     * @param \GDSS\PhasesBundle\Entity\Chat $chat
     */
    public function removeChat(\GDSS\PhasesBundle\Entity\Chat $chat)
    {
        $this->chat->removeElement($chat);
    }

    /**
     * Get chat
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChat()
    {
        return $this->chat;
    }
}
