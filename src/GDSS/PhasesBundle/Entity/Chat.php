<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chat
 *
 * @ORM\Table(name="chat")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\ChatRepository")
 */
class Chat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", inversedBy="chat")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase", inversedBy="chat")
     */
    private $phase;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Chat
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Chat
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set users
     *
     * @param \GDSS\PlatformBundle\Entity\User $users
     *
     * @return Chat
     */
    public function setUsers(\GDSS\PlatformBundle\Entity\User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \GDSS\PlatformBundle\Entity\User
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set phase
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phase
     *
     * @return Chat
     */
    public function setPhase(\GDSS\PhasesBundle\Entity\Phase $phase = null)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return \GDSS\PhasesBundle\Entity\Phase
     */
    public function getPhase()
    {
        return $this->phase;
    }
}
