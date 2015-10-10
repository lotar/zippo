<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class BaseController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager $documentManager
     */
    private $documentManager;

    /**
     * @inheritdoc
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->documentManager = $e->getApplication()
            ->getServiceManager()
            ->get('doctrine.entitymanager.orm_default');

        return parent::onDispatch($e);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }
}
