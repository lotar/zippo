<?php

namespace Application\Controller;

use Application\Document\Listing;
use Application\Document\OrderItem;
use Zend\View\Model\ViewModel;

/**
 * Class OrderController
 * @package Application\Controller
 */
class OrderController extends BaseController
{
    public function indexAction()
    {
        /* @var \Application\Repository\OrderItem $orderItemsRepo */
        $orderItemsRepo = $this->getDocumentManager()->getRepository('Application\Document\OrderItem');
        $orderItems = $orderItemsRepo->getBySessionId($this->getSessionManager()->getId());

        $itemsQty = array();
        $itemIds = array();
        foreach ($orderItems as $one) {
            if (!isset($itemsQty[$one->getItemId()])) {
                $itemsQty[$one->getItemId()] = 0;
            }
            $itemsQty[$one->getItemId()]++;
            $itemIds[] = $one->getItemId();
        }

        /* @var \Application\Repository\Listing $listingRepo */
        $listingRepo = $this->getDocumentManager()->getRepository('Application\Document\Listing');
        $listings = $listingRepo->getByListingIds($itemIds);

        return new ViewModel(array('listings' => $listings, 'qty' => $itemsQty));
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isGet() !== true) {
            return $this->notFoundAction();
        }

        $id = $this->params()->fromRoute('id', null);
        if ($id === null) {
            return $this->redirect()->toRoute('application');
        }

        $listing = $this->getDocumentManager()->find('Application\Document\Listing', $id);

        if (!$listing instanceof Listing) {
            return $this->redirect()->toRoute('application');
        }

        $oi = new OrderItem();
        $oi->setSessionId($this->getSessionManager()->getId())
            ->setItemId($listing->getId());

        $this->getDocumentManager()->persist($oi);
        $this->getDocumentManager()->flush();

        return $this->redirect()->toRoute('application', array('action' => 'index', 'controller' => 'order'));
    }

    public function makeOrderAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
            return $this->notFoundAction();
        }

        $name = $request->getPost('name', '');
        $email = $request->getPost('description', '');
        $phone = $request->getPost('price', 0);
        $address = $request->getPost('price', 0);

        return $this->successAction();
    }

    public function successAction()
    {

    }
}
