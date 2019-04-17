<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MakersGroup
 *
 * @ORM\Table(name="makers_group")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\MakersGroupRepository")
 */
class MakersGroup
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=255)
     */
    private $phase;


    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\DecisionMakers", inversedBy="group")
     */
    private $maker;


    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategories", inversedBy="group")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorie;


    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubject", inversedBy="group")
     * @ORM\JoinColumn(nullable=true)
     */
    private $subproblem;

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
     * @return MakersGroup
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
     * Set phase
     *
     * @param string $phase
     *
     * @return MakersGroup
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

    /**
     * Set maker
     *
     * @param \GDSS\PlatformBundle\Entity\DecisionMakers $maker
     *
     * @return MakersGroup
     */
    public function setMaker(\GDSS\PlatformBundle\Entity\DecisionMakers $maker = null)
    {
        $this->maker = $maker;

        return $this;
    }

    /**
     * Get maker
     *
     * @return \GDSS\PlatformBundle\Entity\DecisionMakers
     */
    public function getMaker()
    {
        return $this->maker;
    }

    /**
     * Set categorie
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategories $categorie
     *
     * @return MakersGroup
     */
    public function setCategorie(\GDSS\PhasesBundle\Entity\NegociationCategories $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \GDSS\PhasesBundle\Entity\NegociationCategories
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set subproblem
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem
     *
     * @return MakersGroup
     */
    public function setSubproblem(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subproblem = null)
    {
        $this->subproblem = $subproblem;

        return $this;
    }

    /**
     * Get subproblem
     *
     * @return \GDSS\PhasesBundle\Entity\GenerationSubSubject
     */
    public function getSubproblem()
    {
        return $this->subproblem;
    }
}
