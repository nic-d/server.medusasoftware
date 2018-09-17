<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:26
 */

namespace Product\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class ProductAddForm
 * @package Product\Form
 */
class ProductAddForm extends Form
{
    public function init()
    {
        parent::__construct('productAdd');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'name',

            'options' => [
                'label' => 'Name',
            ],

            'attributes' => [
                'id' => 'name',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'description',

            'options' => [
                'label' => 'Description',
            ],

            'attributes' => [
                'id' => 'description',
                'class' => 'form-control form-control-lg js-simplemde',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'envatoProduct',
            'required' => false,

            'options' => [
                'label' => 'Choose Envato product',
                'empty_option' => 'Choose an envato product',
                'value_options' => [],
            ],

            'attributes' => [
                'id' => 'envatoProduct',
                'class' => 'form-control form-control-lg',
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
                'value' => 'Save',
                'class' => 'btn btn-hero-primary',
            ],
        ]);
    }

    public function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'envatoProduct',
            'required' => false,
            'filters' => [],
        ]);
    }
}