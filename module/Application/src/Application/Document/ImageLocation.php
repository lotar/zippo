<?php

namespace Application\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="images")
 * @ORM\Entity
 *
 * Class ImageLocation
 * @package Application\Document
 */
class ImageLocation extends Base
{
    /**
     * @ORM\Column(name="userId", type="bigint", nullable=false)
     * @var int
     */
    private $userId;

    /**
     * @ORM\Column(name="url", type="string", nullable=false, length=800)
     * @var string
     */
    private $url;

    /**
     * @param User $user
     * @param $url
     */
    public function __construct(User $user, $url)
    {
        $this->setUserId($user->getId());
        $this->setUrl($url);
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
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
