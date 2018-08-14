<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 14/08/2018
 * Time: 21:37
 */

namespace Install\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Class InstallInputFilter
 * @package Install\Filter
 */
class InstallInputFilter extends InputFilter
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
            'name' => 'product',
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
            'name' => 'ipAddress',
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

        $this->add([
            'name' => 'fullUrl',
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
            'name' => 'serverName',
            'required' => false,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ]);

        $this->add([
            'name' => 'phpVersion',
            'required' => false,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ]);

        $this->add([
            'name' => 'databasePlatform',
            'required' => false,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ]);

        $this->add([
            'name' => 'databasePlatformVersion',
            'required' => false,
            'filters' => [
                [
                    'name' => 'Zend\Filter\StringTrim',
                    'options' => [],
                ],
            ],
            'validators' => [],
            'allow_empty' => true,
            'continue_if_empty' => true,
        ]);
    }
}