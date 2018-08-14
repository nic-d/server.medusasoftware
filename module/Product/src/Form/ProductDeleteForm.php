<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 12/08/2018
 * Time: 15:27
 */

namespace Product\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class ProductDeleteForm
 * @package Product\Form
 */
class ProductDeleteForm extends Form
{
    public function init()
    {
        parent::__construct('productDelete');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'delete_csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type'  => Element\Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Yes, Delete!',
                'class' => 'btn btn-hero-danger',
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