<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Processus
 *
 * @ORM\Table(name="processus")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\ProcessusRepository")
 */
class Processus
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
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Phases", mappedBy="processus", cascade={"remove"})
     */
    private $phases;

    /**
     * @ORM\OneToOne(targetEntity="GDSS\PlatformBundle\Entity\Sujet", mappedBy="processus")
     */
    private $sujet;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @var \int
     *
     * @ORM\Column(name="dureeMin", type="integer", nullable=true)
     */
    private $dureeMin;


    /**
     * @var \int
     *
     * @ORM\Column(name="dureeMax", type="integer", nullable=true)
     */
    private $dureeMax;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreDeParticipantsMinimun", type="integer", nullable=true)
     */
    private $nombreDeParticipantsMinimun;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreDeParticipantsMaximum", type="integer", nullable=true)
     */
    private $nombreDeParticipantsMaximum;

    /**
     * @var int
     *
     * @ORM\Column(name="nbreActuel", type="integer", nullable=true)
     */
    private $nbreActuel;

    /**
     * @var string
     *
     * @ORM\Column(name="Anonyme", type="string")
     */
    private $anonyme;

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
     * @return Processus
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
     * Set description
     *
     * @param string $description
     *
     * @return Processus
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }




    /**
     * Set nombreDeParticipantsMinimum
     *
     * @param integer $nombreDeParticipantsMinimun
     *
     * @return Processus
     */
    public function setNombreDeParticipantsMinimum($nombreDeParticipantsMinimun)
    {
        $this->nombreDeParticipantsMinimun = $nombreDeParticipantsMinimun;

        return $this;
    }

    /**
     * Get nombreDeParticipantsMinimum
     *
     * @return int
     */
    public function getNombreDeParticipantsMinimum()
    {
        return $this->nombreDeParticipantsMinimun;
    }

    /**
     * Set nombreDeParticipantsMaximum
     *
     * @param integer $nombreDeParticipantsMaximum
     *
     * @return Processus
     */
    public function setNombreDeParticipantsMaximum($nombreDeParticipantsMaximum)
    {
        $this->nombreDeParticipantsMaximum = $nombreDeParticipantsMaximum;

        return $this;
    }

    /**
     * Get nombreDeParticipantsMaximum
     *
     * @return int
     */
    public function getNombreDeParticipantsMaximum()
    {
        return $this->nombreDeParticipantsMaximum;
    }


    /**
     * Set nbreActuel
     *
     * @param integer $nbreActuel
     *
     * @return Processus
     */
    public function setNbreActuel($nbreActuel)
    {
        $this->nbreActuel = $nbreActuel;

        return $this;
    }

    /**
     * Get nbreActuel
     *
     * @return integer
     */
    public function getNbreActuel()
    {
        return $this->nbreActuel;
    }

    /**
     * Set dureeMin
     *
     * @param integer $dureeMin
     *
     * @return Processus
     */
    public function setDureeMin($dureeMin)
    {
        $this->dureeMin = $dureeMin;

        return $this;
    }


    /**
     * Get dureeMin
     *
     * @return integer
     */
    public function getDureeMin()
    {
        return $this->dureeMin;
    }


    /**
     * Set dureeMax
     *
     * @param integer $dureeMax
     *
     * @return Processus
     */
    public function setDureeMax($dureeMax)
    {
        $this->dureeMax = $dureeMax;

        return $this;
    }

    /**
     * Get dureeMax
     *
     * @return integer
     */
    public function getDureeMax()
    {
        return $this->dureeMax;
    }

    /**
     * Set nombreDeParticipantsMinimun
     *
     * @param integer $nombreDeParticipantsMinimun
     *
     * @return Processus
     */
    public function setNombreDeParticipantsMinimun($nombreDeParticipantsMinimun)
    {
        $this->nombreDeParticipantsMinimun = $nombreDeParticipantsMinimun;

        return $this;
    }

    /**
     * Get nombreDeParticipantsMinimun
     *
     * @return integer
     */
    public function getNombreDeParticipantsMinimun()
    {
        return $this->nombreDeParticipantsMinimun;
    }

    /**
     * Set anonyme
     *
     * @param string $anonyme
     *
     * @return Processus
     */
    public function setAnonyme($anonyme)
    {
        $this->anonyme = $anonyme;

        return $this;
    }

    /**
     * Get anonyme
     *
     * @return string
     */
    public function getAnonyme()
    {
        return $this->anonyme;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->phases = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add phase
     *
     * @param \GDSS\PlatformBundle\Entity\Phases $phase
     *
     * @return Processus
     */
    public function addPhase(\GDSS\PlatformBundle\Entity\Phases $phase)
    {
        $this->phases[] = $phase;

        return $this;
    }

    /**
     * Remove phase
     *
     * @param \GDSS\PlatformBundle\Entity\Phases $phase
     */
    public function removePhase(\GDSS\PlatformBundle\Entity\Phases $phase)
    {
        $this->phases->removeElement($phase);
    }

    /**
     * Get phases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhases()
    {
        return $this->phases;
    }

    /**
     * Set sujet
     *
     * @param \GDSS\PlatformBundle\Entity\Sujet $sujet
     *
     * @return Processus
     */
    public function setSujet(\GDSS\PlatformBundle\Entity\Sujet $sujet = null)
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
}
