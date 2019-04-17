<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Process
 *
 * @ORM\Table(name="process")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\ProcessRepository")
 */
class Process
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
     * @ORM\OneToOne(targetEntity="GDSS\PlatformBundle\Entity\Problem", inversedBy="process")
     * @ORM\JoinColumn(nullable=true, unique=true, onDelete="SET NULL")
     */
    private $problem;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\DecisionMakers", mappedBy="process", cascade={"remove"})
     */
    private $makers;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Phase", mappedBy="process", cascade={"remove"})
     */
    private $phase;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="durationMin", type="integer", nullable=true)
     */
    private $durationMin;

    /**
     * @var int
     *
     * @ORM\Column(name="durationMax", type="integer", nullable=true)
     */
    private $durationMax;

    /**
     * @var int
     *
     * @ORM\Column(name="participantMin", type="integer")
     */
    private $participantMin;

    /**
     * @var int
     *
     * @ORM\Column(name="participantMax", type="integer")
     */
    private $participantMax;

    /**
     * @var bool
     *
     * @ORM\Column(name="Anonymous", type="boolean")
     */
    private $anonymous;


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
     * Set name
     *
     * @param string $name
     *
     * @return Process
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Process
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set durationMin
     *
     * @param integer $durationMin
     *
     * @return Process
     */
    public function setDurationMin($durationMin)
    {
        $this->durationMin = $durationMin;

        return $this;
    }

    /**
     * Get durationMin
     *
     * @return int
     */
    public function getDurationMin()
    {
        return $this->durationMin;
    }

    /**
     * Set durationMax
     *
     * @param integer $durationMax
     *
     * @return Process
     */
    public function setDurationMax($durationMax)
    {
        $this->durationMax = $durationMax;

        return $this;
    }

    /**
     * Get durationMax
     *
     * @return int
     */
    public function getDurationMax()
    {
        return $this->durationMax;
    }

    /**
     * Set participantMin
     *
     * @param integer $participantMin
     *
     * @return Process
     */
    public function setParticipantMin($participantMin)
    {
        $this->participantMin = $participantMin;

        return $this;
    }

    /**
     * Get participantMin
     *
     * @return int
     */
    public function getParticipantMin()
    {
        return $this->participantMin;
    }

    /**
     * Set participantMax
     *
     * @param integer $participantMax
     *
     * @return Process
     */
    public function setParticipantMax($participantMax)
    {
        $this->participantMax = $participantMax;

        return $this;
    }

    /**
     * Get participantMax
     *
     * @return int
     */
    public function getParticipantMax()
    {
        return $this->participantMax;
    }

    /**
     * Set anonymous
     *
     * @param boolean $anonymous
     *
     * @return Process
     */
    public function setAnonymous($anonymous)
    {
        $this->anonymous = $anonymous;

        return $this;
    }

    /**
     * Get anonymous
     *
     * @return bool
     */
    public function getAnonymous()
    {
        return $this->anonymous;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->makers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phase = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set problem
     *
     * @param \GDSS\PlatformBundle\Entity\Problem $problem
     *
     * @return Process
     */
    public function setProblem(\GDSS\PlatformBundle\Entity\Problem $problem = null)
    {
        $this->problem = $problem;

        return $this;
    }

    /**
     * Get problem
     *
     * @return \GDSS\PlatformBundle\Entity\Problem
     */
    public function getProblem()
    {
        return $this->problem;
    }

    /**
     * Add maker
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $maker
     *
     * @return Process
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
     * Add phase
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phase
     *
     * @return Process
     */
    public function addPhase(\GDSS\PhasesBundle\Entity\Phase $phase)
    {
        $this->phase[] = $phase;

        return $this;
    }

    /**
     * Remove phase
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phase
     */
    public function removePhase(\GDSS\PhasesBundle\Entity\Phase $phase)
    {
        $this->phase->removeElement($phase);
    }

    /**
     * Get phase
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhase()
    {
        return $this->phase;
    }
}
