<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Total
 *
 * @ORM\Table(name="total")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TotalRepository")
 */
class Total
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
     * @var float
     *
     * @ORM\Column(name="summ", type="float")
     */
    private $summ;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currency")
     */
    protected $currency;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Wallet", inversedBy="totals", cascade={"persist"})
     * @ORM\JoinColumn(name="wallet_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $wallet;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="total", cascade={"all"})
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Add transaction
     *
     * @param \AppBundle\Entity\Transaction $transaction
     * @return Total
     */
    public function addTransaction(\AppBundle\Entity\Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \AppBundle\Entity\Transaction $transactions
     */
    public function removeTransaction(\AppBundle\Entity\Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Set wallet
     *
     * @param integer $wallet
     * @return Total
     */
    public function setWallet($wallet)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get project
     *
     * @return integer
     */
    public function getWallet()
    {
        return $this->wallet;
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
     * Set summ
     *
     * @param float $summ
     *
     * @return Total
     */
    public function setSumm($summ)
    {
        $this->summ = $summ;

        return $this;
    }

    /**
     * Get summ
     *
     * @return float
     */
    public function getSumm()
    {
        return $this->summ;
    }

    /**
     * Set currency
     *
     * @param \AppBundle\Entity\Currency $currency
     *
     * @return Total
     */
    public function setCurrency(\AppBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \AppBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

}
