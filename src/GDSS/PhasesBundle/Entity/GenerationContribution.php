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
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Reaction", mappedBy="contrib", cascade={"remove"})
     */
    private $reac;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\GenerationComment", mappedBy="contribution", cascade={"remove"})
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="GDSS\PhasesBundle\Entity\Phase", inversedBy="contribution")
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
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color = null;


    /**
     * @var boolean
     *
     * @ORM\Column(name="selection", type="boolean")
     */
    private $selection = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="similar_ambiguous", type="boolean", nullable=true)
     */
    private $similar_ambiguous = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="liked", type="integer")
     */
    private $liked = 0;


    /**
     * @var integer
     *
     * @ORM\Column(name="dislike", type="integer")
     */
    private $dislike = 0;


    /**
     * @var integer
     *
     * @ORM\Column(name="interesting", type="integer")
     */
    private $interesting = 0;


    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name = "categorie", type="string", length=255)
     */
    private $categorie = 'void';

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

    /**
     * Set status
     *
     * @param string $status
     *
     * @return GenerationContribution
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set liked
     *
     * @param integer $liked
     *
     * @return GenerationContribution
     */
    public function setLiked($liked)
    {
        $this->liked = $liked;

        return $this;
    }

    /**
     * Get liked
     *
     * @return integer
     */
    public function getLiked()
    {
        return $this->liked;
    }

    /**
     * Set dislike
     *
     * @param integer $dislike
     *
     * @return GenerationContribution
     */
    public function setDislike($dislike)
    {
        $this->dislike = $dislike;

        return $this;
    }

    /**
     * Get dislike
     *
     * @return integer
     */
    public function getDislike()
    {
        return $this->dislike;
    }

    /**
     * Set interesting
     *
     * @param integer $interesting
     *
     * @return GenerationContribution
     */
    public function setInteresting($interesting)
    {
        $this->interesting = $interesting;

        return $this;
    }

    /**
     * Get interesting
     *
     * @return integer
     */
    public function getInteresting()
    {
        return $this->interesting;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reac = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reac
     *
     * @param \GDSS\PhasesBundle\Entity\Reaction $reac
     *
     * @return GenerationContribution
     */
    public function addReac(\GDSS\PhasesBundle\Entity\Reaction $reac)
    {
        $this->reac[] = $reac;

        return $this;
    }

    /**
     * Remove reac
     *
     * @param \GDSS\PhasesBundle\Entity\Reaction $reac
     */
    public function removeReac(\GDSS\PhasesBundle\Entity\Reaction $reac)
    {
        $this->reac->removeElement($reac);
    }

    /**
     * Get reac
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReac()
    {
        return $this->reac;
    }

    /**
     * Add comment
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationComment $comment
     *
     * @return GenerationContribution
     */
    public function addComment(\GDSS\PhasesBundle\Entity\GenerationComment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \GDSS\PhasesBundle\Entity\GenerationComment $comment
     */
    public function removeComment(\GDSS\PhasesBundle\Entity\GenerationComment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return GenerationContribution
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return GenerationContribution
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set phases
     *
     * @param \GDSS\PhasesBundle\Entity\Phase $phases
     *
     * @return GenerationContribution
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

    /**
     * Set selection
     *
     * @param boolean $selection
     *
     * @return GenerationContribution
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * Get selection
     *
     * @return boolean
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Set similarAmbiguous
     *
     * @param boolean $similarAmbiguous
     *
     * @return GenerationContribution
     */
    public function setSimilarAmbiguous($similarAmbiguous)
    {
        $this->similar_ambiguous = $similarAmbiguous;

        return $this;
    }

    /**
     * Get similarAmbiguous
     *
     * @return boolean
     */
    public function getSimilarAmbiguous()
    {
        return $this->similar_ambiguous;
    }
}
