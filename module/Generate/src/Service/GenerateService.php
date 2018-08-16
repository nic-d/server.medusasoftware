<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:16
 */

namespace Generate\Service;

use Generate\Form\GenerateForm;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill as FormElementManager;

/**
 * Class GenerateService
 * @package Generate\Service
 */
class GenerateService
{
    /** @var FormElementManager $formElementManager */
    private $formElementManager;

    /**
     * GenerateService constructor.
     * @param FormElementManager $formElementManager
     */
    public function __construct(FormElementManager $formElementManager)
    {
        $this->formElementManager = $formElementManager;
    }

    /**
     * @param GenerateForm $generateForm
     * @return string
     */
    public function generate(GenerateForm $generateForm)
    {
        // Get the product hash (value)
        $productHash = $generateForm->get('product')->getValue();

        // Get the product name from the haystack
        $haystack = $generateForm->get('product')->getOption('value_options');
        $productName = $haystack[$productHash];

        $content = file_get_contents(__DIR__ . '/Template/template.json');

        // Replace the app name
        $content = str_replace('MS.__APPNAME__', 'MS.' . $productName, $content);
        $content = str_replace('__PRODUCTID__', $productHash, $content);

        return $content;
    }

    /**
     * @return GenerateForm
     */
    public function prepareGenerateForm(): GenerateForm
    {
        return $this->formElementManager->get(GenerateForm::class);
    }
}