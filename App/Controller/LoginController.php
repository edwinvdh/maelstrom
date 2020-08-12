<?php

namespace App\Controller;

use App\Controller\Validation\Validator;
use App\Domain\User\UserDomain;
use App\Helper\Request;
use App\Repository\UserRepository;
use Bootstrap;

class LoginController extends AbstractController implements ControllerInterface
{
    protected UserDomain $userDomain;

    /**
     * @param Bootstrap $bootstrap
     * @throws Exception\TemplateNotFoundException
     */
    public function __construct(Bootstrap $bootstrap)
    {
        parent::__construct($bootstrap);
        $this->userDomain = new UserDomain(new UserRepository($this->bootstrap->getDatabaseConnection()));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function get(Request $request): ControllerInterface
    {
        $this->setTitle('Login');
        $this->setDescription('Login');

        $this->template->render('loginGet');

        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function post(Request $request): ControllerInterface
    {
        $userEmail = $request->get('userEmail');
        $userPassword = $request->get('userPassword');

        if ($this->userDomain->isValidUser($userEmail, $userPassword)) {
            $user = $this->userDomain->getByEmailAndPassword($userEmail, $userPassword);
            $this->login($user);
            header('Location: /index');
            return $this;
        }

        $this->session->setErrorMessage(Validator::VALIDATE_USER_NOT_FOUND);

        // invalid
        header('Location: /login/');

        return $this;
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