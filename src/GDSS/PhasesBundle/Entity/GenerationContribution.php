<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationContribution
 *
 * @ORM\Table(name="generation_contribution")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationContributionRepository")
 */
class GenerationContribution
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
     * @ORM\Column(name="contribution", type="text")
     */
    private $contribution;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Phases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phases;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

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
     * Set contribution
     *
     * @param string $contribution
     *
     * @return GenerationContribution
     */
    public function setContribution($contribution)
    {
        $this->contribution = $contribution;

        return $this;
    }

    /**
     * Get contribution
     *
     * @return string
     */
    public function getContribution()
    {
        return $this->contribution;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return GenerationContribution
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
     * @return GenerationContribution
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
     * @param \GDSS\PlatformBundle\Entity\Phases $phases
     *
     * @return GenerationContribution
     */
    public function setPhases(\GDSS\PlatformBundle\Entity\Phases $phases)
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return GenerationContribution
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }
}
