<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 20:58
 */

namespace Install\Repository;

use Carbon\Carbon;
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
    /** @var QueryBuilder $queryBuilder */
    private $queryBuilder;

    /**
     * @param string $query
     * @return array
     */
    public function search(string $query)
    {
        // Set the query builder
        $this->queryBuilder = $this->getEntityManager()
            ->createQueryBuilder();

        $this->queryBuilder
            ->select('i')
            ->from(Install::class, 'i');

        // If returns bool, then we didn't add a time range WHERE statement
        if (!is_null($this->buildTimeRange($query))) {
            $this->queryBuilder
                ->where('i.domain LIKE :domain')
                ->orWhere('i.ipAddress LIKE :ip')
                ->setParameters([
                    'domain' => '%' . $query . '%',
                    'ip' => '%' . $query . '%',
                ]);
        }

        $query = $this->queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /**
     * @return int|mixed
     */
    public function countRows(): int
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb
            ->select('count(i.id)')
            ->from(Install::class, 'i');

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

    /**
     * @param string $query
     * @return mixed
     */
    private function buildTimeRange(string $query)
    {
        switch (trim(strtolower($query))) {
            case 'today':
                return $this->buildToday();
                break;

            case 'yesterday':
                return $this->buildYesterday();
                break;

            case 'this week':
                return $this->buildThisWeek();
                break;

            case 'this month':
                return $this->buildThisMonth();
                break;

            case 'this year':
                return $this->buildThisYear();
                break;

            case 'last week':
                return $this->buildLastWeek();
                break;

            case 'last month':
                return $this->buildLastMonth();
                break;

            case 'last year':
                return $this->buildLastYear();
                break;
        }

        return false;
    }

    private function buildToday()
    {
        $startOfYear = Carbon::now()->startOfDay();
        $endOfYear   = Carbon::now()->endOfDay();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }

    private function buildYesterday()
    {
        $startOfYear = Carbon::now()->subDay()->startOfDay();
        $endOfYear   = Carbon::now()->subDay()->endOfDay();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }

    private function buildThisWeek()
    {
        /** @var \DateTime $datetime */
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfWeek,
            'end'   => $endOfWeek,
        ]);
    }

    private function buildThisMonth()
    {
        /** @var \DateTime $datetime */
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfMonth,
            'end'   => $endOfMonth,
        ]);
    }

    private function buildThisYear()
    {
        /** @var \DateTime $datetime */
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear   = Carbon::now()->endOfYear();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }

    private function buildLastWeek()
    {
        /** @var \DateTime $datetime */
        $startOfWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfWeek   = Carbon::now()->subWeek()->endOfWeek();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfWeek,
            'end'   => $endOfWeek,
        ]);
    }

    private function buildLastMonth()
    {
        /** @var \DateTime $datetime */
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfMonth   = Carbon::now()->subMonth()->endOfMonth();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfMonth,
            'end'   => $endOfMonth,
        ]);
    }

    private function buildLastYear()
    {
        /** @var \DateTime $datetime */
        $startOfYear = Carbon::now()->subYear()->startOfYear();
        $endOfYear   = Carbon::now()->subYear()->endOfYear();

        $this->queryBuilder->where('i.timestamp >= :start');
        $this->queryBuilder->andWhere('i.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }
}