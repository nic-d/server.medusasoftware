<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Install\Service\InstallService;
use License\Service\LicenseService;
use Product\Service\ProductService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{
    /** @var InstallService $installService */
    private $installService;

    /** @var LicenseService $licenseService */
    private $licenseService;

    /** @var ProductService $productService */
    private $productService;

    /**
     * IndexController constructor.
     * @param InstallService $installService
     * @param LicenseService $licenseService
     * @param ProductService $productService
     */
    public function __construct(
        InstallService $installService,
        LicenseService $licenseService,
        ProductService $productService
    )
    {
        $this->installService = $installService;
        $this->licenseService = $licenseService;
        $this->productService = $productService;
    }

    /**
     * @return ViewModel
     */
    public function homeAction()
    {
        $results = [
            'licenses' => $this->licenseService->countLicenses(),
            'products' => $this->productService->countProducts(),
            'installations' => $this->installService->countInstallations(),
        ];

        return new ViewModel([
            'results' => $results,
        ]);
    }
}