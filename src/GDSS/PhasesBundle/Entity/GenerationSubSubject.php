<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationSubSubject
 *
 * @ORM\Table(name="generation_sub_subject")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationSubSubjectRepository")
 */
class GenerationSubSubject
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Phases")
     */
    private $phases;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution", cascade={"remove"}, mappedBy="subsubject")
     */
    private $contrib;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * @return GenerationSubSubject
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
        $this->contrib = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set phases
     *
     * @param \GDSS\PlatformBundle\Entity\Phases $phases
     *
     * @return GenerationSubSubject
     */
    public function setPhases(\GDSS\PlatformBundle\Entity\Phases $phases = null)
    {
        $this->phases = $phases;

        return $this;
    }

    /**
     * Get phases
     *
     * @return \GDSS\PlatformBundle\Entity\Phases
     */
    public function getPhases()
    {
        return $this->phases;
    }

    /**
     * Add contrib
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib
     *
     * @return GenerationSubSubject
     */
    public function addContrib(\GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib)
    {
        $this->contrib[] = $contrib;

        return $this;
    }

    /**
     * Remove contrib
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib
     */
    public function removeContrib(\GDSS\PhasesBundle\Entity\GenerationSubSubjectContribution $contrib)
    {
        $this->contrib->removeElement($contrib);
    }

    /**
     * Get contrib
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrib()
    {
        return $this->contrib;
    }
}
