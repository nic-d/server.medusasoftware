<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 20/08/2018
 * Time: 12:51
 */

namespace Activity\Controller;

use Zend\View\Model\ViewModel;
use Activity\Service\ActivityService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ActivityController
 * @package Activity\Controller
 */
class ActivityController extends AbstractActionController
{
    /** @var ActivityService $activityService */
    private $activityService;

    /**
     * ActivityController constructor.
     * @param ActivityService $activityService
     */
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var int $page */
        $page = $this->params()->fromQuery('page', 1);

        // Get the logs
        $logs = $this->activityService->getActivity($page);
        $totalPages = ceil($logs->count() / $this->activityService->resultsPerPage);

        return new ViewModel([
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages'  => $totalPages
        ]);
    }

    /**
     * @return ViewModel
     */
    public function searchAction()
    {
        /** @var string|null $query */
        $query = $this->params()->fromQuery('q', null);

        if (is_null($query)) {
            return $this->notFoundAction();
        }

        try {
            $matchingResults = $this->activityService->search($query);
        } catch (\Exception $e) {
            $matchingResults = [];
        }

        return new ViewModel([
            'matchingResults' => $matchingResults,
        ]);
    }
}