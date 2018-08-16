<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 17:13
 */

namespace Product\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Class DownloadInputFilter
 * @package Product\Filter
 */
class DownloadInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name' => 'license',
            'required' => true,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => false,
            'continue_if_empty' => false,
        ]);

        $this->add([
            'name' => 'version',
            'required' => true,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => false,
            'continue_if_empty' => false,
        ]);
    }
}