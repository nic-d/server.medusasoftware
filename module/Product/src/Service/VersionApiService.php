<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 16:55
 */

namespace Product\Service;

use Product\Entity\Product;
use Product\Entity\Version;
use League\Flysystem\Filesystem;
use License\Service\LicenseService;
use Product\Filter\DownloadInputFilter;

/**
 * Class VersionApiService
 * @package Product\Service
 */
class VersionApiService
{
    /** @var ProductService $productService */
    private $productService;

    /** @var VersionService $versionService */
    private $versionService;

    /** @var LicenseService $licenseService */
    private $licenseService;

    /** @var DownloadInputFilter $downloadInputFilter */
    private $downloadInputFilter;

    /** @var Filesystem $filesystem */
    private $filesystem;

    /**
     * VersionApiService constructor.
     * @param ProductService $productService
     * @param VersionService $versionService
     * @param LicenseService $licenseService
     * @param DownloadInputFilter $downloadInputFilter
     * @param Filesystem $filesystem
     */
    public function __construct(
        ProductService $productService,
        VersionService $versionService,
        LicenseService $licenseService,
        DownloadInputFilter $downloadInputFilter,
        Filesystem $filesystem
    )
    {
        $this->productService = $productService;
        $this->versionService = $versionService;
        $this->downloadInputFilter = $downloadInputFilter;
        $this->licenseService = $licenseService;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $productHash
     * @param string $productVersion
     * @return array
     * @throws \Exception
     */
    public function isProductUpToDate(string $productHash, string $productVersion): array
    {
        /** @var Product $product */
        $product = $this->productService->getProduct($productHash, 'hash');

        /** @var Version $version */
        $version = $this->versionService->getLatestVersion($product);

        return [
            'latestVersion' => $version->getVersionNumber(),
            'isUpToDate' => version_compare($productVersion, $version->getVersionNumber(), '>='),
        ];
    }

    /**
     * @param string $productHash
     * @param string $productVersion
     * @return Version
     * @throws \Exception
     */
    public function getVersion(string $productHash, string $productVersion): Version
    {
        /** @var Product $product */
        $product = $this->productService->getProduct($productHash, 'hash');

        if ($productVersion === 'latest') {
            return $this->versionService->getLatestVersion($product);
        }
    }

    # ---------------------------------------------------------------
    # - GETTERS AND SETTERS
    # ---------------------------------------------------------------

    /**
     * @return DownloadInputFilter
     */
    public function getDownloadInputFilter(): DownloadInputFilter
    {
        return $this->downloadInputFilter;
    }

    /**
     * @param DownloadInputFilter $downloadInputFilter
     * @return $this
     */
    protected function setDownloadInputFilter(DownloadInputFilter $downloadInputFilter)
    {
        $this->downloadInputFilter = $downloadInputFilter;
        return $this;
    }

    /**
     * @return ProductService
     */
    public function getProductService(): ProductService
    {
        return $this->productService;
    }

    /**
     * @param ProductService $productService
     * @return $this
     */
    protected function setProductService(ProductService $productService)
    {
        $this->productService = $productService;
        return $this;
    }

    /**
     * @return VersionService
     */
    public function getVersionService(): VersionService
    {
        return $this->versionService;
    }

    /**
     * @param VersionService $versionService
     * @return $this
     */
    protected function setVersionService(VersionService $versionService)
    {
        $this->versionService = $versionService;
        return $this;
    }

    /**
     * @return LicenseService
     */
    public function getLicenseService(): LicenseService
    {
        return $this->licenseService;
    }

    /**
     * @param LicenseService $licenseService
     * @return $this
     */
    protected function setLicenseService(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        return $this;
    }
}