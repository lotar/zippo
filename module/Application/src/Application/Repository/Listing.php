<?php

namespace Application\Repository;

use Application\Document\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class Listing
 * @package Application\Repository
 */
class Listing extends EntityRepository
{
    /**
     * @param $limit
     * @param $offset
     * @return \Application\Document\Listing[]
     */
    public function getPage($limit, $offset)
    {
        return $this->findBy(
            array(),
            array('name' => 'ASC'),
            $limit,
            $offset
        );
    }

    /**
     * @param User $user
     * @param $limit
     * @param $offset
     * @return \Application\Document\Listing[]
     */
    public function getUserPage(User $user, $limit, $offset)
    {
        return $this->findBy(
            array('userId' => $user->getId()),
            array('name' => 'ASC'),
            $limit,
            $offset
        );
    }
}
