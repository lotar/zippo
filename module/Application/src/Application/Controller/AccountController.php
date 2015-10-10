<?php

namespace Application\Controller;

use Application\Document\User;
use Zend\Validator\EmailAddress;
use Zend\View\Model\ViewModel;

/**
 * Class AccountController
 * @package Application\Controller
 */
class AccountController extends BaseController
{
    /**
     * Used for account registration registration
     *
     * @return ViewModel
     */
    public function registerAction()
    {
        $request = $this->getRequest();

        if ($request->isPost() !== true) {
            return $this->notFoundAction();
        }

        $email = $request->getPost('email', '');
        $password = $request->getPost('password', '');

        $emailValidator = new EmailAddress();
        if ($emailValidator->isValid($email) === false) {
            throw new \RuntimeException('Invalid email address format.');
        }

        if (strlen($password) < 8) {
            throw new \RuntimeException('Password too short.');
        }

        $user = new User($email, $password);

        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        // TODO: redirect to referrer
        return new ViewModel();
    }
}
