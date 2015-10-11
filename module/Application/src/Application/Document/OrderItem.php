<?php

namespace Application\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="order_items")
 * @ORM\Entity(repositoryClass="Application\Repository\OrderItem")
 *
 * Class OrderItem
 * @package Application\Document
 */
class OrderItem extends Base
{
    /**
     * @ORM\Column(name="sessionId", type="string", nullable=false)
     * @var string
     */
    private $sessionId;

    /**
     * @ORM\Column(name="itemId", type="integer", nullable=false, length=800)
     * @var int
     */
    private $itemId;

    /**
     * @param $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param $itemId
     * @return $this
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getItemId()
    {
        return $this->itemId;
    }
}
