<?php

namespace Application\Controller;

use Application\Document\Listing;
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

    public function createAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
            return $this->notFoundAction();
        }

        $name = $request->getPost('name', '');
        $description = $request->getPost('description', '');
        $quantity = $request->getPost('quantity', 0);

        $listing = new Listing($user, $name);

        $listing->setDescription($description)
            ->setQuantity(abs((int)$quantity));

        $this->getDocumentManager()->persist($listing);
        $this->getDocumentManager()->flush();

        return $this->myListingsAction();
    }

    public function myListingsAction()
    {
        return new ViewModel();
    }
}
