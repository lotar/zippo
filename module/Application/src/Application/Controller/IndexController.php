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
}
