<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 14:41
 */

namespace Product\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class VersionEditForm
 * @package Product\Form
 */
class VersionEditForm extends Form
{
    public function init()
    {
        parent::__construct('versionEdit');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'versionNumber',

            'options' => [
                'label' => 'Version Number',
            ],

            'attributes' => [
                'id' => 'versionNumber',
                'class' => 'form-control',
                'autocomplete' => 'off',
            ],
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'changelog',

            'options' => [
                'label' => 'Changelog',
            ],

            'attributes' => [
                'id' => 'changelog',
                'class' => 'form-control js-simplemde',
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
                'value' => 'Save Changes',
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