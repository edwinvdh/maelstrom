<?php

namespace App\Controller;

use App\Domain\User\UserDomain;
use App\Helper\Request;
use App\Repository\UserRepository;

class IndexController extends AbstractController implements ControllerInterface
{
    protected bool $needsToBeLoggedIn = true;

    /**
     * @param Request $request
     * @return ControllerInterface
     */
    public function get(Request $request): ControllerInterface
    {
        $bootstrap = new \Bootstrap();
        $userDomain = new UserDomain(new UserRepository($bootstrap->getDatabaseConnection()));

        $this->setTitle('Users');
        $this->setDescription('users');
        $this->template->set('users', $userDomain->getAll());
        $this->template->render('indexGet');

        return $this;
    }

    /**
     * Not relevant
     * @param Request $request
     * @return ControllerInterface
     */
    public function post(Request $request): ControllerInterface
    {
        return $this;
    }

    /**
     * Not relevant
     * @param Request $request
     * @return ControllerInterface
     */
    public function delete(Request $request): ControllerInterface
    {
        return $this;
    }

}