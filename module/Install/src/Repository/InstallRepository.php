<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 20:58
 */

namespace Install\Repository;

use Install\Entity\Install;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class InstallRepository
 * @package Install\Repository
 */
class InstallRepository extends EntityRepository
{
    /**
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    public function getPaginatedInstalls($offset = 0, $limit = 15)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb
            ->select('i')
            ->from(Install::class, 'i')
            ->orderBy('i.timestamp', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $qb->getQuery();
        $paginator = new Paginator($query);

        return $paginator;
    }
}