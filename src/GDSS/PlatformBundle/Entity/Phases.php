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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="periodemin", type="string", length=255, nullable=true)
     */
    private $periodemin;



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
}
