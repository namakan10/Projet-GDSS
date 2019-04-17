<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NegociationFormVote
 *
 * @ORM\Table(name="negociation_form_vote")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\NegociationFormVoteRepository")
 */
class NegociationFormVote
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\DecisionMakers", inversedBy="negociationvote")
     * @ORM\JoinColumn(nullable=false)
     */
    private $makers;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Problem", inversedBy="negociationvote")
     * @ORM\JoinColumn(nullable=false)
     */
    private $problem;


    /**
     * @var bool
     *
     * @ORM\Column(name="formulation", type="boolean")
     */
    private $formulation = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="expert", type="boolean")
     */
    private $expert = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="ambiguity", type="boolean")
     */
    private $ambiguity = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="revelantlist", type="boolean")
     */
    private $revelantlist = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="category", type="boolean")
     */
    private $category = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="categorizer", type="boolean")
     */
    private $categorizer = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=255)
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
     * Set formulation
     *
     * @param boolean $formulation
     *
     * @return NegociationFormVote
     */
    public function setFormulation($formulation)
    {
        $this->formulation = $formulation;

        return $this;
    }

    /**
     * Get formulation
     *
     * @return bool
     */
    public function getFormulation()
    {
        return $this->formulation;
    }

    /**
     * Set expert
     *
     * @param boolean $expert
     *
     * @return NegociationFormVote
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
     * Set ambiguity
     *
     * @param boolean $ambiguity
     *
     * @return NegociationFormVote
     */
    public function setAmbiguity($ambiguity)
    {
        $this->ambiguity = $ambiguity;

        return $this;
    }

    /**
     * Get ambiguity
     *
     * @return bool
     */
    public function getAmbiguity()
    {
        return $this->ambiguity;
    }

    /**
     * Set revelantlist
     *
     * @param boolean $revelantlist
     *
     * @return NegociationFormVote
     */
    public function setRevelantlist($revelantlist)
    {
        $this->revelantlist = $revelantlist;

        return $this;
    }

    /**
     * Get revelantlist
     *
     * @return bool
     */
    public function getRevelantlist()
    {
        return $this->revelantlist;
    }

    /**
     * Set makers
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $makers
     *
     * @return NegociationFormVote
     */
    public function setMakers(\GDSS\PlatformBundle\Entity\DecisionMakers $makers)
    {
        $this->makers = $makers;

        return $this;
    }

    /**
     * Get makers
     *
     * @return \GDSS\PlatformBundle\Entity\DecisionMakers
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
     * @return NegociationFormVote
     */
    public function setProblem(\GDSS\PlatformBundle\Entity\Problem $problem)
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
     * Set category
     *
     * @param boolean $category
     *
     * @return NegociationFormVote
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return boolean
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set categorizer
     *
     * @param boolean $categorizer
     *
     * @return NegociationFormVote
     */
    public function setCategorizer($categorizer)
    {
        $this->categorizer = $categorizer;

        return $this;
    }

    /**
     * Get categorizer
     *
     * @return boolean
     */
    public function getCategorizer()
    {
        return $this->categorizer;
    }

    /**
     * Set phase
     *
     * @param string $phase
     *
     * @return NegociationFormVote
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return string
     */
    public function getPhase()
    {
        return $this->phase;
    }
}
