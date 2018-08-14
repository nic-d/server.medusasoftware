<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 17:49
 */

namespace Install\Service;

use Install\Entity\Install;
use Doctrine\ORM\EntityManagerInterface;
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
}