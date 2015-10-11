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

    public function addAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', null);
        if ($id === null) {
            return $this->redirect()->toRoute('application');
        }

        $listing = $this->getDocumentManager()->find('Application\Document\Listing', $id);

        if (!$listing instanceof Listing) {
            return $this->redirect()->toRoute('application');
        }

        $listing->setDeleted(true);
        $this->getDocumentManager()->flush();

        return $this->redirect()->toUrl("/listing/index");
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', null);
        if ($id === null) {
            return $this->redirect()->toRoute('application');
        }

        $listing = $this->getDocumentManager()->find('Application\Document\Listing', $id);

        if (!$listing instanceof Listing) {
            return $this->redirect()->toRoute('application');
        }


        return new ViewModel(array('listing' => $listing));
    }

    public function myListingsAction()
    {
        return new ViewModel();
    }

    public function editSaveAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
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

        $name = $request->getPost('name', '');
        $description = $request->getPost('description', '');
        $quantity = $request->getPost('price', 0);

        $listing->setDescription($description)
            ->setQuantity(0)
            ->setPrice((float) $quantity)
            ->setName($name);

        $this->getDocumentManager()->persist($listing);
        $this->getDocumentManager()->flush();

        return $this->redirect()->toUrl("/listing/edit/" . $listing->getId());
    }

    public function createAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
            return $this->notFoundAction();
        }

        $name = $request->getPost('name', '');
        $description = $request->getPost('description', '');
        $quantity = $request->getPost('price', 0);

        $listing = new Listing($this->getAuthService()->getIdentity(), $name);

        $listing->setDescription($description)
            ->setQuantity(0)
            ->setPrice((float) $quantity)
            ->setDeleted(0);

        $this->getDocumentManager()->persist($listing);
        $this->getDocumentManager()->flush();

        return $this->redirect()->toUrl("/listing/edit/" . $listing->getId());
    }
}
