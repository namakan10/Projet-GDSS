<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Repertoire
 *
 * @ORM\Table(name="repertoire")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\RepertoireRepository")
 */
class Repertoire
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(name="userproprietaire", type="integer")
     */
    private $userproprietaire;


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
     * Set userproprietaire
     *
     * @param integer $userproprietaire
     *
     * @return Repertoire
     */
    public function setUserproprietaire($userproprietaire)
    {
        $this->userproprietaire = $userproprietaire;

        return $this;
    }

    /**
     * Get userproprietaire
     *
     * @return int
     */
    public function getUserproprietaire()
    {
        return $this->userproprietaire;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return Repertoire
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
