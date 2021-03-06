<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:48
 */

namespace Install\Controller;

use Zend\View\Model\JsonModel;
use Install\Service\InstallService;
use Install\Filter\InstallInputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * Class InstallApiController
 * @package Install\Controller
 */
class InstallApiController extends AbstractRestfulController
{
    /** @var InstallService $installService */
    private $installService;

    /** @var InstallInputFilter $installInputFilter */
    private $installInputFilter;

    /**
     * InstallApiController constructor.
     * @param InstallService $installService
     * @param InstallInputFilter $installInputFilter
     */
    public function __construct(
        InstallService $installService,
        InstallInputFilter $installInputFilter
    )
    {
        $this->installService = $installService;
        $this->installInputFilter = $installInputFilter;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    public function create($data)
    {
        /** @var JsonModel $json */
        $json = new JsonModel();

        // Validate the $data array
        $this->installInputFilter->setData($data);

        if (!$this->installInputFilter->isValid()) {
            $this->response->setStatusCode(400);
            return $json->setVariables([
                'error' => $this->installInputFilter->getMessages(),
            ]);
        }

        try {
            $this->installService->saveInstall($data);
        } catch (\Exception $e) {
            $this->response->setStatusCode(400);
            return $json->setVariables([
                'error' => [
                    'licenseCode' => [
                        'message' => 'License code is invalid',
                    ],
                ],
            ]);
        }

        return $this->response->setStatusCode(200);
    }
}