<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:40
 */

namespace Product\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Product\Entity\Product;
use Zend\InputFilter\InputFilter;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Form\Element\ObjectSelect;

/**
 * Class VersionAddForm
 * @package Product\Form
 */
class VersionAddForm extends Form
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * VersionAddForm constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function init()
    {
        parent::__construct('versionAdd');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'product',

            'options' => [
                'label' => 'Product',
                'object_manager' => $this->entityManager,
                'target_class' => Product::class,
                'property' => 'name',
                'display_empty_item' => true,
                'empty_item_label'   => 'Please choose a product',
                'is_method' => true,
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                    ],
                ],
            ],

            'attributes' => [
                'id' => 'product',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'versionNumber',

            'options' => [
                'label' => 'Version Number',
            ],

            'attributes' => [
                'id' => 'versionNumber',
                'class' => 'form-control',
                'autocomplete' => 'off',
                'placeholder' => '0.0.0',
            ],
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'changelog',

            'options' => [
                'label' => 'Changelog',
            ],

            'attributes' => [
                'id' => 'changelog',
                'class' => 'form-control js-simplemde',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\File::class,
            'name' => 'packagedApp',

            'options' => [
                'label' => 'Packaged App (.zip)',
            ],

            'attributes' => [
                'id' => 'packagedApp',
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
                'value' => 'Publish Version',
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
}