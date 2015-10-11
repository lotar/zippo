<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class OrderItem
 * @package Application\Repository
 */
class OrderItem extends EntityRepository
{
    /**
     * @param $sessionId
     * @return \Application\Document\OrderItem[]
     */
    public function getBySessionId($sessionId)
    {
        return $this->findBy(
            array('sessionId' => $sessionId),
            array('id' => 'ASC')
        );
    }
}
