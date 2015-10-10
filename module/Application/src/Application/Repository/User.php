<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\Select;

/**
 * Class User
 * @package Application\Repository
 */
class User extends EntityRepository
{
    /**
     * @param array $userIds
     * @return \Application\Document\User[]
     */
    public function getMapByIds(array $userIds)
    {
        $qb = $this->createQueryBuilder('u');
        $results = $qb->add('select', new Select(array('u')))
            ->add('where', $qb->expr()->orX(
                $qb->expr()->in('u.id', '?1')
            ))
            ->setParameter(1, array_unique($userIds))
            ->getQuery()
            ->execute();

        $out = array();
        /* @var \Application\Document\User[] $one */
        foreach ($results as $one) {
            $out[$one->getId()] = $one;
        }

        return $out;
    }
}
