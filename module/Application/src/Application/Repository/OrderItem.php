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

    public function removeBySessionAndListingId($sessionId, $listingId)
    {
        $ois = $this->findBy(
            array('sessionId' => $sessionId, 'itemId' => $listingId),
            array('sessionId' => 'ASC')
        );

        foreach ($ois as $one) {
            $this->getEntityManager()->remove($one);
        }

        $this->getEntityManager()->flush();
    }
}
