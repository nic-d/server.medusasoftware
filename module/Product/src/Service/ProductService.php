<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:19
 */

namespace Product\Service;

use Product\Entity\Product;
use Product\Form\ProductAddForm;
use Product\Form\ProductEditForm;
use Envato\Service\EnvatoService;
use Product\Form\ProductDeleteForm;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class ProductService
 * @package Product\Service
 */
class ProductService
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /** @var EnvatoService $envatoService */
    private $envatoService;

    /**
     * ProductService constructor.
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
     * @return int
     */
    public function countProducts(): int
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->countRows();
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findAll();
    }

    /**
     * @param $id
     * @param string $getBy
     * @return Product
     * @throws \Exception
     */
    public function getProduct($id, string $getBy = 'id'): Product
    {
        switch ($getBy) {
            case 'id':
                return $this->getProductById($id);
                break;

            case 'hash':
                return $this->getProductByHash($id);
                break;

            default:
                throw new \Exception('Unknown getBy: ' . $getBy);
        }
    }

    /**
     * @param int $id
     * @return Product
     * @throws \Exception
     */
    private function getProductById(int $id): Product
    {
        /** @var Product $product */
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($id);

        if (is_null($product)) {
            throw new \Exception('not found');
        }

        return $product;
    }

    /**
     * @param string $hash
     * @return Product
     * @throws \Exception
     */
    private function getProductByHash(string $hash): Product
    {
        /** @var Product $product */
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy([
                'hash' => $hash,
            ]);

        if (is_null($product)) {
            throw new \Exception('not found');
        }

        return $product;
    }

    /**
     * @param ProductAddForm $productAddForm
     * @param $identity
     * @return Product
     */
    public function addProduct(ProductAddForm $productAddForm, $identity): Product
    {
        /** @var Product $product */
        $product = $productAddForm->getObject();

        // Merge the identity back into the EM
        $user = $this->entityManager->merge($identity);
        $product->setCreatedByUser($user);

        if (!is_null($productAddForm->get('envatoProduct')->getValue())) {
            /** @var int $selectedProductId */
            $selectedProductId = $productAddForm->get('envatoProduct')->getValue();

            // Get the product name from the haystack (don't need another API request :))
            $haystack = $productAddForm->get('envatoProduct')->getOption('value_options');
            $selectedProductName = $haystack[$selectedProductId];

            // Override the product name with the Envato product name
            $product->setName($selectedProductName);
            $product->setEnvatoProductId($selectedProductId);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    /**
     * @param ProductEditForm $productEditForm
     * @return bool
     */
    public function editProduct(ProductEditForm $productEditForm): bool
    {
        /** @var Product $product */
        $product = $productEditForm->getObject();

        if (!is_null($productEditForm->get('envatoProduct')->getValue())) {
            /** @var int $selectedProductId */
            $selectedProductId = $productEditForm->get('envatoProduct')->getValue();

            // Get the product name from the haystack (don't need another API request :))
            $haystack = $productEditForm->get('envatoProduct')->getOption('value_options');
            $selectedProductName = $haystack[$selectedProductId];

            // Override the product name with the Envato product name
            $product->setName($selectedProductName);
            $product->setEnvatoProductId($selectedProductId);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param ProductDeleteForm $productDeleteForm
     * @return bool
     */
    public function deleteProduct(ProductDeleteForm $productDeleteForm): bool
    {
        /** @var Product $product */
        $product = $productDeleteForm->getObject();

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @return ProductAddForm
     * @throws \Exception
     */
    public function prepareAddForm(): ProductAddForm
    {
        /** @var ProductAddForm $form */
        $form = $this->formElementManager->get(ProductAddForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind(new Product());

        // Authenticate using the personal token
        $this->envatoService->authenticatePersonal();
        $options = [];

        $products = $this->envatoService->api('market')->search([
            'username' => 'medusasoftware',
        ]);

        if (isset($products['matches'])) {
            foreach ($products['matches'] as $product) {
                $options[$product['id']] = $product['name'];
            }
        }

        $form->get('envatoProduct')->setOptions([
            'value_options' => $options,
        ]);

        return $form;
    }

    /**
     * @param Product $product
     * @return ProductEditForm
     * @throws \ErrorException
     */
    public function prepareEditForm(Product $product): ProductEditForm
    {
        /** @var ProductEditForm $form */
        $form = $this->formElementManager->get(ProductEditForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($product);

        // Authenticate using the personal token
        $this->envatoService->authenticatePersonal();
        $options = [];

        $products = $this->envatoService->api('market')->search([
            'username' => 'medusasoftware',
        ]);

        if (isset($products['matches'])) {
            foreach ($products['matches'] as $item) {
                $options[$item['id']] = $item['name'];
            }
        }

        $form->get('envatoProduct')->setOptions([
            'value_options' => $options,
        ])->setValue($product->getEnvatoProductId());

        return $form;
    }

    /**
     * @param Product $product
     * @return ProductDeleteForm
     */
    public function prepareDeleteForm(Product $product): ProductDeleteForm
    {
        /** @var ProductDeleteForm $form */
        $form = $this->formElementManager->get(ProductDeleteForm::class);
        $form->setHydrator(new DoctrineObject($this->entityManager, true));
        $form->bind($product);

        // Change the action attr
        $form->setAttribute('action', '/products/' . $product->getHash() . '/delete');

        return $form;
    }
}