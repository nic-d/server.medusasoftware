<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 22:54
 */

namespace Product\Repository;

use Product\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository
 * @package Product\Repository
 */
class ProductRepository extends EntityRepository
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
            ->select('count(p.id)')
            ->from(Product::class, 'p');

        $query = $qb->getQuery();

        try {
            $result = $query->getSingleScalarResult();
        } catch (\Exception $e) {
            return 0;
        }

        return $result;
    }
}