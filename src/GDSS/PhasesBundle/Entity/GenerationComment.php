<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationComment
 *
 * @ORM\Table(name="generation_comment")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationCommentRepository")
 */
class GenerationComment
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
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PlatformBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="GenerationContribution")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contribution;

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
     * Set comment
     *
     * @param string $comment
     *
     * @return GenerationComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return GenerationComment
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
     * Set contribution
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationContribution $contribution
     *
     * @return GenerationComment
     */
    public function setContribution(\GDSS\PhasesBundle\Entity\GenerationContribution $contribution)
    {
        $this->contribution = $contribution;

        return $this;
    }

    /**
     * Get contribution
     *
     * @return \GDSS\PhasesBundle\Entity\GenerationContribution
     */
    public function getContribution()
    {
        return $this->contribution;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return GenerationComment
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
}
