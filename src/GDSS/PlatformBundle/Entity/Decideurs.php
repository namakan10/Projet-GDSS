<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Decideurs
 *
 * @ORM\Table(name="decideurs")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\DecideursRepository")
 */
class Decideurs
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", inversedBy="decideurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Sujet", inversedBy="decideurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sujet;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategorieSelection", mappedBy="decideurs", cascade={"remove"})
     */
    private $selection_catego;


    /**
     * @ORM\Column(name="pseudodecideurs", type="string")
     */
    private $pseudodecideurs;


    /**
     * @ORM\Column(name="selection", type="boolean", nullable=true)
     */
    private $selection;


    /**
     * @ORM\Column(name="expert", type="boolean", nullable=true)
     */
    private $expert = null;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->selection_catego = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set pseudodecideurs
     *
     * @param string $pseudodecideurs
     *
     * @return Decideurs
     */
    public function setPseudodecideurs($pseudodecideurs)
    {
        $this->pseudodecideurs = $pseudodecideurs;

        return $this;
    }

    /**
     * Get pseudodecideurs
     *
     * @return string
     */
    public function getPseudodecideurs()
    {
        return $this->pseudodecideurs;
    }

    /**
     * Set selection
     *
     * @param boolean $selection
     *
     * @return Decideurs
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * Get selection
     *
     * @return boolean
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Decideurs
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
     * Set sujet
     *
     * @param \GDSS\PlatformBundle\Entity\Sujet $sujet
     *
     * @return Decideurs
     */
    public function setSujet(\GDSS\PlatformBundle\Entity\Sujet $sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return \GDSS\PlatformBundle\Entity\Sujet
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Add selectionCatego
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategorieSelection $selectionCatego
     *
     * @return Decideurs
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
     * Set expert
     *
     * @param boolean $expert
     *
     * @return Decideurs
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return boolean
     */
    public function getExpert()
    {
        return $this->expert;
    }
}
