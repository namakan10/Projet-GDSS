<?php

namespace GDSS\PhasesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenerationCommentReply
 *
 * @ORM\Table(name="generation_comment_reply")
 * @ORM\Entity(repositoryClass="GDSS\PhasesBundle\Repository\GenerationCommentReplyRepository")
 */
class GenerationCommentReply
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
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\GenerationComment", inversedBy="reply")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="reply", type="text")
     */
    private $reply;

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
     * @param string $reply
     *
     * @return GenerationCommentReply
     */
    public function setReply($reply)
    {
        $this->reply = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return string
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return GenerationCommentReply
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
     * Set user
     *
     * @param \GDSS\PlatformBundle\Entity\User $user
     *
     * @return GenerationCommentReply
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
     * Set comment
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationComment $comment
     *
     * @return GenerationCommentReply
     */
    public function setComment(\GDSS\PhasesBundle\Entity\GenerationComment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \GDSS\PhasesBundle\Entity\GenerationComment
     */
    public function getComment()
    {
        return $this->comment;
    }
}
