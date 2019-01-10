<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reaction
 *
 * @ORM\Table(name="reaction")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\ReactionRepository")
 */
class Reaction
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
     * @ORM\Column(name="reaction", type="string", length=255)
     */
    private $reaction;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\GenerationContribution", inversedBy="reac")
     */
    private $contrib;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", inversedBy="reac")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



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
     * Set reaction
     *
     * @param string $reaction
     *
     * @return Reaction
     */
    public function setReaction($reaction)
    {
        $this->reaction = $reaction;

        return $this;
    }

    /**
     * Get reaction
     *
     * @return string
     */
    public function getReaction()
    {
        return $this->reaction;
    }

    /**
     * Set contrib
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationContribution $contrib
     *
     * @return Reaction
     */
    public function setContrib(\GDSS\PhasesBundle\Entity\GenerationContribution $contrib = null)
    {
        $this->contrib = $contrib;

        return $this;
    }

    /**
     * Get contrib
     *
     * @return \GDSS\PhasesBundle\Entity\GenerationContribution
     */
    public function getContrib()
    {
        return $this->contrib;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Reaction
     */
    public function setUser(\GDSS\PlatformBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \GDSS\PlatformBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
