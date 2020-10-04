<?php use MyProject\Models\Comments\Comment;

include_once __DIR__.'/../header.php'; ?>
<?php if ($user !== null && $user->isAdmin()):?><a href="/articles/add"><button>Add new article</button></a><hr>
<?php endif?>
<?php if (!empty($articles)):?>
<?php foreach ($articles as $article):?>
    <h2><a href="/articles/<?=$article->getId()?>"><?=$article->getName()?></a></h2>
        <?=$article->getParsedShortText300()?>
        <p style="font-size: 10pt"><b>Posted at</b> <?=$article->getStringCreatedAt()?>
            <b>by</b> <?=$article->getAuthor()->getNickname()?>
        <?php
        $comments = Comment::getAllByColumn('article_id', $article->getId());
        echo $comments !== null ? ' | Comments (' . count($comments) . ')' : '';
        ?>
    </p>
    <hr>
<?php endforeach;?>
    <div class="paginator">
        <?php include_once __DIR__ . '/../services/paginator.php'; ?>
    </div>
<?php else:?>
    <p>There is no articles yet</p>
<?php endif;?>
<?php include_once __DIR__.'/../footer.php'; ?>