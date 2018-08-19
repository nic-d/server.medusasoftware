<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 16:48
 */

namespace Product\Controller;

use Zend\View\Model\JsonModel;
use Product\Service\VersionApiService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class VersionApiController
 * @package Product\Controller
 */
class VersionApiController extends AbstractActionController
{
    /** @var VersionApiService $versionApiService */
    private $versionApiService;

    /**
     * VersionApiController constructor.
     * @param VersionApiService $versionApiService
     */
    public function __construct(VersionApiService $versionApiService)
    {
        $this->versionApiService = $versionApiService;
    }

    /**
     * @return JsonModel|\Zend\View\Model\ViewModel
     */
    public function isUpToDateAction()
    {
        /** @var $jsonModel $jsonModel */
        $jsonModel = new JsonModel();

        /** @var string|null $productHash */
        $productHash = $this->params()->fromRoute('hash');
        $productVersion = $this->params()->fromPost('version');

        try {
            $isUpToDate = $this->versionApiService->isProductUpToDate($productHash, $productVersion);
        } catch (\Exception $e) {
            return $this->getResponse()->setStatusCode(404);
        }

        return $jsonModel->setVariables($isUpToDate);
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function downloadAction()
    {
        /** @var $jsonModel $jsonModel */
        $jsonModel = new JsonModel();

        /** @var string|null $productHash */
        $productHash = $this->params()->fromRoute('hash');
        $version = $this->params()->fromPost('version');
        $licenseCode = $this->params()->fromPost('license');

        // Validate the incoming data
        $this->versionApiService
            ->getDownloadInputFilter()
            ->setData($this->params()->fromPost());

        // If the incoming data isn't valid, just ignore this request...
        if (!$this->versionApiService->getDownloadInputFilter()->isValid()) {
            return $this->getResponse()->setStatusCode(400);
        }

        try {
            $license = $this->versionApiService->getLicenseService()->getLicense($licenseCode, 'code');
        } catch (\Exception $e) {
            return $this->getResponse()->setStatusCode(400);
        }

        // Check that this license owns this product
        if ($license->getProduct()->getHash() !== $productHash) {
            return $this->getResponse()->setStatusCode(400);
        }

        // Get the latest version
        try {
            $latestVersion = $this->versionApiService->getVersion($productHash, $version);
        } catch (\Exception $e) {
            return $this->getResponse()->setStatusCode(400);
        }

        return $jsonModel->setVariables([
            'package_url' => $latestVersion->getPackagedAppUrl(),
        ]);
    }
}