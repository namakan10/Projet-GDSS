<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phase
 *
 * @ORM\Table(name="phase")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\PhaseRepository")
 */
class Phase
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Process", inversedBy="phase")
     * @ORM\JoinColumn(nullable=false)
     */
    private $process;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationContribution", mappedBy="phases", cascade={"remove"})
     */
    private $contribution;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Chat", mappedBy="phase", cascade={"remove"})
     */
    private $chat;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\NegociationCategories", mappedBy="phase", cascade={"remove"})
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubject", mappedBy="phases", cascade={"remove"})
     */
    private $subsubject;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\CompIdea", mappedBy="phases", cascade={"remove"})
     */
    private $compidea;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var boolean
     *
     * @ORM\Column(name="start", type="boolean")
     */
    private $start = false;

    /**
     * @var int
     *
     * @ORM\Column(name="durationMax", type="integer")
     */
    private $durationMax;

    /**
     * @var int
     *
     * @ORM\Column(name="durationMin", type="integer")
     */
    private $durationMin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datestart", type="datetime")
     */
    private $datestart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateend", type="datetime")
     */
    private $dateend;

    /**
     * @var integer
     *
     * @ORM\Column(name="selection", type="integer", nullable=true)
     */
    private $selection;

    /**
     * @var string
     *
     * @ORM\Column(name="expert", type="string", length=255, nullable=true)
     */
    private $expert;

    /**
     * @var string
     *
     * @ORM\Column(name="thinklet", type="string", length=255, nullable=true)
     */
    private $thinklet;


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
     * @return Phase
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
     * Set durationMax
     *
     * @param integer $durationMax
     *
     * @return Phase
     */
    public function setDurationMax($durationMax)
    {
        $this->durationMax = $durationMax;

        return $this;
    }

    /**
     * Get durationMax
     *
     * @return int
     */
    public function getDurationMax()
    {
        return $this->durationMax;
    }

    /**
     * Set durationMin
     *
     * @param integer $durationMin
     *
     * @return Phase
     */
    public function setDurationMin($durationMin)
    {
        $this->durationMin = $durationMin;

        return $this;
    }

    /**
     * Get durationMin
     *
     * @return int
     */
    public function getDurationMin()
    {
        return $this->durationMin;
    }

    /**
     * Set datestart
     *
     * @param \DateTime $datestart
     *
     * @return Phase
     */
    public function setDatestart($datestart)
    {
        $this->datestart = $datestart;

        return $this;
    }

    /**
     * Get datestart
     *
     * @return \DateTime
     */
    public function getDatestart()
    {
        return $this->datestart;
    }

    /**
     * Set dateend
     *
     * @param \DateTime $dateend
     *
     * @return Phase
     */
    public function setDateend($dateend)
    {
        $this->dateend = $dateend;

        return $this;
    }

    /**
     * Get dateend
     *
     * @return \DateTime
     */
    public function getDateend()
    {
        return $this->dateend;
    }

    /**
     * Set selection
     *
     * @param boolean $selection
     *
     * @return Phase
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
     * Set expert
     *
     * @param string $expert
     *
     * @return Phase
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

    /**
     * Set thinklet
     *
     * @param string $thinklet
     *
     * @return Phase
     */
    public function setThinklet($thinklet)
    {
        $this->thinklet = $thinklet;

        return $this;
    }

    /**
     * Get thinklet
     *
     * @return string
     */
    public function getThinklet()
    {
        return $this->thinklet;
    }

    /**
     * Set process
     *
     * @param \GDSS\PlatformBundle\Entity\Process $process
     *
     * @return Phase
     */
    public function setProcess(\GDSS\PlatformBundle\Entity\Process $process)
    {
        $this->process = $process;

        return $this;
    }

    /**
     * Get process
     *
     * @return \GDSS\PlatformBundle\Entity\Process
     */
    public function getProcess()
    {
        return $this->process;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contribution = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subsubject = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contribution
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationContribution $contribution
     *
     * @return Phase
     */
    public function addContribution(\GDSS\PhasesBundle\Entity\GenerationContribution $contribution)
    {
        $this->contribution[] = $contribution;

        return $this;
    }

    /**
     * Remove contribution
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationContribution $contribution
     */
    public function removeContribution(\GDSS\PhasesBundle\Entity\GenerationContribution $contribution)
    {
        $this->contribution->removeElement($contribution);
    }

    /**
     * Get contribution
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContribution()
    {
        return $this->contribution;
    }

    /**
     * Add categorie
     *
     * @param \GDSS\PhasesBundle\Entity\NegociationCategories $categorie
     *
     * @return Phase
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
     * Add subsubject
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject
     *
     * @return Phase
     */
    public function addSubsubject(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject)
    {
        $this->subsubject[] = $subsubject;

        return $this;
    }

    /**
     * Remove subsubject
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject
     */
    public function removeSubsubject(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject)
    {
        $this->subsubject->removeElement($subsubject);
    }

    /**
     * Get subsubject
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubsubject()
    {
        return $this->subsubject;
    }

    /**
     * Add compidea
     *
     * @param \GDSS\PhasesBundle\Entity\CompIdea $compidea
     *
     * @return Phase
     */
    public function addCompidea(\GDSS\PhasesBundle\Entity\CompIdea $compidea)
    {
        $this->compidea[] = $compidea;

        return $this;
    }

    /**
     * Remove compidea
     *
     * @param \GDSS\PhasesBundle\Entity\CompIdea $compidea
     */
    public function removeCompidea(\GDSS\PhasesBundle\Entity\CompIdea $compidea)
    {
        $this->compidea->removeElement($compidea);
    }

    /**
     * Get compidea
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompidea()
    {
        return $this->compidea;
    }

    /**
     * Set start
     *
     * @param boolean $start
     *
     * @return Phase
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return boolean
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Add chat
     *
     * @param \GDSS\PhasesBundle\Entity\Chat $chat
     *
     * @return Phase
     */
    public function addChat(\GDSS\PhasesBundle\Entity\Chat $chat)
    {
        $this->chat[] = $chat;

        return $this;
    }

    /**
     * Remove chat
     *
     * @param \GDSS\PhasesBundle\Entity\Chat $chat
     */
    public function removeChat(\GDSS\PhasesBundle\Entity\Chat $chat)
    {
        $this->chat->removeElement($chat);
    }

    /**
     * Get chat
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChat()
    {
        return $this->chat;
    }
}
