<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 15/08/2018
 * Time: 19:49
 */

namespace Server\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class InstallForm
 * @package Server\Form
 */
class InstallForm extends Form
{
    public function init()
    {
        parent::__construct('install');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type'  => Element\Text::class,
            'name' => 'license',

            'options' => [
                'label' => 'License Key',
            ],

            'attributes' => [
                'id' => 'license',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type'  => Element\Text::class,
            'name' => 'host',

            'options' => [
                'label' => 'Database Host',
            ],

            'attributes' => [
                'id' => 'host',
                'class' => 'form-control',
                'autocomplete' => 'off',
                'value' => 'localhost',
            ],
        ]);

        $this->add([
            'type'  => Element\Number::class,
            'name' => 'port',

            'options' => [
                'label' => 'Database Port',
            ],

            'attributes' => [
                'id' => 'port',
                'class' => 'form-control',
                'autocomplete' => 'off',
                'value' => '3306',
            ],
        ]);

        $this->add([
            'type'  => Element\Text::class,
            'name' => 'user',

            'options' => [
                'label' => 'Database User',
            ],

            'attributes' => [
                'id' => 'user',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type'  => Element\Password::class,
            'name' => 'password',

            'options' => [
                'label' => 'Database Password',
            ],

            'attributes' => [
                'id' => 'password',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type'  => Element\Text::class,
            'name' => 'name',

            'options' => [
                'label' => 'Database Name',
            ],

            'attributes' => [
                'id' => 'name',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type'  => Element\Url::class,
            'name' => 'appUrl',

            'options' => [
                'label' => 'App URL',
            ],

            'attributes' => [
                'id' => 'appUrl',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Install',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function addInputFilter()
    {
        /** @var InputFilter $inputFilter */
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'license',
            'required' => true,
        ]);
    }
}