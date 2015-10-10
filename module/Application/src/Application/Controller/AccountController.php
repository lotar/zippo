<?php

namespace Application\Controller;

use Application\Document\User;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;
use Zend\View\Model\ViewModel;

/**
 * Class AccountController
 * @package Application\Controller
 */
class AccountController extends BaseController
{
    /**
     * Used for editing account information
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
            return $this->notFoundAction();
        }

        if (!$this->getAuthService()->getIdentity() instanceof User) {
            $this->redirect()->toRoute('application');
        }

        $name = $request->getPost('name', '');
        $phone = $request->getPost('phone', '');
        $address = $request->getPost('address', '');
        $description = $request->getPost('description', '');

        $lat = null;
        $lng = null;
        if (!empty($address)) {
            $apiKey = $this->getServiceLocator()->get('config')['google']['api_key'];
            $checkUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" .
                $address .
                "&key=" .
                $apiKey;
            $request = new Request();
            $request->setUri($checkUrl);
            $request->setMethod('GET');

            $client = new Client();
            $response = $client->send($request);

            $body = $response->getBody();
            $decoded = Json::decode($body, Json::TYPE_ARRAY);
            if (isset($decoded['results'][0]['geometry']['location']['lat'])
                && isset($decoded['results'][0]['geometry']['location']['lng'])
            ) {
                $lat = $decoded['results'][0]['geometry']['location']['lat'];
                $lng = $decoded['results'][0]['geometry']['location']['lng'];
            }
        }

        /* @var \Application\Document\User $user */
        $user = $this->getAuthService()->getIdentity();
        $user->setDisplayName($name)
            ->setPhone($phone)
            ->setAddress($address)
            ->setDescription($description);

        if ($lat && $lng) {
            $user->setLatitude($lat)
                ->setLongitude($lng);
        }

        //Distance URL for checkout https://maps.googleapis.com/maps/api/distancematrix/json?origins=Osijek&destinations=Vinkovci&key=

        $this->getDocumentManager()->flush();

        return $this->redirect()->toRoute('zfcuser');
    }
}
