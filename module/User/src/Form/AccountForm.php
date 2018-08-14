<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 29/03/2018
 * Time: 11:37
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\InputFilter\InputFilter;

/**
 * Class AccountForm
 * @package User\Form
 */
class AccountForm extends Form
{
    /**
     * AccountForm constructor.
     */
    public function __construct()
    {
        parent::__construct('accountForm');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type'  => Text::class,
            'name' => 'username',
            'disabled' => true,

            'options' => [
                'label' => 'Username',
            ],

            'attributes' => [
                'id' => 'username',
                'class' => 'form-control',
                'disabled' => 'disabled',
            ],
        ]);

        $this->add([
            'type'  => Email::class,
            'name' => 'email',
            'disabled' => true,

            'options' => [
                'label' => 'Email',
            ],

            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'disabled' => 'disabled',
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',

            'options' => [
                'label' => 'Password',
            ],

            'attributes' => [
                'id' => 'password',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'confirm_password',

            'options' => [
                'label' => 'Confirm Password',
            ],

            'attributes' => [
                'id' => 'confirm_password',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',

            'attributes' => [
                'value' => 'Save Changes',
                'class' => 'btn btn-primary mb-10',
            ],
        ]);
    }

    private function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'username',
            'required' => false,
            'filters' => [],
        ]);

        $inputFilter->add([
            'name' => 'email',
            'required' => false,
            'filters' => [],
        ]);

        $inputFilter->add([
            'name' => 'password',
            'required' => false,
            'filters' => [],

            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'confirm_password',
            'required' => false,
            'filters'  => [],

            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ],
                ],
            ],
        ]);
    }
}