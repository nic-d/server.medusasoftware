<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 24/03/2018
 * Time: 07:55
 */

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Validator\Hostname;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Password;
use Zend\InputFilter\InputFilter;

/**
 * Class LoginForm
 * @package User\Form
 */
class LoginForm extends Form
{
    /**
     * LoginForm constructor.
     */
    public function __construct()
    {
        parent::__construct('loginForm');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'type'  => Email::class,
            'name'  => 'email',
            'required' => true,

            'options' => [
                'label' => 'Your Email',
            ],

            'attributes' => [
                'id' => 'email',
                'class' => 'form-control',
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type'  => Password::class,
            'name'  => 'password',
            'required' => true,

            'options' => [
                'label' => 'Password',
            ],

            'attributes' => [
                'id' => 'password',
                'class' => 'form-control',
                'required' => 'required',
            ],
        ]);

        $this->add([
            'type'  => Hidden::class,
            'name' => 'redirect_url'
        ]);

        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'required' => true,

            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type'  => Submit::class,
            'name'  => 'submit',

            'attributes' => [
                'value' => 'Sign in',
                'id' => 'submit',
                'class' => 'btn btn-primary btn-block',
            ],
        ]);
    }

    private function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,

            'filters'  => [
                ['name' => 'StringTrim'],
            ],

            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);

        // Add input for "password" field
        $inputFilter->add([
            'name'     => 'password',
            'required' => true,

            'filters'  => [],

            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64,
                    ],
                ],
            ],
        ]);

        // Add input for "redirect_url" field
        $inputFilter->add([
            'name'     => 'redirect_url',
            'required' => false,

            'filters'  => [
                ['name' => 'StringTrim']
            ],

            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 2048,
                    ],
                ],
            ],
        ]);
    }
}