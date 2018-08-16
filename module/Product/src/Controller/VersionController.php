<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:42
 */

namespace Product\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Product\Service\VersionService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class VersionController
 * @package Product\Controller
 */
class VersionController extends AbstractActionController
{
    /** @var VersionService $versionService */
    private $versionService;

    /**
     * VersionController constructor.
     * @param VersionService $versionService
     */
    public function __construct(VersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('product.index');
    }

    /**
     * @return Response|ViewModel
     * @throws \Exception
     */
    public function addAction()
    {
        // Prepare the add form
        $form = $this->versionService->prepareAddForm();

        if ($this->getRequest()->isPost()) {
            // Merge the post and files to validate the form
            $form->setData(array_merge_recursive(
                $this->params()->fromPost(),
                $this->params()->fromFiles()
            ));

            if ($form->isValid()) {
                try {
                    $this->versionService->addVersion($form, $this->params()->fromFiles(null, []));
                } catch (\Exception $e) {
                    // Add flash message and redirect so we can access it
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    return $this->redirect()->toRoute('product.index/version/add');
                }

                // Add flash message and redirect so we can access it
                $this->flashMessenger()->addSuccessMessage('Success! Saved new version.');
                return $this->redirect()->toRoute('product.index/version/add');
            }
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
        /** @var string|null $hash */
        $hash = $this->params()->fromRoute('hash');

        if (is_null($hash)) {
            return $this->notFoundAction();
        }

        try {
            $version = $this->versionService->getVersion($hash, 'hash');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the forms
        $form = $this->versionService->prepareEditForm($version);
        $deleteForm = $this->versionService->prepareDeleteForm($version);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                try {
                    $this->versionService->editVersion($form);
                } catch (\Exception $e) {
                    // Add flash message and redirect so we can access it
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    return $this->redirect()->toRoute('product.index/version/edit', [
                        'hash' => $hash,
                    ]);
                }

                // Add flash message and redirect so we can access it
                $this->flashMessenger()->addSuccessMessage('Success! Saved changes.');
                return $this->redirect()->toRoute('product.index/version/edit', [
                    'hash' => $hash,
                ]);
            }
        }

        return new ViewModel([
            'form' => $form,
            'version' => $version,
            'deleteForm' => $deleteForm,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function deleteAction()
    {
        /** @var string|null $hash */
        $hash = $this->params()->fromRoute('hash');

        if (is_null($hash)) {
            return $this->notFoundAction();
        }

        try {
            $version = $this->versionService->getVersion($hash, 'hash');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the delete form
        $form = $this->versionService->prepareDeleteForm($version);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->versionService->deleteVersion($form);
            }
        }

        return $this->redirect()->toRoute('product.index');
    }
}