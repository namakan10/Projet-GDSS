<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationSubSubject
 *
 * @ORM\Table(name="generation_sub_subject")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationSubSubjectRepository")
 */
class GenerationSubSubject
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
     * @var bool
     * @ORM\Column(name="allow", type="boolean")
     */
    private $allow = false;


    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase", inversedBy="subsubject")
     */
    private $phases;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Problem", inversedBy="subsubject")
     */
    private $problem;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution", cascade={"remove"}, mappedBy="subsubject", cascade={"remove"})
     */
    private $contrib;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\MakersGroup", mappedBy="subproblem")
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity="GDSS\PlatformBundle\Entity\DecisionMakers", mappedBy="subproblem")
     */
    private $makers;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * @return GenerationSubSubject
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
     * Constructor
     */
    public function __construct()
    {
        $this->contrib = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add contrib
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib
     *
     * @return GenerationSubSubject
     */
    public function addContrib(\GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib)
    {
        $this->contrib[] = $contrib;

        return $this;
    }

    /**
     * Remove contrib
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib
     */
    public function removeContrib(\GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib)
    {
        $this->contrib->removeElement($contrib);
    }

    /**
     * Get contrib
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrib()
    {
        return $this->contrib;
    }

    /**
     * Set phases
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phases
     *
     * @return GenerationSubSubject
     */
    public function setPhases(\GDSS\PhasesBundle\Entity\Phase $phases)
    {
        $this->phases = $phases;

        return $this;
    }

    /**
     * Get phases
     *
     * @return \GDSS\PhasesBundle\Entity\Phase
     */
    public function getPhases()
    {
        return $this->phases;
    }

    /**
     * Add maker
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $maker
     *
     * @return GenerationSubSubject
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
     * Set problem
     *
     * @param \GDSS\PlatformBundle\Entity\Problem $problem
     *
     * @return GenerationSubSubject
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
     * Set allow
     *
     * @param boolean $allow
     *
     * @return GenerationSubSubject
     */
    public function setAllow($allow)
    {
        $this->allow = $allow;

        return $this;
    }

    /**
     * Get allow
     *
     * @return boolean
     */
    public function getAllow()
    {
        return $this->allow;
    }

    /**
     * Add group
     *
     * @param \GDSS\PhasesBundle\Entity\MakersGroup $group
     *
     * @return GenerationSubSubject
     */
    public function addGroup(\GDSS\PhasesBundle\Entity\MakersGroup $group)
    {
        $this->group[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \GDSS\PhasesBundle\Entity\MakersGroup $group
     */
    public function removeGroup(\GDSS\PhasesBundle\Entity\MakersGroup $group)
    {
        $this->group->removeElement($group);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroup()
    {
        return $this->group;
    }
}
