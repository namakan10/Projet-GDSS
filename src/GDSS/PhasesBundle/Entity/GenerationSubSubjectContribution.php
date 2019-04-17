<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationSubSubjectContribution
 *
 * @ORM\Table(name="generation_sub_subject_contribution")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationSubSubjectContributionRepository")
 */
class GenerationSubSubjectContribution
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
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\GenerationSubSubject", inversedBy="contrib")
     */
    private $subsubject;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="contrib", type="text")
     */
    private $contrib;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;


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
     * Set contrib
     *
     * @param string $contrib
     *
     * @return GenerationSubSubjectContribution
     */
    public function setContrib($contrib)
    {
        $this->contrib = $contrib;

        return $this;
    }

    /**
     * Get contrib
     *
     * @return string
     */
    public function getContrib()
    {
        return $this->contrib;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return GenerationSubSubjectContribution
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set subsubject
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject
     *
     * @return GenerationSubSubjectContribution
     */
    public function setSubsubject(\GDSS\PhasesBundle\Entity\GenerationSubSubject $subsubject = null)
    {
        $this->subsubject = $subsubject;

        return $this;
    }

    /**
     * Get subsubject
     *
     * @return \GDSS\PhasesBundle\Entity\GenerationSubSubject
     */
    public function getSubsubject()
    {
        return $this->subsubject;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return GenerationSubSubjectContribution
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
}
