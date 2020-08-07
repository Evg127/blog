<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserActivationService;
use MyProject\Models\Users\UserAuthService;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Services\EmailSender;
use MyProject\Exceptions\DbException;
use MyProject\Services\Flasher;
use MyProject\Services\ImageServices;

/**
 * Class UsersController
 * @package MyProject\Controllers
 */
class UsersController extends AbstractController
{
    /**
     * @throws DbException
     */
    public function registration()
    {
        if (!empty($_POST)) {
            try {
                $user = User::registration($_POST);
            } catch (InvalidArgumentException $exception) {
                $this->view->renderHtml('users/register.php', ['error' => $exception->getMessage()]);
                return;
            }
            if ($user instanceof User) {
                $code = UserActivationService::activationCodeCreate($user);
                $message = 'New user with nickname <b>'.$user->getNickname().'</b> created.<br>Check your email, noticed during registration for activation link';
                $this->view->renderHtml('successful/successful.php', ['message' => $message]);
                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);
                return;
            } else {
                $message = 'Something went wrong';
                $this->view->renderHtml('unsuccessful/unsuccessful.php', ['message' => $message]);
                return;
            }
        }
        $this->view->renderHtml('users/register.php');
    }

    /**
     * @param int $userId
     * @param string $code
     * @throws DbException
     */
    public function activate(int $userId, string $code)
    {
        if (UserActivationService::activationCodeCheck($userId, $code)) {
            UserActivationService::activate($userId);
            $message = 'Your account is activated now';
            UserActivationService::activationCodeDelete($userId);
            $this->view->renderHtml('successful/successful.php', ['message' => $message]);
            return;
        } else {
            $message = 'Something wrong with activation data. Check your activation link.';
            $this->view->renderHtml('unsuccessful/unsuccessful.php', ['message' => $message]);
            return;
        }
    }

    /**
     * @throws DbException
     */
    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
            } catch (InvalidArgumentException $exception) {
                $this->view->renderHtml('users/login.php', ['error' => $exception->getMessage()]);
                return;
            }
            if ($user instanceof User) {
                UserAuthService::tokenCreate($user);
                header('Location: /');
                exit;
            }
        }
        $this->view->renderHtml('users/login.php');
        return;
    }

    /**
     * @throws UnauthorizedException
     */
    public function logout()
    {
        UserAuthService::tokenDelete();
        header('Location: /');
    }

    /**
     * @param int $id
     * @throws DbException
     * @throws InvalidArgumentException
     * @throws ForbiddenException
     */
    public function view(int $id)
    {
        if ($this->user === null || !$this->user->isAdmin()) {
            throw new ForbiddenException('Only admin is allowed');
        }
        $userById = User::getById($id);
        if ($userById === null) {
            throw new InvalidArgumentException('Wrong user id passed');
        }
        $this->view->renderHtml('users/user.php', ['userById' => $userById]);
        return;
    }

    /**
     * @param int $id
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function roleControl(int $id)
    {
        $userById = User::getById($id);
        if ($userById === null) {
            throw new InvalidArgumentException('Wrong user id passed');
        }
        if (!empty($_POST)) {
            try {
                $role = $userById->changeAndSaveRole($_POST, $userById);
            } catch (InvalidArgumentException $exception) {
                $this->view->renderHtml('/users/user.php', ['message' => $exception->getMessage(), 'userById' => $userById]);
                return;
            }
            Flasher::set('successRoleControl', $userById->getNickname().'\'s role was successfully changed to "'.$role.'"');
            header('Location: /users/'.$id);
            exit();
        }

    }

    /**
     * @param int $id
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function activationControl(int $id)
    {
        $userById = User::getById($id);
        if ($userById === null) {
            throw new InvalidArgumentException('Wrong user id passed');
        }
        if (!empty($_POST)) {
            try {
                $modifiedUserById = UserActivationService::activationStatusChange($_POST, $userById);

            } catch (InvalidArgumentException $exception) {
                $this->view->renderHtml('/users/user.php', ['message' => $exception->getMessage(), 'userById' => $userById]);
                return;
            }
            Flasher::set('successActivationControl', $modifiedUserById->getNickname().' activation was successfully modified');
            header('Location: /users/'.$id);
            exit();
        }
    }

    /**
     * @param int $id
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function settings(int $id)
    {
        if ($this->user === null || $this->user->getId() !== $id) {
            throw new InvalidArgumentException('Wrong Id');
        }
        $activity = Comment::getAllByColumn('author_id', $id);
        $this->view->renderHtml('users/settings.php', ['activity' => $activity, 'user' => User::getById($id)]);
        return;
    }

    /**
     * @param int $id
     * @throws DbException
     * @throws InvalidArgumentException
     */
    public function signatureSet(int $id)
    {
        if ($this->user === null || $this->user->getId() !== $id) {
            throw new InvalidArgumentException('Wrong Id');
        }
        if (!empty($_POST)) {
            $this->user->signatureSet($_POST);
            header('Location: /users/' . $id . '/settings');
            exit();
        }
        $this->view->renderHtml('/users/signatureSet.php', ['user' => $this->user]);
        return;
    }

    /**
     * @param int $id
     * @throws InvalidArgumentException
     */
    public function avatarSet(int $id)
    {
        if ($this->user === null || $this->user->getId() !== $id) {
            throw new InvalidArgumentException('Wrong Id');
        }
        if (!empty($_POST)) {
            if (isset($_POST['avatar'])) {
                $avatar = $_POST['avatar'];
                if ($avatar === 'none') {
                    ImageServices::remove('users', $id);
                }
                if ($avatar === 'custom') {
                    if (!empty($_FILES['attachment']['name'])) {
                        ImageServices::upload($id, $_FILES['attachment']);
                    } else {
                        Flasher::set('error', 'For custom avatar some file must be chosen');
                    }
                }
            } else {
                Flasher::set('error', 'You have not chosen an option');
            }
        }
        $this->view->renderHtml('/users/avatarSet.php', ['user' => $this->user]);
        return;
    }
}
