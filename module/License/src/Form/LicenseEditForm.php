<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 13/08/2018
 * Time: 23:10
 */

namespace License\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class LicenseEditForm
 * @package License\Form
 */
class LicenseEditForm extends Form
{
    public function init()
    {
        parent::__construct('licenseEdit');

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
            'required' => false,

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
            'required' => false,

            'options' => [
                'label' => 'Licensed IP(s)',
            ],

            'attributes' => [
                'id' => 'licensedIp',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
                'placeholder' => 'Comma separated for multiple values.',
            ],
        ]);

        $this->add([
            'type' => Element\Text::class,
            'name' => 'licensedDomain',

            'options' => [
                'label' => 'Licensed Domain(s)',
            ],

            'attributes' => [
                'id' => 'licensedDomain',
                'class' => 'form-control form-control-lg',
                'autocomplete' => 'off',
                'placeholder' => 'Comma separated for multiple values.',
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
            'type' => Element\Date::class,
            'name' => 'licenseExpirationDate',

            'options' => [
                'label' => 'License Expiration Date',
            ],

            'attributes' => [
                'id' => 'licenseExpirationDate',
                'class' => 'form-control form-control-lg js-datepicker',
                'autocomplete' => 'off',
                'placeholder' => 'yyyy-mm-dd',
                'data-date-format' => 'yyyy-mm-dd',
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

        $inputFilter->add([
            'name'     => 'validForEnvatoUsername',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'licensedIp',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'licensedDomain',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'installLimit',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'licenseExpirationDate',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'note',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);

        $inputFilter->add([
            'name'     => 'isBlocked',
            'required' => false,
            'filters'  => [],
            'validators' => [],
        ]);
    }
}