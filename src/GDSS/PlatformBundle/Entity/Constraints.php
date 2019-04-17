<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Constraints
 *
 * @ORM\Table(name="constraints")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\ConstraintsRepository")
 */
class Constraints
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
     * @ORM\Column(name="thinklet", type="string", length=255)
     */
    private $thinklet;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Problem", inversedBy="constraints")
     * @ORM\JoinColumn(nullable=false)
     */
    private $problem;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
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
     * @return Constraints
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
     * Set problem
     *
     * @param \GDSS\PlatformBundle\Entity\Problem $problem
     *
     * @return Constraints
     */
    public function setProblem(\GDSS\PlatformBundle\Entity\Problem $problem)
    {
        $this->problem = $problem;

        return $this;
    }

    /**
     * Get problem
     *
     * @return \GDSS\PlatformBundle\Entity\Problem
     */
    public function getProblem()
    {
        return $this->problem;
    }



    /**
     * Set thinklet
     *
     * @param string $thinklet
     *
     * @return Constraints
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
}
