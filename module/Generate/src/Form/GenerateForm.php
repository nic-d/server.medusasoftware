<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:20
 */

namespace Generate\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Product\Entity\Product;
use Zend\InputFilter\InputFilter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GenerateForm
 * @package Generate\Form
 */
class GenerateForm extends Form
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * GenerateForm constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function init()
    {
        parent::__construct('generate');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
        $this->setProductValueOptions();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Select::class,
            'name' => 'product',

            'options' => [
                'label' => 'Choose a product?',
                'empty_option' => 'Please choose a product',
                'value_options' => [],
            ],

            'attributes' => [
                'id' => 'product',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Generate',
                'class' => 'btn btn-hero-primary',
            ],
        ]);
    }

    public function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }

    public function setProductValueOptions()
    {
        /** @var array $products */
        $products = $this->entityManager
            ->getRepository(Product::class)
            ->findAll();

        if (empty($products)) {
            return;
        }

        $options = [];

        /** @var Product $product */
        foreach ($products as $product) {
            $options[$product->getHash()] = $product->getName();
        }

        $this->get('product')->setOptions([
            'value_options' => $options,
        ]);
    }
}