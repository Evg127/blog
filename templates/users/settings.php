<?php
$title = 'user profile';
$colspan = 3;
$settingsOn = 1;
include __DIR__.'/../header.php'; ?>
LAST ACTIVITY:
<?php if (!empty($activity)): ?>
    <?php foreach ($activity as $comment): ?>
        <p>
            <i style="font-size: small"><u><?= $comment->getAuthor()->getNickname().' | '.$comment->getCreatedAt() ?></u></i>
        <br>
        &#10078;<?= $comment->getCommentText()?>&#10078;
        <? if (!empty($comment->getSignature())): ?>

        <i style="font-size: small"><br>Signature:<b><?= $comment->getSignature()?></b></i>

        <?php endif ?>
        </p>
        <a href="/articles/<?=$comment->getArticleId()?>"><i style="font-size: small">to article</i></a>
        <hr>
    <?php endforeach;?>
<?php else : ?>
    <p>No activity yet</p>
<?php endif ?>
<?php include __DIR__.'/../footer.php'; ?>