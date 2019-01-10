<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phases
 *
 * @ORM\Table(name="phases")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\PhasesRepository")
 */
class Phases
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Processus", cascade={"persist"}, inversedBy="phases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $processus;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategories", mappedBy="phase", cascade={"remove"})
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var integer
     *
     * @ORM\Column(name="selection", type="integer", nullable=true)
     */
    private $selection;

    /**
     * @var string
     *
     * @ORM\Column(name="periodemin", type="string", length=255, nullable=true)
     */
    private $periodemin;


    /**
     * @ORM\Column(name="expert", type="string", length=255, nullable=true)
     */
    private $expert = null;

    /**
     * @var string
     *
     * @ORM\Column(name="periodemax", type="string", length=255, nullable=true)
     */
    private $periodemax;


    /**
     * @ORM\Column(name="dureemin", type="integer", nullable=true)
     */
    private $dureemin;

    /**
     * @ORM\Column(name="dureemax", type="integer", nullable=true)
     */
    private $dureemax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime", nullable=true)
     */
    private $dateFin;


    //Constructeur

    public function __construct()
    {
        $this->dateDebut= new \DateTime();
    }

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Phases
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Phases
     */
    public function setDateStart($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }


    /**
     * Set processus
     *
     * @param \GDSS\PlatformBundle\Entity\Processus $processus
     *
     * @return Phases
     */
    public function setProcessus(\GDSS\PlatformBundle\Entity\Processus $processus)
    {
        $this->processus = $processus;

        return $this;
    }

    /**
     * Get processus
     *
     * @return \GDSS\PlatformBundle\Entity\Processus
     */
    public function getProcessus()
    {
        return $this->processus;
    }



    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Phases
     */
    public function setDateEnd($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }


    /**
     * Set dureemin
     *
     * @param integer $dureemin
     *
     * @return Phases
     */
    public function setDureemin($dureemin)
    {
        $this->dureemin = $dureemin;

        return $this;
    }

    /**
     * Get dureemin
     *
     * @return integer
     */
    public function getDureemin()
    {
        return $this->dureemin;
    }

    /**
     * Set dureemax
     *
     * @param integer $dureemax
     *
     * @return Phases
     */
    public function setDureemax($dureemax)
    {
        $this->dureemax = $dureemax;

        return $this;
    }

    /**
     * Get dureemax
     *
     * @return integer
     */
    public function getDureemax()
    {
        return $this->dureemax;
    }


    /**
     * Set periodemin
     *
     * @param string $periodemin
     *
     * @return Phases
     */
    public function setPeriodemin($periodemin)
    {
        $this->periodemin = $periodemin;

        return $this;
    }

    /**
     * Get periodemin
     *
     * @return string
     */
    public function getPeriodemin()
    {
        return $this->periodemin;
    }

    /**
     * Set periodemax
     *
     * @param string $periodemax
     *
     * @return Phases
     */
    public function setPeriodemax($periodemax)
    {
        $this->periodemax = $periodemax;

        return $this;
    }

    /**
     * Get periodemax
     *
     * @return string
     */
    public function getPeriodemax()
    {
        return $this->periodemax;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Phases
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Phases
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Add categorie
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategories $categorie
     *
     * @return Phases
     */
    public function addCategorie(\GDSS\PhasesBundle\Entity\NegociationCategories $categorie)
    {
        $this->categorie[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategories $categorie
     */
    public function removeCategorie(\GDSS\PhasesBundle\Entity\NegociationCategories $categorie)
    {
        $this->categorie->removeElement($categorie);
    }

    /**
     * Get categorie
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set selection
     *
     * @param integer $selection
     *
     * @return Phases
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * Get selection
     *
     * @return integer
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Set expert
     *
     * @param string $expert
     *
     * @return Phases
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return string
     */
    public function getExpert()
    {
        return $this->expert;
    }
}
