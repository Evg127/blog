<?php include_once __DIR__.'/../header.php'; ?>
<a><button onclick='location.href="/users/<?=$authorId?>"'>Back to User profile</button>
<?php if (!empty($comments)): ?>
    <?php foreach ($comments as $comment): ?>
        <p>
            <?= $comment->getCreatedAt() ?>
            <br>
            <?= $comment->getCommentText()?>
        </p>
        <p><?php if ($user !== null && $user->isAdmin()):?>
                <a href="/articles/<?=$comment->getArticleId()?>/comments/<?=$comment->getId()?>/edit">edit</a> |
                <a href="/articles/<?=$comment->getArticleId()?>/comments/<?=$comment->getId()?>/delete">delete</a>
            <?php endif ?>
        </p>
        <hr>
    <?php endforeach;?>
<?php else : ?>
    <p>No comments yet</p>
<?php endif ?>
<?php include_once __DIR__.'/../footer.php'; ?>
