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
     * @var boolean
     *
     * @ORM\Column(name="allow", type="boolean")
     */
    private $allow = false;


    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255, nullable=true)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase", inversedBy="categorie")
     */
    private $phase;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategorieSelection", mappedBy="categories", cascade={"remove"})
     */
    private $selection_catego;


    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\MakersGroup", mappedBy="categorie", cascade={"remove"})
     */
    private $group;


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

    /**
     * Set phase
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phase
     *
     * @return NegociationCategories
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

    /**
     * Set allow
     *
     * @param boolean $allow
     *
     * @return NegociationCategories
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
     * @return NegociationCategories
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
