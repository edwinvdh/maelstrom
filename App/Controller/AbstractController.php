<?php

namespace App\Controller;

use App\Controller\Exception\TemplateNotFoundException;
use App\Helper\Session;
use App\Infrastructure\Template\Template;
use App\Repository\Entity\User;
use Bootstrap;

abstract class AbstractController
{
    const USER_SESSION_BAG = 'userIdentifier';

    protected string $templateDir;

    protected string $title;

    protected string $description;

    protected Template $template;

    protected Bootstrap $bootstrap;

    protected Session $session;

    protected bool $needsToBeLoggedIn = false;

    /**
     * @param Bootstrap $bootstrap
     * @throws TemplateNotFoundException
     */
    public function __construct(Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
        $this->session = $this->bootstrap->getSession();
        $this->templateDir = getenv('TEMPLATE_DIR');

        if ($this->templateDir == '') {
            throw new TemplateNotFoundException();
        }

        if ($this->needsToBeLoggedIn and !$this->isLoggedIn() and !headers_sent()) {
            header('Location: /login');
        }

        $this->template = new Template($this->templateDir);
        $this->template->set('errorMessage', $this->bootstrap->getSession()->getErrorMessage());
        $this->template->set('notificationMessage', $this->bootstrap->getSession()->getNotificationMessage());
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->template->set('title', $title);
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->template->set('description', $description);
    }

    /**
     * @param User $user
     */
    protected function login(User $user)
    {
        $this->session->set(self::USER_SESSION_BAG, $user->getUserIdentifier());
    }

    /**
     * Destroy all humans...
     */
    protected function logout()
    {
        $this->session->set(self::USER_SESSION_BAG, null);
    }

    /**
     * User can be null or User->getUserIdentifier()
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        $userIdentifier = $this->session->get(self::USER_SESSION_BAG);

        if (is_null($userIdentifier)) {
            return false;
        }

        return (!is_null($userIdentifier));
    }

    protected function getLoggedInUserIdentifier()
    {
        return $this->session->get(self::USER_SESSION_BAG);
    }
}