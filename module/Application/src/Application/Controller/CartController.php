<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class CartController
 * @package Application\Controller
 */
class CartController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
