<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contraintes
 *
 * @ORM\Table(name="contraintes")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\ContraintesRepository")
 */
class Contraintes
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Sujet", cascade={"persist"}, inversedBy="contraintes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;


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
     * Set description
     *
     * @param string $description
     *
     * @return Contraintes
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
     * Set sujet
     *
     * @param \GDSS\PlatformBundle\Entity\Sujet $sujet
     *
     * @return Contraintes
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
}
