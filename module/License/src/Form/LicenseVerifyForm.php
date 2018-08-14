<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 13:19
 */

namespace License\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class LicenseVerifyForm
 * @package License\Form
 */
class LicenseVerifyForm extends Form
{
    public function init()
    {
        parent::__construct('licenseVerify');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/licenses/verify');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'code',

            'options' => [
                'label' => 'License Code',
            ],

            'attributes' => [
                'id' => 'code',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'required' => false,

            'attributes' => [
                'value' => 'Verify',
                'class' => 'btn btn-sm btn-primary',
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