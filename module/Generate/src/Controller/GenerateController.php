<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 16/08/2018
 * Time: 00:15
 */

namespace Generate\Controller;

use Zend\View\Model\ViewModel;
use Generate\Service\GenerateService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class GenerateController
 * @package Generate\Controller
 */
class GenerateController extends AbstractActionController
{
    /** @var GenerateService $generateService */
    private $generateService;

    /**
     * GenerateController constructor.
     * @param GenerateService $generateService
     */
    public function __construct(GenerateService $generateService)
    {
        $this->generateService = $generateService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $generated = null;
        $form = $this->generateService->prepareGenerateForm();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $generated = $this->generateService->generate($form);
            }
        }

        return new ViewModel([
            'form' => $form,
            'generated' => $generated,
        ]);
    }
}