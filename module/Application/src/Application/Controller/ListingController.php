<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class ListingController
 * @package Application\Controller
 */
class ListingController extends BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
