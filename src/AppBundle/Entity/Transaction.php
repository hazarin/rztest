<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Table(indexes={@ORM\Index(name="created_at_idx", columns={"created_at"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Transaction
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var float
     *
     * @ORM\Column(name="summ", type="float")
     */
    private $summ;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Total", inversedBy="transactions")
     * @ORM\JoinColumn(name="total_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $total;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set summ
     *
     * @param float $summ
     *
     * @return Transaction
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
     * Set total
     *
     * @param \AppBundle\Entity\Total $total
     *
     * @return Transaction
     */
    public function setTotal(\AppBundle\Entity\Total $total = null)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return \AppBundle\Entity\Total
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     *
     * @ORM\PrePersist
     */
    public function setTimestamp()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

}
