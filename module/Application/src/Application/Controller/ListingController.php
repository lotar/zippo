<?php

namespace Application\Controller;

use Application\Document\Listing;
use Application\Document\User;
use Zend\View\Model\ViewModel;

/**
 * Class ListingController
 * @package Application\Controller
 */
class ListingController extends BaseController
{
    public function indexAction()
    {
        $offset = 0;
        /* @var \Application\Repository\Listing $repo */
        $repo = $this->getDocumentManager()->getRepository('Application\Document\Listing');
        $listings = $repo->getPage(12, $offset);

        $userIds = array();
        foreach ($listings as $one) {
            $userIds[] = $one->getUserId();
        }

        /* @var \Application\Repository\User $repo */
        $repo = $this->getDocumentManager()->getRepository('Application\Document\User');
        $providersMap = $repo->getMapByIds($userIds);

        return new ViewModel(array('providersMap' => $providersMap, 'listings' => $listings, 'offset' => $offset));
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
