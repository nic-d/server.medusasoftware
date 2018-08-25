<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 22:55
 */

namespace License\Repository;

use Carbon\Carbon;
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
            ->select('l')
            ->from(License::class, 'l');

        // If returns bool, then we didn't add a time range WHERE statement
        if (!is_null($this->buildTimeRange($query))) {
            $this->queryBuilder
                ->where('l.licensedDomain LIKE :domain')
                ->orWhere('l.licenseCode LIKE :key')
                ->orWhere('l.licensedIp LIKE :ip')
                ->setParameters([
                    'key' => '%' . $query . '%',
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
        /** @var \DateTime $datetime */
        $datetime = Carbon::now();

        $this->queryBuilder->where('l.timestamp = :date');
        $this->queryBuilder->setParameter('date', $datetime);
    }

    private function buildYesterday()
    {
        /** @var \DateTime $datetime */
        $datetime = Carbon::now()->subDay(1);

        $this->queryBuilder->where('l.timestamp = :date');
        $this->queryBuilder->setParameter('date', $datetime);
    }

    private function buildThisWeek()
    {
        /** @var \DateTime $datetime */
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

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

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

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

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

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

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

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

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

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

        $this->queryBuilder->where('l.timestamp >= :start');
        $this->queryBuilder->andWhere('l.timestamp <= :end');

        $this->queryBuilder->setParameters([
            'start' => $startOfYear,
            'end'   => $endOfYear,
        ]);
    }
}