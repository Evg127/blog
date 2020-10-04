<?php

namespace MyProject\Controllers;

use MyProject\Models\Users\User;
use MyProject\Exceptions\DbException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;

/**
 * Class ArticlesController
 * @package MyProject\Controllers
 */
class ArticlesController extends AbstractController
{
    /**
     * @param int $articleId
     * @throws DbException
     * @throws NotFoundException
     */
    public function view(int $articleId)
    {
        $article = Article::getById($articleId);
        if ($article === null) {
            throw new NotFoundException('ERROR_404. Page not found');
        }
        $comments = Comment::getAllByColumn('article_id', $articleId);
        $title = $article->getName();
        $this->view->renderHtml('articles/view.php', ['article' => $article, 'comments' => $comments, 'title' => $title]);
    }

    /**
     * @param int $userId
     * @throws DbException
     * @throws NotFoundException
     */
    public function articlesByUser(int $userId)
    {
        $user = User::getById($userId);
        if ($user === null) {
            throw new NotFoundException('Wrong user id passed');
        }
        $articles = Article::getAllByColumn('author_id', $userId);
        $title = 'Articles from ' . $user->getNickname();
        $this->view->renderHtml('articles/allArticlesByUser.php', ['articles' => $articles, 'userId' => $userId, 'title' => $title]);
    }

    /**
     * @param $articleId
     * @throws DbException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException('ERROR_404. Wrong article id');
        }
        if ($this->user === null) {
            throw new UnauthorizedException('Unauthorized access');
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only admin can add articles');
        }
        if (!empty($_POST)) {
            try {
                $article->updateFromEditForm($_POST);
            } catch (InvalidArgumentException $exception) {
                $this->view->setAdditionData('article', $article);
                $this->view->renderHtml('/articles/edit.php', ['error' => $exception->getMessage(), 'title' => 'Edit article']);
                return;
            }
            header('Location: /articles/'.$articleId, true, 302);
            exit();
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article, 'title' => 'Edit article']);
    }

    /**
     * @return void
     * @throws UnauthorizedException
     * @throws ForbiddenException|DbException
     */
    public function add(): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException('Unauthorized access');
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only admin can add articles');
        }
        if (!empty($_POST)) {
            try {
                $article = Article::createFromCreateForm($_POST, $this->user->getId());
            } catch (InvalidArgumentException $exception) {
                $this->view->setAdditionData('user', $this->user);
                $this->view->renderHtml('articles/add.php', ['error' => $exception->getMessage(), 'title' => 'Add article']);
                return;
            }
            header('Location: /articles/'.$article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/add.php', ['title' => 'Add article']);
    }

    /**
     * @param $articleId
     * @throws DbException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function delete($articleId): void
    {
        $article = Article::getById($articleId);
        if ($article === null) {
            throw new NotFoundException('ERROR_404. Wrong article id');
        }
        if ($this->user === null) {
            throw new UnauthorizedException('Unauthorized access');
        }
        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only admin can add articles');
        }
        $article->delete();
        $message = 'Article "'.$article->getName().'" was successfully deleted';
        Comment::deleteAllByColumn('article_id', $articleId);
        $this->view->renderHtml('successful/successful.php', ['message' => $message, 'title' => 'Success']);
        return;
    }
}