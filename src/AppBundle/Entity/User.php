<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Wallet", mappedBy="user")
     */
    protected $wallet;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set wallet
     *
     * @param \AppBundle\Entity\Wallet $wallet
     *
     * @return User
     */
    public function setWallet(\AppBundle\Entity\Wallet $wallet = null)
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get wallet
     *
     * @return \AppBundle\Entity\Wallet
     */
    public function getWallet()
    {
        return $this->wallet;
    }

}
