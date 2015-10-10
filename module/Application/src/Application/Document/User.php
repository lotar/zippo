<?php

namespace Application\Document;

use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;

// TODO: GOOGLE api for lat/long

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Application\Repository\User")
 *
 * Class User
 * @package Application\Document
 */
class User extends Base implements UserInterface
{
    /**
     * @ORM\Column(name="email", type="string", nullable=false, length=800)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", nullable=false, length=800)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="name", type="string", nullable=false, length=100)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="phone", type="string", nullable=true, length=30)
     * @var string
     */
    private $phone;

    /**
     * @ORM\Column(name="address", type="string", nullable=true, length=500)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(name="latitude", type="float", nullable=true)
     * @var float
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="float", nullable=true)
     * @var float
     */
    private $longitude;

    private $state;

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return (string) $this->email;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    /**
     * @param $phoneNumber
     * @return $this
     */
    public function setPhone($phoneNumber)
    {
        $this->phone = $phoneNumber;
        return $this;
    }

    /**
     * @return float
     */
    public function getPhone()
    {
        return (string) $this->phone;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return (string) $this->address;
    }

    /**
     * @return bool
     */
    public function areCoordinatesSet()
    {
        return $this->getLatitude() !== null && $this->getLongitude() !== null;
    }

    /**
     * @param $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @inheritdoc
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @inheritdoc
     */
    public function setDisplayName($displayName)
    {
        $this->name = $displayName;
    }

    /**
     * @inheritdoc
     */
    public function getDisplayName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setUsername($username)
    {
        throw new \RuntimeException("Nope");
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->getEmail();
    }
}
