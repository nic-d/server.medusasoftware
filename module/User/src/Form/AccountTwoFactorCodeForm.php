<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 08/06/2018
 * Time: 13:30
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\InputFilter\InputFilter;

/**
 * Class AccountTwoFactorCodeForm
 * @package User\Form
 */
class AccountTwoFactorCodeForm extends Form
{
    /**
     * AccountTwoFactorCodeForm constructor.
     */
    public function __construct()
    {
        parent::__construct('accountTwoFactorCode');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/user/account/2fa/generate');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type' => Csrf::class,
            'name' => 'account_2fa_code_csrf',
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
                'value' => 'Generate New Codes',
                'class' => 'btn btn-primary mb-10',
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