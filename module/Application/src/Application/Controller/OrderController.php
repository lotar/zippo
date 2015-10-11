<?php

namespace Application\Controller;

use Application\Document\Listing;
use Application\Document\OrderItem;
use Application\Document\User;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
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

        $orderFinalMap = array();
        $userIds = array();
        foreach ($listings as $one) {
            if (!isset($orderFinalMap[$one->getUserId()][$one->getId()])) {
                $orderFinalMap[$one->getUserId()][$one->getId()] = 0;
            }
            $orderFinalMap[$one->getUserId()][$one->getId()]++;
            $userIds[] = $one->getUserId();
        }

        /* @var \Application\Repository\user $userRepo */
        $userRepo = $this->getDocumentManager()->getRepository('Application\Document\User');
        $users = $userRepo->getMapByIds($userIds);

        foreach ($users as $user) {
            $name = $request->getPost('name', '');
            $email = $request->getPost('email', '');
            $phone = $request->getPost('phone', 0);
            $address = $request->getPost('address', 0);
            $this->notifyUser(
                $user,
                $orderFinalMap[$user->getId()],
                $listings,
                $name,
                $email,
                $phone,
                $address
            );
        }

        return $this->redirect()->toRoute('application', array('action' => 'success', 'controller' => 'order'));
    }

    public function successAction()
    {
        return new ViewModel();
    }

    public function removeAction()
    {
        $request = $this->getRequest();

        if ($request->isGet() !== true) {
            return $this->notFoundAction();
        }

        $id = $this->params()->fromRoute('id', null);
        if ($id === null) {
            return $this->redirect()->toRoute('application', array('action' => 'index', 'controller' => 'order'));
        }

        /* @var \Application\Repository\OrderItem $listingRepo */
        $listingRepo = $this->getDocumentManager()->getRepository('Application\Document\OrderItem');
        $listingRepo->removeBySessionAndListingId($this->getSessionManager()->getId(), $id);

        return $this->redirect()->toRoute('application', array('action' => 'index', 'controller' => 'order'));
    }

    private function notifyUser(User $user, $qtyMap, $listings, $name, $email, $phone, $address)
    {
        //TODO: throw exception if address not recognized

        $text = 'Pozdrav ' . $user->getDisplayName() . ",\n\n" .
        'Dobili ste iducu narudzbu:' . "\nn";

        foreach ($qtyMap as $listingId => $qty) {
            /* @var \Application\Repository\OrderItem $listingRepo */
            $listingRepo = $this->getDocumentManager()->getRepository('Application\Document\OrderItem');
            $listingRepo->removeBySessionAndListingId($this->getSessionManager()->getId(), $listingId);

            $text .= $listings[$listingId]->getName() . ', kolicina: ' . $qtyMap[$listingId] . "\n";
        }

        $text .= "\n" . 'Narudzba je stigla od: ' . $name . ', na adresu: ' . $address . "\n";

        $distance = $this->checkDistance($user, $address);
        if ($distance !== null) {
            $text .= "Izracunali smo da je od Vas to udaljeno " . $distance . "\n\n";
        }

        $text .= 'Kontakt email: ' . $email . ', kontakt telefon: ' . $phone . "\n";

        $mail = new Message();
        $mail->setBody($text);
        $mail->setFrom($email, $name);
        $mail->addTo($user->getEmail(), $user->getDisplayName());
        $mail->setSubject('Nova narudzba - Farma Direkt');

        $transport = new Sendmail();
        $transport->send($mail);
    }

    private function checkDistance(User $user, $deliveryAddress)
    {
        if ($user->areCoordinatesSet()) {
            $apiKey = $this->getServiceLocator()->get('config')['google']['api_key'];
            $checkUrl = "https://maps.googleapis.com/maps/api/distancematrix/json?origins="
                . $user->getAddress()
                . "&destinations=" .
                $deliveryAddress
                . "&key=" .
                $apiKey;
            $request = new Request();
            $request->setUri($checkUrl);
            $request->setMethod('GET');

            $client = new Client();
            $response = $client->send($request);

            $body = $response->getBody();
            $decoded = Json::decode($body, Json::TYPE_ARRAY);
            if (isset($decoded['rows'][0]['elements'][0]['distance']['text'])) {
                return $decoded['rows'][0]['elements'][0]['distance']['text'];
            }
        }
        return null;
    }
}
