<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:49
 */

namespace Install\Service;

use Install\Entity\Install;
use License\Entity\License;
use Product\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class InstallService
 * @package Install\Service
 */
class InstallService
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /**
     * InstallService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormElementManager $formElementManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormElementManager $formElementManager
    )
    {
        $this->entityManager = $entityManager;
        $this->formElementManager = $formElementManager;
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function getInstallations(int $page = 1)
    {
        $limit = 15;
        $offset = ($page === 0) ? 0 : ($page - 1) * $limit;

        return $this->entityManager
            ->getRepository(Install::class)
            ->getPaginatedInstalls($offset, $limit);
    }

    /**
     * @param string $hash
     * @return Install
     * @throws \Exception
     */
    public function getInstallation(string $hash): Install
    {
        /** @var Install $installation */
        $installation = $this->entityManager
            ->getRepository(Install::class)
            ->findOneBy([
                'hash' => $hash,
            ]);

        if (is_null($installation)) {
            throw new \Exception('not found');
        }

        return $installation;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function saveInstall(array $data): bool
    {
        /** @var Install $install */
        $install = new Install();

        /** @var DoctrineObject $hydrator */
        $hydrator = new DoctrineObject($this->entityManager, true);
        $hydrator->hydrate($data, $install);

        // Get the license & product
        $license = $this->getLicense($data['license']);
        $product = $this->getProduct($data['product']);

        $install->setLicense($license);
        $install->setProduct($product);

        $this->entityManager->persist($install);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param string $code
     * @return null|object
     * @throws \Exception
     */
    private function getLicense(string $code)
    {
        $license = $this->entityManager
            ->getRepository(License::class)
            ->findOneBy([
                'licenseCode' => $code,
            ]);

        if (is_null($license)) {
            throw new \Exception('License not found');
        }

        return $license;
    }

    /**
     * @param string $hash
     * @return null|object
     * @throws \Exception
     */
    private function getProduct(string $hash)
    {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy([
                'hash' => $hash,
            ]);

        if (is_null($product)) {
            throw new \Exception('Product not found');
        }

        return $product;
    }
}