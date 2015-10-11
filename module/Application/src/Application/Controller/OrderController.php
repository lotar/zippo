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
        $listings = $orderItemsRepo->getBySessionId($this->getSessionManager()->getId());
        return new ViewModel(array('listings' => $listings));
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
}
