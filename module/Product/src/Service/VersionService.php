<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:42
 */

namespace Product\Service;

use Product\Entity\Version;
use Product\Form\VersionAddForm;
use League\Flysystem\Filesystem;
use Product\Form\VersionEditForm;
use Product\Form\VersionDeleteForm;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class VersionService
 * @package Product\Service
 */
class VersionService
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /** @var Filesystem $filesystem */
    private $filesystem;

    /**
     * VersionService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormElementManager $formElementManager
     * @param Filesystem $filesystem
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormElementManager $formElementManager,
        Filesystem $filesystem
    )
    {
        $this->entityManager = $entityManager;
        $this->formElementManager = $formElementManager;
        $this->filesystem = $filesystem;
    }

    /**
     * @param $id
     * @param string $getBy
     * @return Version
     * @throws \Exception
     */
    public function getVersion($id, string $getBy = 'id'): Version
    {
        switch ($getBy) {
            case 'id':
                return $this->getVersionById($id);
                break;

            case 'hash':
                return $this->getVersionByHash($id);
                break;

            default:
                throw new \Exception('Unknown getBy: ' . $getBy);
        }
    }

    /**
     * @param int $id
     * @return Version
     * @throws \Exception
     */
    private function getVersionById(int $id): Version
    {
        /** @var Version $version */
        $version = $this->entityManager
            ->getRepository(Version::class)
            ->find($id);

        if (is_null($version)) {
            throw new \Exception('not found');
        }

        return $version;
    }

    /**
     * @param string $hash
     * @return Version
     * @throws \Exception
     */
    private function getVersionByHash(string $hash): Version
    {
        /** @var Version $version */
        $version = $this->entityManager
            ->getRepository(Version::class)
            ->findOneBy([
                'hash' => $hash,
            ]);

        if (is_null($version)) {
            throw new \Exception('not found');
        }

        return $version;
    }

    /**
     * @param VersionAddForm $versionAddForm
     * @param array $files
     * @return Version
     * @throws \Exception
     */
    public function addVersion(VersionAddForm $versionAddForm, array $files = []): Version
    {
        /** @var Version $version */
        $version = $versionAddForm->getObject();

        if (empty($files) && !isset($files['packagedApp'])) {
            throw new \Exception('No file uploaded.');
        }

        // Generate a hash for the file & write to FS
        $filename = hash('sha256', random_bytes(8)) . '.zip';
        $this->filesystem->write($filename, file_get_contents($files['packagedApp']['tmp_name']));

        // Hash the file contents
        $fileContentsHash = hash_file('sha1', $files['packagedApp']['tmp_name']);

        $version->setPackagedApp($filename);
        $version->setPackagedHash($fileContentsHash);
        $version->setPackagedAppUrl(getenv('APP_URL') . 'upload/' . $filename);

        $this->entityManager->persist($version);
        $this->entityManager->flush();

        return $version;
    }

    /**
     * @param VersionEditForm $versionEditForm
     * @return bool
     */
    public function editVersion(VersionEditForm $versionEditForm): bool
    {
        /** @var Version $version */
        $version = $versionEditForm->getObject();

        $this->entityManager->persist($version);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param VersionDeleteForm $versionDeleteForm
     * @return bool
     */
    public function deleteVersion(VersionDeleteForm $versionDeleteForm): bool
    {
        /** @var Version $version */
        $version = $versionDeleteForm->getObject();

        $this->entityManager->remove($version);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return VersionAddForm
     * @throws \Exception
     */
    public function prepareAddForm(): VersionAddForm
    {
        /** @var VersionAddForm $form */
        $form = $this->formElementManager->get(VersionAddForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind(new Version());

        return $form;
    }

    /**
     * @param Version $version
     * @return VersionEditForm
     */
    public function prepareEditForm(Version $version): VersionEditForm
    {
        /** @var VersionEditForm $form */
        $form = $this->formElementManager->get(VersionEditForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($version);

        return $form;
    }

    /**
     * @param Version $version
     * @return VersionDeleteForm
     */
    public function prepareDeleteForm(Version $version): VersionDeleteForm
    {
        /** @var VersionDeleteForm $form */
        $form = $this->formElementManager->get(VersionDeleteForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($version);

        // Change the action attr
        $form->setAttribute('action', '/products/versions/' . $version->getHash() . '/delete');

        return $form;
    }
}