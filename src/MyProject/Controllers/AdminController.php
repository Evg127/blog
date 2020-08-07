<?php


namespace MyProject\Controllers;


use MyProject\Exceptions\DbException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;

/**
 * Class AdminController
 * @package MyProject\Controllers
 */
class AdminController extends AbstractController
{
    /**
     * AdminController constructor.
     * @throws ForbiddenException
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->user === null || !$this->user->isAdmin()) {
            throw new ForbiddenException('Only admin allowed to enter admin panel');
        }
    }

    public function main(): void
    {
        $this->view->renderHtml('admin/main.php');
        return;
    }

    /**
     * @throws DbException
     */
    public function users()
    {
        $users = User::getAll();
        $this->view->renderHtml('users/users.php', ['users' => $users]);
        return;
    }

    /**
     * @throws DbException
     */
    public function articles()
    {
        $articles = Article::getAll();
        $this->view->renderHtml('articles/viewAll.php', ['articles' => $articles]);
        return;
    }

    public function comments()
    {
        $comments = Comment::getAll();
        $this->view->renderHtml('comments/allComments.php', ['comments' => $comments]);
        return;
    }
}