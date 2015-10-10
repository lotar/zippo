<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class BaseController extends AbstractActionController
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager $documentManager
     */
    private $documentManager;

    /**
     * @inheritdoc
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->documentManager = $e->getApplication()
            ->getServiceManager()
            ->get('doctrine.documentmanager.odm_default');

        return parent::onDispatch($e);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}
