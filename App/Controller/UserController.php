<?php

namespace App\Controller;

use App\Controller\Validation\Validator;
use App\Domain\User\UserDomain;
use App\Helper\Encrypt;
use App\Helper\Request;
use App\Infrastructure\PrivateKeyProvider\PrivateKeyProvider;
use App\Repository\UserRepository;
use Bootstrap;

class UserController extends AbstractController implements ControllerInterface
{
    protected UserDomain $userDomain;

    protected PrivateKeyProvider $privateKeyProvider;
    public function __construct(Bootstrap $bootstrap)
    {
        parent::__construct($bootstrap);
        $this->userDomain = new UserDomain(new UserRepository($this->bootstrap->getDatabaseConnection()));

        // Could also place this in the abstract controller.
        // But I think at this point this is the only place we'll be needing this inside a controller
        $this->privateKeyProvider = new PrivateKeyProvider(getenv('PRIVATE_KEY'));
    }

    public function get(Request $request): ControllerInterface
    {
        $this->setTitle('Edit User');
        $this->setDescription('Edit User');
        $user = $this->userDomain->getByUuid($request->get('userIdentifier'));

        $this->template->set('userEmail', Encrypt::decrypt($this->privateKeyProvider, $user->getUserEmail()));
        $this->template->set('userName', Encrypt::decrypt($this->privateKeyProvider, $user->getUserName()));
        $this->template->set('userPassword', Encrypt::decrypt($this->privateKeyProvider, $user->getUserPassword()));
        $this->template->set('userIdentifier', $user->getUserIdentifier());

        // Todo: Using payload (sha?)

        $this->template->render('userGet');

        return $this;
    }

    public function post(Request $request): ControllerInterface
    {
        $validator = new Validator();
        $validationMessage = $validator->validateRegistration(
            $request->get('userName'),
            $request->get('userEmail'),
            $request->get('userPassword'),
            $request->get('userPasswordRepeat')
        );
        if ($validationMessage != '') {
            $this->session->setErrorMessage($validationMessage);
            header('Location: /user/?userIdentifier='. $request->get('userIdentifier'));
            return $this;
        }
        $user = $this->userDomain->getByUuid($request->get('userIdentifier'));
        if ($user->getUserIdentifier() == null or $user->getUserIdentifier() == '') {

            // In this case something really odd happened with the input fields. We'll just stop it here.
            die('Game over... please insert coin');
        }
        // Todo: modify the fields with encrypted values first.
        // Todo: might need to do this with a different entity (UserEncrypted?)

        // These are the fields we are modifying.
        $user->setUserName(Encrypt::encrypt($this->privateKeyProvider, $request->get('userName')));
        $user->setUserPassword(Encrypt::encrypt($this->privateKeyProvider, $request->get('userPassword')));
        $user->setUserEmail(Encrypt::encrypt($this->privateKeyProvider, $request->get('userEmail')));
        $this->userDomain->update($user);

        header('Location: /index');
        return $this;
    }

    /**
     * @param Request $request
     * @return ControllerInterface
     */
    public function delete(Request $request): ControllerInterface
    {
        $loggedInUser = $this->getLoggedInUserIdentifier();
        if ($loggedInUser == $request->get('userIdentifier')) {
            $this->session->setErrorMessage('Why are you trying to remove yourself from reality?');
            header('Location: /index');
            return $this;
        }
        $user = $this->userDomain->getByUuid($request->get('userIdentifier'));
        if ($user->getUserIdentifier() == null or $user->getUserIdentifier() == '') {
            die('Game over... please insert coin');
        }

        $this->userDomain->delete($user);
        header('Location: /index');
        return $this;
    }
}