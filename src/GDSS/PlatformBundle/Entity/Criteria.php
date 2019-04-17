<?php

namespace GDSS\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Criteria
 *
 * @ORM\Table(name="criteria")
 * @ORM\Entity(repositoryClass="GDSS\PlatformBundle\Repository\CriteriaRepository")
 */
class Criteria
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
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\Problem", inversedBy="criteria")
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
     * @return Criteria
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
     * @return Criteria
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
}
