<?php


namespace GDSS\PlatformBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ParticipantInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PlatformBundle\Entity\Decideurs", mappedBy="user", cascade={"remove"})
     */
    private $decideurs;

    /**
     * @ORM\OneToMany(targetEntity="GDSS\PhasesBundle\Entity\Reaction", mappedBy="user", cascade={"remove"})
     */
    private $reac;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add decideur
     *
     * @param \GDSS\PlatformBundle\Entity\Decideurs $decideur
     *
     * @return User
     */
    public function addDecideur(\GDSS\PlatformBundle\Entity\Decideurs $decideur)
    {
        $this->decideurs[] = $decideur;

        return $this;
    }

    /**
     * Remove decideur
     *
     * @param \GDSS\PlatformBundle\Entity\Decideurs $decideur
     */
    public function removeDecideur(\GDSS\PlatformBundle\Entity\Decideurs $decideur)
    {
        $this->decideurs->removeElement($decideur);
    }

    /**
     * Get decideurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDecideurs()
    {
        return $this->decideurs;
    }

    /**
     * Add reac
     *
     * @param \GDSS\PhasesBundle\Entity\Reaction $reac
     *
     * @return User
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
}
