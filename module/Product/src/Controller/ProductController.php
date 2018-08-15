<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:17
 */

namespace Product\Controller;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Product\Service\ProductService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ProductController
 * @package Product\Controller
 */
class ProductController extends AbstractActionController
{
    /** @var ProductService $productService */
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        // Fetch all products
        $products = $this->productService->getProducts();

        return new ViewModel([
            'products' => $products,
        ]);
    }

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function addAction()
    {
        $form = $this->productService->prepareAddForm();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->productService->addProduct($form, $this->identity());
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * @return ViewModel
     */
    public function viewAction()
    {
        /** @var string|null $hash */
        $hash = $this->params()->fromRoute('hash');

        if (is_null($hash)) {
            return $this->notFoundAction();
        }

        try {
            $product = $this->productService->getProduct($hash, 'hash');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'product' => $product,
        ]);
    }

    /**
     * @return ViewModel
     * @throws \ErrorException
     */
    public function editAction()
    {
        /** @var string|null $hash */
        $hash = $this->params()->fromRoute('hash');

        if (is_null($hash)) {
            return $this->notFoundAction();
        }

        try {
            $product = $this->productService->getProduct($hash, 'hash');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the edit form
        $form = $this->productService->prepareEditForm($product);
        $deleteForm = $this->productService->prepareDeleteForm($product);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->productService->editProduct($form);
            }
        }

        return new ViewModel([
            'form' => $form,
            'product' => $product,
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
            $product = $this->productService->getProduct($hash, 'hash');
        } catch (\Exception $e) {
            return $this->notFoundAction();
        }

        // Prepare the delete form
        $form = $this->productService->prepareDeleteForm($product);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $this->productService->deleteProduct($form);
            }
        }

        return $this->redirect()->toRoute('product.index');
    }
}