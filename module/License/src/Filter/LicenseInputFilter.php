<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 16:42
 */

namespace License\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Class LicenseInputFilter
 * @package License\Filter
 */
class LicenseInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name' => 'licenseCode',
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
            'name' => 'ip',
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
            'name' => 'domain',
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