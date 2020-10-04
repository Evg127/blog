<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\DbException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;

/**
 * Class CommentsController
 * @package MyProject\Controllers
 */
class CommentsController extends AbstractController
{
    /**
     * @param int $articleId
     * @throws DbException
     * @throws UnauthorizedException
     */
    public function add(int $articleId)
    {
        if ($this->user === null) {
            throw new UnauthorizedException('Only authorized users can add comments');
        }
        if (!empty($_POST)) {
            try {
                $comment = new Comment();
                $comment->add($_POST, $articleId, $this->user);
            } catch (InvalidArgumentException $exception) {
                $this->view->renderHtml('comments/add.php', [
                    'error' => $exception->getMessage(),
                    'article' => Article::getById($articleId),
                    'comments' => Comment::getAllByColumn('article_id', $articleId),
                    'title' => 'Add comment',
                ]);
                return;
            }
            header('Location: /articles/'.$articleId.'#comment'.$comment->getId());
            exit();
        }
        $this->view->renderHtml('comments/add.php', ['article' => $article = Article::getById($articleId), 'title' => 'Success']);
        return;
    }

    /**
     * @param int $articleId
     * @param int $commentId
     * @throws NotFoundException
     * @throws DbException|ForbiddenException
     */
    public function delete(int $articleId, int $commentId)
    {
        $comment = Comment::getById($commentId);
        if ($comment === null) {
            throw new NotFoundException('Wrong comment id passed');
        }
        if ($this->user->isAdmin()) {
            $comment->delete();
            header('Location: /articles/' . $articleId, true, 302);
            exit;
        } else {
            throw new ForbiddenException('Only admin can delete comments');
        }
    }

    /**
     * @param int $articleId
     * @param int $commentId
     * @throws DbException
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function edit(int $articleId, int $commentId)
    {
        $comment = Comment::getById($commentId);
        if ($comment === null) {
            throw new NotFoundException('Wrong comment id passed');
        }
        if ($this->user->getId() === $comment->getAuthorId() || $this->user->isAdmin()) {
            if (empty($_POST)) {
                $this->view->renderHtml('comments/edit.php', ['comment' => $comment, 'articleId' => $articleId, 'title' => 'Edit comment']);
                return;
            }

            try {
                $comment->edit($_POST);
            } catch (InvalidArgumentException $exception) {
                $this->view->setAdditionData('error', $exception->getMessage());
                $this->view->renderHtml('comments/edit.php', ['comment' => $comment, 'articleId' => $articleId, 'title' => 'Edit comment']);
                return;
            }
            header('Location: /articles/'.$articleId, true, 302);
            exit;
        } else {
            throw new ForbiddenException('Only admin can edit comments');
        }
    }

    /**
     * @param int $authorId
     * @throws DbException
     * @throws NotFoundException
     */
    public function commentsByUser(int $authorId)
    {
        $author = User::getById($authorId);
        if ($author === null) {
            throw new NotFoundException('Wrong author id passed');
        }
        $comments = Comment::getAllByColumn('author_id', $authorId);
        $title = 'Comments from ' . $author->getNickname();
        $this->view->renderHtml('comments/allCommentsByAuthor.php', ['comments' => $comments, 'authorId' => $authorId, 'title' => $title]);
    }
}