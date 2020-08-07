<?php
/**
 * @var Article $article
 */

use MyProject\Models\Articles\Article;
$title = 'article #'.$article->getId();
include_once __DIR__ . '/../header.php'; ?>
    <p>Author: <?=$article->getAuthor()->getNickname();?></p>
    <h2><?=$article->getName()?></h2>
    <p><?=$article->getParsedText()?></p>
    <p><?php if ($user !== null && $user->isAdmin()):?>
    <a href="/articles/<?=$article->getId()?>/edit">edit</a> |
    <a href="/articles/<?=$article->getId()?>/delete">delete</a>
    </p>
<?php endif?>
<?php include_once __DIR__ . '/../comments/allCommentsForArticle.php'; ?>
<?php include_once __DIR__ . '/../footer.php'; ?>