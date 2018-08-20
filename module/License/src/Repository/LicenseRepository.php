<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 22:55
 */

namespace License\Repository;

use License\Entity\License;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class LicenseRepository
 * @package License\Repository
 */
class LicenseRepository extends EntityRepository
{
    /**
     * @return int|mixed
     */
    public function countRows(): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb
            ->select('count(l.id)')
            ->from(License::class, 'l');

        $query = $qb->getQuery();

        try {
            $result = $query->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0;
        }

        return $result;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    public function paginateLicenses(int $offset = 0, int $limit = 15)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $queryBuilder
            ->select('l')
            ->from(License::class, 'l')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);

        return $paginator;
    }
}