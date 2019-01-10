<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Sujet
 *
 * @ORM\Table(name="sujet")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\SujetRepository")
 */
class Sujet
{

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\OneToOne(targetEntity="GDSS\PlatformBundle\Entity\Processus", cascade={"remove"}, inversedBy="sujet")
     * @ORM\JoinColumn(nullable=true, unique=true)
     */
    private $processus;


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
     * @ORM\Column(name="titre", type="text")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="but", type="text")
     */
    private $but;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=255)
     */
    private $domaine;

    /**
     * @var string
     *
     * @ORM\Column(name="contexte", type="text")
     */
    private $contexte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Decideurs", mappedBy="sujet", cascade={"remove"})
     */
    private $decideurs;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Contraintes", mappedBy="sujet", cascade={"remove"})
     */
    private $contraintes;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Criteres", mappedBy="sujet", cascade={"remove"})
     */
    private $criteres;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="datetime")
     */
    private $dateFin;


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
     * Set but
     *
     * @param string $but
     *
     * @return Sujet
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * Get but
     *
     * @return string
     */
    public function getBut()
    {
        return $this->but;
    }

    /**
     * Set domaine
     *
     * @param string $domaine
     *
     * @return Sujet
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return string
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set contexte
     *
     * @param string $contexte
     *
     * @return Sujet
     */
    public function setContexte($contexte)
    {
        $this->contexte = $contexte;

        return $this;
    }

    /**
     * Get contexte
     *
     * @return string
     */
    public function getContexte()
    {
        return $this->contexte;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Sujet
     */
    public function setDateDebut($dateDebut)
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
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Sujet
     */
    public function setDateFin($dateFin)
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
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Sujet
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Sujet
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Add decideur
     *
     * @param \GDSS\PlatformBundle\Entity\Decideurs $decideur
     *
     * @return Sujet
     */
    public function addDecideur(\GDSS\PlatformBundle\Entity\Decideurs $decideur)
    {
        $this->decideurs[] = $decideur;

        return $this;
    }

    /**
     * Remove decideur
     *
     * @param \GDSS\PlatformBundle\Entity\Decideurs $decideur
     */
    public function removeDecideur(\GDSS\PlatformBundle\Entity\Decideurs $decideur)
    {
        $this->decideurs->removeElement($decideur);
    }

    /**
     * Get decideurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDecideurs()
    {
        return $this->decideurs;
    }

    /**
     * Set processus
     *
     * @param \GDSS\PlatformBundle\Entity\Processus $processus
     *
     * @return Sujet
     */
    public function setProcessus(\GDSS\PlatformBundle\Entity\Processus $processus = null)
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
     * Add contrainte
     *
     * @param \GDSS\PlatformBundle\Entity\Contraintes $contrainte
     *
     * @return Sujet
     */
    public function addContrainte(\GDSS\PlatformBundle\Entity\Contraintes $contrainte)
    {
        $this->contraintes[] = $contrainte;

        return $this;
    }

    /**
     * Remove contrainte
     *
     * @param \GDSS\PlatformBundle\Entity\Contraintes $contrainte
     */
    public function removeContrainte(\GDSS\PlatformBundle\Entity\Contraintes $contrainte)
    {
        $this->contraintes->removeElement($contrainte);
    }

    /**
     * Get contraintes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContraintes()
    {
        return $this->contraintes;
    }

    /**
     * Add critere
     *
     * @param \GDSS\PlatformBundle\Entity\Criteres $critere
     *
     * @return Sujet
     */
    public function addCritere(\GDSS\PlatformBundle\Entity\Criteres $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere
     *
     * @param \GDSS\PlatformBundle\Entity\Criteres $critere
     */
    public function removeCritere(\GDSS\PlatformBundle\Entity\Criteres $critere)
    {
        $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }
}
