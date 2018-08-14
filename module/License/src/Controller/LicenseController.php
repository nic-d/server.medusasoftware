<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 22:08
 */

namespace License\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use License\Service\LicenseService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class LicenseController
 * @package License\Controller
 */
class LicenseController extends AbstractActionController
{
    /** @var LicenseService $licenseService */
    private $licenseService;

    /**
     * LicenseController constructor.
     * @param LicenseService $licenseService
     */
    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // Get all licenses
        $licenses = $this->licenseService->getLicenses();

        // Prepare the verify form
        $form = $this->licenseService->prepareVerifyForm();

        return new ViewModel([
            'form' => $form,
            'licenses' => $licenses,
        ]);
    }

    /**
     * @throws \ErrorException
     */
    public function verifyAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();

        // TEST CODE: b1a13c6b-a604-4c1e-ae3d-bab9e4ccf7d2
        $form = $this->licenseService->prepareVerifyForm();
        $isValidLicense = false;

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $isValidLicense = $this->licenseService->verifyLicenseCodeUsingForm($form);
            }
        }

        if ($isValidLicense) {
            return $response->setStatusCode(200);
        }

        return $response->setStatusCode(400);
    }

    /**
     * @return ViewModel
     */
    public function addAction()
    {
        // Prepare the add form
        $form = $this->licenseService->prepareAddForm();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->licenseService->addLicense($form);
            }

            $this->flashMessenger()->addSuccessMessage('Success! Created license.');
            return $this->redirect()->toRoute('license.index');
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function editAction()
    {
        /** @var string|null $code */
        $code = $this->params()->fromRoute('code');

        if (is_null($code)) {
            return $this->notFoundAction();
        }

        try {
            $license = $this->licenseService->getLicense($code, 'code');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the edit form
        $form = $this->licenseService->prepareEditForm($license);
        $deleteForm = $this->licenseService->prepareDeleteForm($license);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->licenseService->editLicense($form);
            }

            $this->flashMessenger()->addSuccessMessage('Success! Saved changes to license.');
            return $this->redirect()->toRoute('license.index/edit', [
                'code' => $code,
            ]);
        }

        return new ViewModel([
            'form' => $form,
            'license' => $license,
            'deleteForm' => $deleteForm,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function deleteAction()
    {
        /** @var string|null $code */
        $code = $this->params()->fromRoute('code');

        if (is_null($code)) {
            return $this->notFoundAction();
        }

        try {
            $license = $this->licenseService->getLicense($code, 'code');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the delete form
        $form = $this->licenseService->prepareDeleteForm($license);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                try {
                    $this->licenseService->deleteLicense($form);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Error! Failed to remove license.');
                    return $this->redirect()->toRoute('license.index/edit', [
                        'code' => $code,
                    ]);
                }
            }
        }

        $this->flashMessenger()->addSuccessMessage('Success! Removed license.');
        return $this->redirect()->toRoute('license.index');
    }
}