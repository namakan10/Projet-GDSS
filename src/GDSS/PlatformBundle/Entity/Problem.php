<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Problem
 *
 * @ORM\Table(name="problem")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\ProblemRepository")
 */
class Problem
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="GDSS\PlatformBundle\Entity\Process", mappedBy="problem", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, unique=true, onDelete="SET NULL")
     */
    private $process;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Constraints", mappedBy="problem", cascade={"remove"})
     */
    private $constraints;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationFormVote", mappedBy="problem", cascade={"remove"})
     */
    private $negociationvote;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Criteria", mappedBy="problem", cascade={"remove"})
     */
    private $criteria;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="goal", type="string", length=255)
     */
    private $goal;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="text")
     */
    private $context;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datestart", type="datetime")
     */
    private $datestart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateend", type="datetime")
     */
    private $dateend;

    /**
     * @var \Boolean
     *
     * @ORM\Column(name="subproblemdefinied", type="boolean")
     */
    private $subproblemdefinied = false;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubject", mappedBy="problem", cascade={"remove"})
     */
    private $subsubject;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->constraints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->negociationvote = new \Doctrine\Common\Collections\ArrayCollection();
        $this->criteria = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subsubject = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Problem
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
     * Set goal
     *
     * @param string $goal
     *
     * @return Problem
     */
    public function setGoal($goal)
    {
        $this->goal = $goal;

        return $this;
    }

    /**
     * Get goal
     *
     * @return string
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return Problem
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set context
     *
     * @param string $context
     *
     * @return Problem
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set datestart
     *
     * @param \DateTime $datestart
     *
     * @return Problem
     */
    public function setDatestart($datestart)
    {
        $this->datestart = $datestart;

        return $this;
    }

    /**
     * Get datestart
     *
     * @return \DateTime
     */
    public function getDatestart()
    {
        return $this->datestart;
    }

    /**
     * Set dateend
     *
     * @param \DateTime $dateend
     *
     * @return Problem
     */
    public function setDateend($dateend)
    {
        $this->dateend = $dateend;

        return $this;
    }

    /**
     * Get dateend
     *
     * @return \DateTime
     */
    public function getDateend()
    {
        return $this->dateend;
    }

    /**
     * Set subproblemdefinied
     *
     * @param boolean $subproblemdefinied
     *
     * @return Problem
     */
    public function setSubproblemdefinied($subproblemdefinied)
    {
        $this->subproblemdefinied = $subproblemdefinied;

        return $this;
    }

    /**
     * Get subproblemdefinied
     *
     * @return boolean
     */
    public function getSubproblemdefinied()
    {
        return $this->subproblemdefinied;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Problem
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

    /**
     * Set process
     *
     * @param \GDSS\PlatformBundle\Entity\Process $process
     *
     * @return Problem
     */
    public function setProcess(\GDSS\PlatformBundle\Entity\Process $process = null)
    {
        $this->process = $process;

        return $this;
    }

    /**
     * Get process
     *
     * @return \GDSS\PlatformBundle\Entity\Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Add constraint
     *
     * @param \GDSS\PlatformBundle\Entity\Constraints $constraint
     *
     * @return Problem
     */
    public function addConstraint(\GDSS\PlatformBundle\Entity\Constraints $constraint)
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    /**
     * Remove constraint
     *
     * @param \GDSS\PlatformBundle\Entity\Constraints $constraint
     */
    public function removeConstraint(\GDSS\PlatformBundle\Entity\Constraints $constraint)
    {
        $this->constraints->removeElement($constraint);
    }

    /**
     * Get constraints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Add negociationvote
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote
     *
     * @return Problem
     */
    public function addNegociationvote(\GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote)
    {
        $this->negociationvote[] = $negociationvote;

        return $this;
    }

    /**
     * Remove negociationvote
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote
     */
    public function removeNegociationvote(\GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote)
    {
        $this->negociationvote->removeElement($negociationvote);
    }

    /**
     * Get negociationvote
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNegociationvote()
    {
        return $this->negociationvote;
    }

    /**
     * Add criterion
     *
     * @param \GDSS\PlatformBundle\Entity\Criteria $criterion
     *
     * @return Problem
     */
    public function addCriterium(\GDSS\PlatformBundle\Entity\Criteria $criterion)
    {
        $this->criteria[] = $criterion;

        return $this;
    }

    /**
     * Remove criterion
     *
     * @param \GDSS\PlatformBundle\Entity\Criteria $criterion
     */
    public function removeCriterium(\GDSS\PlatformBundle\Entity\Criteria $criterion)
    {
        $this->criteria->removeElement($criterion);
    }

    /**
     * Get criteria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Add subsubject
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject
     *
     * @return Problem
     */
    public function addSubsubject(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject)
    {
        $this->subsubject[] = $subsubject;

        return $this;
    }

    /**
     * Remove subsubject
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject
     */
    public function removeSubsubject(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject)
    {
        $this->subsubject->removeElement($subsubject);
    }

    /**
     * Get subsubject
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubsubject()
    {
        return $this->subsubject;
    }
}
