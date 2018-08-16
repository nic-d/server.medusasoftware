<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 22:27
 */

namespace Install\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Class InstallEditForm
 * @package Install\Form
 */
class InstallEditForm extends Form
{
    public function init()
    {
        parent::__construct('installEdit');

        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => Element\Csrf::class,
            'name' => 'edit_csrf',
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
                'value' => 'Null License',
                'class' => 'btn btn-sm btn-secondary',
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