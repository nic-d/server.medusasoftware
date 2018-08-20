<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:54
 */

namespace Activity\Repository;

use Activity\Entity\Activity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ActivityRepository
 * @package Activity\Repository
 */
class ActivityRepository extends EntityRepository
{
    /**
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    public function paginateActivity(int $offset = 0, int $limit = 15)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from(Activity::class, 'a')
            ->orderBy('a.timestamp', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query);

        return $paginator;
    }
}