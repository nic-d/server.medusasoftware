<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 22:10
 */

namespace License\Service;

use License\Entity\License;
use License\Form\LicenseAddForm;
use Envato\Service\EnvatoService;
use License\Form\LicenseEditForm;
use License\Form\LicenseDeleteForm;
use License\Form\LicenseVerifyForm;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class LicenseService
 * @package License\Service
 */
class LicenseService
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /** @var EnvatoService $envatoService */
    private $envatoService;

    /**
     * LicenseService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormElementManager $formElementManager
     * @param EnvatoService $envatoService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormElementManager $formElementManager,
        EnvatoService $envatoService
    )
    {
        $this->entityManager = $entityManager;
        $this->formElementManager = $formElementManager;
        $this->envatoService = $envatoService;
    }

    /**
     * @return array
     */
    public function getLicenses(): array
    {
        /** @var array $licenses */
        $licenses = $this->entityManager
            ->getRepository(License::class)
            ->findAll();

        return $licenses;
    }

    /**
     * @param $id
     * @param string $getBy
     * @return License
     * @throws \Exception
     */
    public function getLicense($id, string $getBy = 'id'): License
    {
        switch ($getBy) {
            case 'id':
                return $this->getLicenseById($id);
                break;

            case 'code':
                return $this->getLicenseByCode($id);
                break;

            default:
                throw new \Exception('Unknown getBy ' . $getBy);
        }
    }

    /**
     * @param int $id
     * @return License
     * @throws \Exception
     */
    private function getLicenseById(int $id): License
    {
        /** @var License $license */
        $license = $this->entityManager
            ->getRepository(License::class)
            ->find($id);

        if (is_null($license)) {
            throw new \Exception('not found');
        }

        return $license;
    }

    /**
     * @param string $code
     * @return License
     * @throws \Exception
     */
    private function getLicenseByCode(string $code): License
    {
        /** @var License $license */
        $license = $this->entityManager
            ->getRepository(License::class)
            ->findOneBy([
                'licenseCode' => $code,
            ]);

        if (is_null($license)) {
            throw new \Exception('not found');
        }

        return $license;
    }

    /**
     * @param LicenseVerifyForm $licenseVerifyForm
     * @return bool
     * @throws \ErrorException
     */
    public function verifyLicenseCodeUsingForm(LicenseVerifyForm $licenseVerifyForm): bool
    {
        /** @var string $code */
        $code = $licenseVerifyForm->get('code')->getValue();
        return $this->verifyLicenseCode($code);
    }

    /**
     * @param string $code
     * @param string $ip
     * @param string $domain
     * @return bool
     * @throws \ErrorException
     */
    public function verifyLicenseCode(string $code, string $ip = '', string $domain = ''): bool
    {
        // First check if it exists in the DB
        $license = $this->entityManager
            ->getRepository(License::class)
            ->findOneBy([
                'licenseCode' => $code,
            ]);

        // The license exists in our database
        if (!is_null($license)) {
            // Verify the constraints
            return $this->verifyLicenseConstraints($license, $ip, $domain);
        }

        // Authenticate with our clientSecret key and verify the license code with envato
        $this->envatoService->authenticatePersonal();
        $result = $this->envatoService->api('me')->sale($code);

        if (isset($result['error'])) {
            return false;
        }

        // The license code exists with Envato, so save it in our DB
        $this->saveEnvatoLicenseCode($code, $result, $ip, $domain);
        return true;
    }

    /**
     * @param License $license
     * @param string $ip
     * @param string $domain
     * @return bool
     */
    private function verifyLicenseConstraints(License $license, string $ip = '', string $domain = ''): bool
    {
        // Check that the license isn't blocked
        if ($license->getIsBlocked()) {
            return false;
        }

        // Verify the IP if it's restricted by IP
        if (!empty($ip) && (!is_null($license->getLicensedIp()) || empty($license->getLicensedIp()))) {
            if ($ip !== $license->getLicensedIp()) {
                return false;
            }
        }

        // Verify domain IP if it's restricted by domain
        if (!empty($domain) && (!is_null($license->getLicensedDomain()) || empty($license->getLicensedDomain()))) {
            if ($domain !== $license->getLicensedDomain()) {
                return false;
            }
        }

        // Verify the expiration date
        if (!is_null($license->getLicenseExpirationDate()) && $license->getLicenseExpirationDate() >= time()) {
            return false;
        }

        // TODO: Verify the install limit
//        if ($license->getInstallLimit() !== 0) {
//            return false;
//        }

        return true;
    }

    /**
     * @param string $licenseCode
     * @param array $licenseData
     * @param string $ip
     * @param string $domain
     * @return License
     */
    private function saveEnvatoLicenseCode(
        string $licenseCode,
        array $licenseData,
        string $ip = '',
        string $domain = ''
    ): License
    {
        /** @var License $license */
        $license = new License();
        $license->setLicenseCode($licenseCode);
        $license->setValidForEnvatoUsername($licenseData['buyer']);

        if (!empty($ip)) {
            $license->setLicensedIp($ip);
        }

        if (!empty($domain)) {
            $license->setLicensedDomain($domain);
        }

        $this->entityManager->persist($license);
        $this->entityManager->flush();

        return $license;
    }

    /**
     * @param LicenseAddForm $licenseAddForm
     * @return License
     */
    public function addLicense(LicenseAddForm $licenseAddForm): License
    {
        /** @var License $license */
        $license = $licenseAddForm->getObject();

        $this->entityManager->persist($license);
        $this->entityManager->flush();

        return $license;
    }

    /**
     * @param LicenseEditForm $licenseEditForm
     * @return bool
     */
    public function editLicense(LicenseEditForm $licenseEditForm): bool
    {
        /** @var License $license */
        $license = $licenseEditForm->getObject();

        if (empty($licenseEditForm->get('licenseExpirationDate')->getValue()) ||
            is_null($licenseEditForm->get('licenseExpirationDate')->getValue())) {
            $license->setLicenseExpirationDate(null);
        }

        $this->entityManager->persist($license);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param LicenseDeleteForm $licenseDeleteForm
     * @return bool
     */
    public function deleteLicense(LicenseDeleteForm $licenseDeleteForm): bool
    {
        /** @var License $license */
        $license = $licenseDeleteForm->getObject();

        $this->entityManager->remove($license);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return LicenseAddForm
     */
    public function prepareAddForm(): LicenseAddForm
    {
        /** @var LicenseAddForm $form */
        $form = $this->formElementManager->get(LicenseAddForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind(new License());

        return $form;
    }

    /**
     * @param License $license
     * @return LicenseEditForm
     */
    public function prepareEditForm(License $license): LicenseEditForm
    {
        /** @var LicenseEditForm $form */
        $form = $this->formElementManager->get(LicenseEditForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($license);

        return $form;
    }

    /**
     * @param License $license
     * @return LicenseDeleteForm
     */
    public function prepareDeleteForm(License $license): LicenseDeleteForm
    {
        /** @var LicenseDeleteForm $form */
        $form = $this->formElementManager->get(LicenseDeleteForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($license);

        // Set the action attr
        $form->setAttribute('action', '/licenses/' . $license->getLicenseCode() . '/delete');

        return $form;
    }

    /**
     * @return LicenseVerifyForm
     */
    public function prepareVerifyForm(): LicenseVerifyForm
    {
        /** @var LicenseVerifyForm $form */
        $form = $this->formElementManager->get(LicenseVerifyForm::class);
        return $form;
    }
}