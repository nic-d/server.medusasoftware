<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:41
 */

namespace Product\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class VersionDeleteForm
 * @package Product\Form
 */
class VersionDeleteForm extends Form
{
    public function init()
    {
        parent::__construct('versionDelete');

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