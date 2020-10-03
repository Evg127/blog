<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\DbException;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserAuthService;
use MyProject\View\View;

/**
 * Class AbstractController
 * @package MyProject\Controllers
 */
class AbstractController
{
    /**
     * @var View
     */
    protected $view;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * AbstractController constructor.
     * @throws DbException
     */
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates/');
        $this->user = UserAuthService::getByToken();
        $this->view->setAdditionData('user', $this->user);
        if ($this->user !== null) {
            $this->user->updateLastVisitTime();
        }
    }
}