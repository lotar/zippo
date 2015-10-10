<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
