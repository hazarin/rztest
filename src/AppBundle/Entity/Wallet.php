<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Wallet
 *
 * @ORM\Table(name="wallet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WalletRepository")
 */
class Wallet
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="wallet")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Total", mappedBy="wallet", cascade={"all"})
     */
    private $totals;

    public function __construct()
    {
        $this->totals = new ArrayCollection();
    }

    public function getTotals()
    {
        return $this->totals;
    }

    /**
     * Add total
     *
     * @param \AppBundle\Entity\Total $total
     * @return Wallet
     */
    public function addTotal(\AppBundle\Entity\Total $total)
    {
        $this->totals[] = $total;
        $total->setWallet($this);

        return $this;
    }

    /**
     * Remove total
     *
     * @param \AppBundle\Entity\Total $totals
     */
    public function removeTotal(\AppBundle\Entity\Total $totals)
    {
        $this->totals->removeElement($totals);
    }

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
     * @param \AppBundle\Entity\User $user
     *
     * @return Wallet
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getTotalByCurrency($currency)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('currency', $currency));

        return $this->getTotals()->matching($criteria)->first();
    }

}
