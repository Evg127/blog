<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\DbException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Services\Paginator;

/**
 * Class MainController
 * @package MyProject\Controllers
 */
class MainController extends AbstractController
{
    /**
     * @param int $currentPage
     * @throws DbException
     */
    public function main(int $currentPage = 1)
    {
        $articles = Article::getAll();
        if (is_array($articles)) {
            $pathForPaginationLinks = '/blog/';
            $pagination = new Paginator($currentPage, count($articles), $pathForPaginationLinks);
            $articles = Article::getByLimit( $pagination->getOffset(), $pagination->getPagesLimit());
        }
        $title = 'Blog/' . $currentPage;
        $this->view->renderHtml('main/main.php', ['articles' => $articles, 'pagination' => $pagination, 'title' => $title]);
        exit();
    }

    public function about()
    {
        $this->view->renderHtml('about.php', ['title' => 'About']);
        exit();
    }
}