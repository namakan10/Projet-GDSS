<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NegociationCategories
 *
 * @ORM\Table(name="negociation_categories")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\NegociationCategoriesRepository")
 */
class NegociationCategories
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
     * @ORM\Column(name="priority", type="string", length=255, nullable=true)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Phases", inversedBy="categorie")
     */
    private $phase;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategorieSelection", mappedBy="categories", cascade={"remove"})
     */
    private $selection_catego;


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
     * @return NegociationCategories
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
     * @param \GDSS\PlatformBundle\Entity\Phases $phase
     *
     * @return NegociationCategories
     */
    public function setPhase(\GDSS\PlatformBundle\Entity\Phases $phase = null)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return \GDSS\PlatformBundle\Entity\Phases
     */
    public function getPhase()
    {
        return $this->phase;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->selection_catego = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add selectionCatego
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego
     *
     * @return NegociationCategories
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
     * Get selectionCatego
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSelectionCatego()
    {
        return $this->selection_catego;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return NegociationCategories
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
