<?php

namespace Application\Repository;

use Application\Document\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Select;

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
        // TODO: implement
        return $this->findBy(
            array('deleted' => 0),
            array('name' => 'ASC')
//            $limit,
//            $offset
        );
    }

    /**
     * @param array $ids
     * @return \Application\Document\Listing[]
     */
    public function getByListingIds(array $ids)
    {
        $qb = $this->createQueryBuilder('u');
        $results = $qb->add('select', new Select(array('u')))
            ->add('where', $qb->expr()->orX(
                $qb->expr()->in('u.id', '?1')
            ))
            ->setParameter(1, array_unique($ids))
            ->getQuery()
            ->execute();

        $out = array();
        /* @var \Application\Document\Listing $one */
        foreach ($results as $one) {
            $out[$one->getId()] = $one;
        }

        return $out;
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
