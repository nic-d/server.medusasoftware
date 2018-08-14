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
 * Class LicenseDeleteForm
 * @package License\Form
 */
class LicenseDeleteForm extends Form
{
    public function init()
    {
        parent::__construct('licenseDelete');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'delete_csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'type'  => Element\Submit::class,
            'name' => 'submit',

            'attributes' => [
                'value' => 'Delete',
                'class' => 'btn btn-sm btn-danger',
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