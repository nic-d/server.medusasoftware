<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 15:16
 */

namespace License\Controller;

use Zend\View\Model\JsonModel;
use License\Service\LicenseService;
use License\Filter\LicenseInputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * Class LicenseApiController
 * @package License\Controller
 */
class LicenseApiController extends AbstractRestfulController
{
    /** @var LicenseService $licenseService */
    private $licenseService;

    /** @var LicenseInputFilter $licenseInputFilter */
    private $licenseInputFilter;

    /**
     * LicenseApiController constructor.
     * @param LicenseService $licenseService
     * @param LicenseInputFilter $licenseInputFilter
     */
    public function __construct(
        LicenseService $licenseService,
        LicenseInputFilter $licenseInputFilter
    )
    {
        $this->licenseService = $licenseService;
        $this->licenseInputFilter = $licenseInputFilter;
    }

    /**
     * @param mixed $data
     * @return mixed|\Zend\View\Model\ViewModel
     */
    public function create($data)
    {
        /** @var JsonModel $json */
        $json = new JsonModel();

        // Validate the $data array
        $this->licenseInputFilter->setData($data);

        if (!$this->licenseInputFilter->isValid()) {
            $this->response->setStatusCode(400);
            return $json->setVariables([
                'error' => $this->licenseInputFilter->getMessages(),
            ]);
        }

        try {
            $isValid = $this->licenseService->verifyLicenseCode(
                $data['licenseCode'],
                $data['ip'],
                $data['domain']
            );
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

        if ($isValid) {
            return $json->setVariables([
                'content' => [
                    'message' => 'License is valid',
                ],
            ]);
        }

        // By default, the license isn't valid
        $this->response->setStatusCode(400);
        return $json->setVariables([
            'error' => [
                'licenseCode' => [
                    'message' => 'License code is invalid',
                ],
            ],
        ]);
    }
}