<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/06/2018
 * Time: 14:42
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;
use Zend\InputFilter\InputFilter;

/**
 * Class LoginTwoFactorForm
 * @package User\Form
 */
class LoginTwoFactorForm extends Form
{
    /**
     * LoginTwoFactorForm constructor.
     */
    public function __construct()
    {
        parent::__construct('loginTwoFactor');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type'  => Text::class,
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
            'name' => 'login_2fa_csrf',
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