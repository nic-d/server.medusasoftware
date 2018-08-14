<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/06/2018
 * Time: 14:05
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\InputFilter\InputFilter;

/**
 * Class AccountTwoFactorDisableForm
 * @package User\Form
 */
class AccountTwoFactorDisableForm extends Form
{
    /**
     * AccountTwoFactorDisableForm constructor.
     */
    public function __construct()
    {
        parent::__construct('accountTwoFactorDisable');

        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/user/account/2fa/disable');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type' => Csrf::class,
            'name' => 'account_2fa_disable_csrf',
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
                'value' => 'Disable',
                'class' => 'btn btn-outline-danger mt-2',
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