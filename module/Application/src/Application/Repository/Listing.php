<?php

namespace Application\Repository;

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
}
