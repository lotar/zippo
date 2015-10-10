<?php

namespace Application\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class BaseController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager $documentManager
     */
    private $documentManager;

    /**
     * @return \Zend\Http\PhpEnvironment\Request
     * @throws \RuntimeException
     */
    public function getRequest()
    {
        $request = parent::getRequest();
        if ($request instanceof Request) {
            return $request;
        }
        throw new \RuntimeException("Only PHP Environment requests are allowed.");
    }

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
