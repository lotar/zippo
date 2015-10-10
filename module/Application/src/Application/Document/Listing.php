<?php

namespace Application\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="listings")
 * @ORM\Entity(repositoryClass="Application\Repository\Listing")
 *
 * Class Listing
 * @package Application\Document
 */
class Listing extends Base
{
    /**
     * @ORM\Column(name="userId", type="bigint", nullable=false)
     * @var int
     */
    private $userId;

    /**
     * @ORM\Column(name="name", type="string", nullable=false, length=800)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="string", nullable=true, length=800)
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(name="qty", type="integer", nullable=false)
     * @var int
     */
    private $quantity;

    /**
     * @ORM\Column(name="price", type="float", nullable=false)
     * @var float
     */
    private $price;

    /**
     * @param User $user
     * @param $name
     */
    public function __construct(User $user, $name)
    {
        $this->setUserId($user->getId());
        $this->setName($name);
    }

    /**
     * @param $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return (float) $this->price;
    }
}
