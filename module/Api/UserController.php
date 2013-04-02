<?php

namespace Api;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{
    protected $service;

    public function setUserService(UserService $service)
    {
        $this->service = $service;
    }

    public function byEmailAction()
    {
        $email = $this->params()->fromQuery('email', false);

        if (!$email) {
            return $this->createProblemResponse(400, 'Bad Request', 'No email provided in the query string');
        }

        $found = $this->service->byEmail($email);

        if (!$found) {
            return $this->createProblemResponse(404, 'Not found', 'No user by that email found');
        }
        return new JsonModel(array(
            'found' => true,
            'email' => $email,
        ));
    }

    public function byUsernameAction()
    {
        $username = $this->params()->fromQuery('username', false);
        if (!$username) {
            return $this->createProblemResponse(400, 'Bad Request', 'No username provided in the query string');
        }

        $found = $this->service->byUsername($username);

        if (!$found) {
            return $this->createProblemResponse(404, 'Not found', 'No user by that username found');
        }
        return new JsonModel(array(
            'found'    => true,
            'username' => $username,
        ));
    }

    protected function createProblemResponse($code, $title, $message)
    {
        $response = $this->getResponse();
        $response->setStatusCode($code);

        $viewModel = new JsonModel(array(
            'httpStatus'  => $code,
            'title'       => $title,
            'detail'      => $message,
            'describedBy' => 'http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
        ));
        return $viewModel;
    }
}
