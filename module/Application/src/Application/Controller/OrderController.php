<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class OrderController
 * @package Application\Controller
 */
class OrderController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        return new ViewModel();
    }
}
