<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Currency
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Currency
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
     * @ORM\Column(name="numCode", type="string", length=3, unique=true)
     */
    private $numCode;

    /**
     * @var string
     *
     * @ORM\Column(name="charCode", type="string", length=3, unique=true)
     */
    private $charCode;

    /**
     * @var int
     *
     * @ORM\Column(name="nominal", type="integer")
     */
    private $nominal;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="date")
     */
    private $updatedAt;


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
     * Set numCode
     *
     * @param string $numCode
     *
     * @return Currency
     */
    public function setNumCode($numCode)
    {
        $this->numCode = $numCode;

        return $this;
    }

    /**
     * Get numCode
     *
     * @return string
     */
    public function getNumCode()
    {
        return $this->numCode;
    }

    /**
     * Set charCode
     *
     * @param string $charCode
     *
     * @return Currency
     */
    public function setCharCode($charCode)
    {
        $this->charCode = $charCode;

        return $this;
    }

    /**
     * Get charCode
     *
     * @return string
     */
    public function getCharCode()
    {
        return $this->charCode;
    }

    /**
     * Set nominal
     *
     * @param integer $nominal
     *
     * @return Currency
     */
    public function setNominal($nominal)
    {
        $this->nominal = $nominal;

        return $this;
    }

    /**
     * Get nominal
     *
     * @return int
     */
    public function getNominal()
    {
        return $this->nominal;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return Currency
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Currency
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamp()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

}
