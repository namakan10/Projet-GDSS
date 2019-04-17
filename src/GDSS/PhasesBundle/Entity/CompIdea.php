<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompIdea
 *
 * @ORM\Table(name="comp_idea")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\CompIdeaRepository")
 */
class CompIdea
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
     * @ORM\Column(name="idea", type="text")
     */
    private $idea;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase", inversedBy="compidea")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phases;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string")
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
     * Set idea
     *
     * @param string $idea
     *
     * @return CompIdea
     */
    public function setIdea($idea)
    {
        $this->idea = $idea;

        return $this;
    }

    /**
     * Get idea
     *
     * @return string
     */
    public function getIdea()
    {
        return $this->idea;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return CompIdea
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
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return CompIdea
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
     * Set phases
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phases
     *
     * @return CompIdea
     */
    public function setPhases(\GDSS\PhasesBundle\Entity\Phase $phases)
    {
        $this->phases = $phases;

        return $this;
    }

    /**
     * Get phases
     *
     * @return \GDSS\PhasesBundle\Entity\Phase
     */
    public function getPhases()
    {
        return $this->phases;
    }
}
