<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NegociationCategorieSelection
 *
 * @ORM\Table(name="negociation_categorie_selection")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\NegociationCategorieSelectionRepository")
 */
class NegociationCategorieSelection
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
     *
     * @ORM\Column(name="selection", type="boolean")
     */
    private $selection;


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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Decideurs", inversedBy="selection_catego")
     * @ORM\JoinColumn(nullable=false)
     */
    private $decideurs;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategories", inversedBy="selection_catego")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * Set selection
     *
     * @param boolean $selection
     *
     * @return NegociationCategorieSelection
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
     * Set decideurs
     *
     * @param \GDSS\PlatformBundle\Entity\Decideurs $decideurs
     *
     * @return NegociationCategorieSelection
     */
    public function setDecideurs(\GDSS\PlatformBundle\Entity\Decideurs $decideurs)
    {
        $this->decideurs = $decideurs;

        return $this;
    }

    /**
     * Get decideurs
     *
     * @return \GDSS\PlatformBundle\Entity\Decideurs
     */
    public function getDecideurs()
    {
        return $this->decideurs;
    }

    /**
     * Set categories
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategories $categories
     *
     * @return NegociationCategorieSelection
     */
    public function setCategories(\GDSS\PhasesBundle\Entity\NegociationCategories $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return \GDSS\PhasesBundle\Entity\NegociationCategories
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
