<?php

namespace App\Controller;

use App\Controller\Validation\Validator;
use App\Domain\User\UserDomain;
use App\Helper\Request;
use App\Repository\Entity\User;
use App\Repository\Mapper\UserMapper;
use App\Repository\UserRepository;
use Bootstrap;

class RegisterController extends AbstractController implements ControllerInterface
{
    protected UserDomain $userDomain;

    public function __construct(Bootstrap $bootstrap)
    {
        parent::__construct($bootstrap);
        $this->userDomain = new UserDomain(new UserRepository($this->bootstrap->getDatabaseConnection()));
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     */
    public function get(Request $request): ControllerInterface
    {
        $this->setTitle('Login');
        $this->setDescription('Login');

        $this->template->set('userEmail', $this->session->get('userEmail'));
        $this->template->set('userName', $this->session->get('userName'));
        $this->template->render('registerGet');

        return $this;
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     */
    public function post(Request $request): ControllerInterface
    {
        $validator = new Validator();
        $message = $validator->validateRegistration(
            $request->get('userName'),
            $request->get('userEmail'),
            $request->get('userPassword'),
            $request->get('userPasswordRepeat')
        );

        if (!empty($message)) {
            $this->getRegisterPage($request, $message);
            return $this;
        }
        // check if user is already registered.
        $user = $this->userDomain->getByEmail($request->get('userEmail'));

        if (!is_null($user->getUserIdentifier())) {
            // I do not like when a service is used to find used email addresses. So keeping the message vague.
            $this->getRegisterPage($request, Validator::UNABLE_TO_CREATE_ACCOUNT);
            return $this;
        }

        $user = $this->createUser($request);
        // Last check...
        if (is_null($user->getUserIdentifier())) {
            $this->getRegisterPage($request, Validator::SOMETHING_WENT_WRONG);
        }

        $this->session->setNotificationMessage(Validator::ACCOUNT_HAS_BEEN_CREATED);
        header('Location: /index');
        return $this;
    }

    /**
     * @param Request $request
     * @param string $message
     * @return $this
     */
    private function getRegisterPage(Request $request, string $message)
    {
        $this->session->setErrorMessage($message);
        $this->session->set('userEmail', $request->get('userEmail'));
        $this->session->set('userName', $request->get('userName'));
        header('Location: /register/');
        return $this;
    }

    /**
     * Todo: create a request to user mapper? Done..
     * @param Request $request
     * @return User
     */
    private function createUser(Request $request)
    {
        return $this->userDomain->create(UserMapper::mapRequestToUser($request, new User()));
    }

    /**
     * Not relevant
     * @param Request $request
     * @return ControllerInterface
     */
    public function delete(Request $request): ControllerInterface
    {

    }
}