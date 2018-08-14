<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/06/2018
 * Time: 19:23
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Number;
use Zend\InputFilter\InputFilter;

/**
 * Class AccountTwoFactorForm
 * @package User\Form
 */
class AccountTwoFactorForm extends Form
{
    /**
     * AccountTwoFactorForm constructor.
     */
    public function __construct()
    {
        parent::__construct('accountTwoFactor');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/user/account/2fa');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type'  => Number::class,
            'name' => 'verificationSecret',

            'options' => [
                'label' => 'Secret',
            ],

            'attributes' => [
                'id' => 'verificationSecret',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Csrf::class,
            'name' => 'account_2fa_csrf',
            'required' => true,

            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type'  => Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Verify',
                'class' => 'btn btn-success',
            ],
        ]);
    }

    private function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
    }
}