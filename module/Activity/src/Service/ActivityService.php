<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:52
 */

namespace Activity\Service;

use Activity\Entity\Activity;
use Zend\EventManager\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ActivityService
 * @package Activity\Service
 */
class ActivityService
{
    /** @var EventManager $eventManager */
    private $eventManager;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var int $resultsPerPage */
    public $resultsPerPage = 15;

    /**
     * ActivityService constructor.
     * @param EntityManagerInterface $entityManager
     * @param EventManager $eventManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventManager $eventManager
    )
    {
        $this->entityManager = $entityManager;
        $this->eventManager = $eventManager;
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function search(string $query)
    {
        return $this->entityManager
            ->getRepository(Activity::class)
            ->search($query);
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function getActivity(int $page = 1): Paginator
    {
        $limit = $this->resultsPerPage;
        $offset = ($page === 0) ? 0 : ($page - 1) * $limit;

        return $this->entityManager
            ->getRepository(Activity::class)
            ->paginateActivity($offset, $limit);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function log(array $data)
    {
        /** @var Activity $activity */
        $activity = new Activity();
        $activity->setLogMessage($data['message']);
        $activity->setIpAddress($data['ipAddress']);

        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }
}