<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:54
 */

namespace Activity\Repository;

use Carbon\Carbon;
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
            ->select('a')
            ->from(Activity::class, 'a');

        // If returns bool, then we didn't add a time range WHERE statement
        if (!is_null($this->buildTimeRange($query))) {
            $this->queryBuilder
                ->where('a.logMessage LIKE :message')
                ->orWhere('a.id LIKE :id')
                ->orWhere('a.ipAddress LIKE :ip')
                ->setParameters([
                    'message' => '%' . $query . '%',
                    'id' => '%' . $query . '%',
                    'ip' => '%' . $query . '%',
                ]);
        }

        $query = $this->queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

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
        $startOfDate = Carbon::now()->startOfDay();
        $endOfDay = Carbon::now()->endOfDay();

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');
        $this->queryBuilder->setParameters([
            'start' => $startOfDate,
            'end'   => $endOfDay,
        ]);
    }

    private function buildYesterday()
    {
        $startOfDate = Carbon::now()->subDay()->startOfDay();
        $endOfDay = Carbon::now()->subDay()->endOfDay();

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');
        $this->queryBuilder->setParameters([
            'start' => $startOfDate,
            'end'   => $endOfDay,
        ]);
    }

    private function buildThisWeek()
    {
        /** @var \DateTime $datetime */
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

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

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

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

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

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

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

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

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

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

        $this->queryBuilder->where('a.timestamp >= :start');
        $this->queryBuilder->andWhere('a.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }
}