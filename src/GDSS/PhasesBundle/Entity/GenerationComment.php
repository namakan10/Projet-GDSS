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
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\GenerationContribution", inversedBy="comment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contribution;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phase;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationCommentReply", mappedBy="comment", cascade={"remove"})
     */
    private $reply;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;


    /**
     * @var string
     *
     * @ORM\Column(name="reaction", type="string", length=255)
     */
    private $reaction;


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

    /**
     * Set reaction
     *
     * @param string $reaction
     *
     * @return GenerationComment
     */
    public function setReaction($reaction)
    {
        $this->reaction = $reaction;

        return $this;
    }

    /**
     * Get reaction
     *
     * @return string
     */
    public function getReaction()
    {
        return $this->reaction;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reply = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reply
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationCommentReply $reply
     *
     * @return GenerationComment
     */
    public function addReply(\GDSS\PhasesBundle\Entity\GenerationCommentReply $reply)
    {
        $this->reply[] = $reply;

        return $this;
    }

    /**
     * Remove reply
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationCommentReply $reply
     */
    public function removeReply(\GDSS\PhasesBundle\Entity\GenerationCommentReply $reply)
    {
        $this->reply->removeElement($reply);
    }

    /**
     * Get reply
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set phase
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phase
     *
     * @return GenerationComment
     */
    public function setPhase(\GDSS\PhasesBundle\Entity\Phase $phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return \GDSS\PhasesBundle\Entity\Phase
     */
    public function getPhase()
    {
        return $this->phase;
    }
}
