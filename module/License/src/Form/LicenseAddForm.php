<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 23:09
 */

namespace License\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class LicenseAddForm
 * @package License\Form
 */
class LicenseAddForm extends Form
{
    public function init()
    {
        parent::__construct('licenseAdd');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'licenseCode',

            'options' => [
                'label' => 'License Code',
            ],

            'attributes' => [
                'id' => 'licenseCode',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'validForEnvatoUsername',

            'options' => [
                'label' => 'Valid for Envato User',
            ],

            'attributes' => [
                'id' => 'validForEnvatoUsername',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'licensedIp',

            'options' => [
                'label' => 'Licensed IP',
            ],

            'attributes' => [
                'id' => 'licensedIp',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'licensedDomain',

            'options' => [
                'label' => 'Licensed Domain',
            ],

            'attributes' => [
                'id' => 'licensedDomain',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Number::class,
            'name' => 'installLimit',

            'options' => [
                'label' => 'Install Limit',
            ],

            'attributes' => [
                'id' => 'installLimit',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'licenseExpirationDate',

            'options' => [
                'label' => 'License Expiration Date',
            ],

            'attributes' => [
                'id' => 'licenseExpirationDate',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'note',

            'options' => [
                'label' => 'Note',
            ],

            'attributes' => [
                'id' => 'note',
                'class' => 'form-control form-control-lg js-simplemde',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Checkbox::class,
            'name' => 'isBlocked',

            'options' => [
                'label' => 'Is Blocked',
            ],

            'attributes' => [
                'id' => 'isBlocked',
                'class' => 'custom-control-input',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Save',
                'class' => 'btn btn-hero-primary',
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