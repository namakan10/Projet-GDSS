<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DecisionMakers
 *
 * @ORM\Table(name="decision_makers")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\DecisionMakersRepository")
 */
class DecisionMakers
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", inversedBy="makers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Process", inversedBy="makers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $process;

    /**
     * @ORM\ManyToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubject", inversedBy="makers")
     */
    private $subproblem;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationFormVote", mappedBy="makers", cascade={"remove"})
     */
    private $negociationvote;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategorieSelection", mappedBy="makers")
     */
    private $selection_catego;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\MakersGroup", mappedBy="maker", cascade={"remove"})
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudoMaker", type="string", length=255)
     */
    private $pseudoMaker;

    /**
     * @var bool
     *
     * @ORM\Column(name="selection", type="boolean", nullable=true)
     */
    private $selection;

    /**
     * @var bool
     *
     * @ORM\Column(name="expert", type="boolean", nullable=true)
     */
    private $expert;

    /**
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote = 0;


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
     * Set pseudoMaker
     *
     * @param string $pseudoMaker
     *
     * @return DecisionMakers
     */
    public function setPseudoMaker($pseudoMaker)
    {
        $this->pseudoMaker = $pseudoMaker;

        return $this;
    }

    /**
     * Get pseudoMaker
     *
     * @return string
     */
    public function getPseudoMaker()
    {
        return $this->pseudoMaker;
    }

    /**
     * Set selection
     *
     * @param boolean $selection
     *
     * @return DecisionMakers
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * Get selection
     *
     * @return bool
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Set expert
     *
     * @param boolean $expert
     *
     * @return DecisionMakers
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return bool
     */
    public function getExpert()
    {
        return $this->expert;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return DecisionMakers
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
     * @return DecisionMakers
     */
    public function setProcess(\GDSS\PlatformBundle\Entity\Process $process)
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
     * Set selectionCatego
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego
     *
     * @return DecisionMakers
     */
    public function setSelectionCatego(\GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego = null)
    {
        $this->selection_catego = $selectionCatego;

        return $this;
    }

    /**
     * Get selectionCatego
     *
     * @return \GDSS\PhasesBundle\Entity\NegociationCategorieSelection
     */
    public function getSelectionCatego()
    {
        return $this->selection_catego;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subproblem = new \Doctrine\Common\Collections\ArrayCollection();
        $this->selection_catego = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subproblem
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem
     *
     * @return DecisionMakers
     */
    public function addSubproblem(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem)
    {
        $this->subproblem[] = $subproblem;

        return $this;
    }

    /**
     * Remove subproblem
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem
     */
    public function removeSubproblem(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem)
    {
        $this->subproblem->removeElement($subproblem);
    }

    /**
     * Get subproblem
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubproblem()
    {
        return $this->subproblem;
    }

    /**
     * Add selectionCatego
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego
     *
     * @return DecisionMakers
     */
    public function addSelectionCatego(\GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego)
    {
        $this->selection_catego[] = $selectionCatego;

        return $this;
    }

    /**
     * Remove selectionCatego
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego
     */
    public function removeSelectionCatego(\GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego)
    {
        $this->selection_catego->removeElement($selectionCatego);
    }

    /**
     * Set negociationvote
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote
     *
     * @return DecisionMakers
     */
    public function setNegociationvote(\GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote = null)
    {
        $this->negociationvote = $negociationvote;

        return $this;
    }

    /**
     * Get negociationvote
     *
     * @return \GDSS\PhasesBundle\Entity\NegociationFormVote
     */
    public function getNegociationvote()
    {
        return $this->negociationvote;
    }

    /**
     * Add group
     *
     * @param \GDSS\PhasesBundle\Entity\MakersGroup $group
     *
     * @return DecisionMakers
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

    /**
     * Set vote
     *
     * @param boolean $vote
     *
     * @return DecisionMakers
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return boolean
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Add negociationvote
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationFormVote $negociationvote
     *
     * @return DecisionMakers
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
}
