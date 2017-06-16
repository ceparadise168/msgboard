<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Account")
 */
class Account
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /*
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $username;

    /*
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $money;

    /**
     * Get Id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set Username
     * @param string $useranme
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get Password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Password
     * @param string $password
     * @return Account
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get Money
     * @return integer
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * Set Money
     * @param integer $money
     * @return Account
     */
     public function setMoney($money)
     {
        $this->money = $money;
        return $this;
     }
}
