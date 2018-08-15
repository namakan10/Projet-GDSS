<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Decideurs
 *
 * @ORM\Table(name="decideurs")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\DecideursRepository")
 */
class Decideurs
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User", inversedBy="decideurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Sujet", inversedBy="decideurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sujet;

    /**
     * @ORM\Column(name="pseudodecideurs", type="string")
     */
    private $pseudodecideurs;


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
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Decideurs
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
     * Set sujet
     *
     * @param \GDSS\PlatformBundle\Entity\Sujet $sujet
     *
     * @return Decideurs
     */
    public function setSujet(\GDSS\PlatformBundle\Entity\Sujet $sujet)
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

    /**
     * Set pseudodecideurs
     *
     * @param string $pseudodecideurs
     *
     * @return Decideurs
     */
    public function setPseudodecideurs($pseudodecideurs)
    {
        $this->pseudodecideurs = $pseudodecideurs;

        return $this;
    }

    /**
     * Get pseudodecideurs
     *
     * @return string
     */
    public function getPseudodecideurs()
    {
        return $this->pseudodecideurs;
    }
}
