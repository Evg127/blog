<hr>
<hr>
<a><h3>COMMENTS</h3></a>
<a href=/articles/<?=$article->getId()?>/comments/add><input type="submit" value="add new"></a>
<?php if (!empty($comments)): ?>
<?php foreach ($comments as $comment): ?>
    <p>
        <?php if ($comment->getAuthor()->getAvatar('Mini') !== null): ?>
            <img src="<?=$comment->getAuthor()->getAvatar('Mini')?>" alt="User's MiniAvatar">
        <?php endif ?>
        <i style="font-size: small"><?= $comment->getAuthor()->getNickname().' | '.$comment->getCreatedAt() ?></i>
        <br>
        &#10078;<?= $comment->getCommentText()?>&#10078;
        <?php if (!empty($comment->getSignature())): ?>
        <p>
            <i style="font-size: small"><br><b><?= $comment->getSignature()?></b></i>
        </p>
        <?php endif ?>
    <p><?php if ($user !== null && $user->isAdmin()):?>

       <a href="/articles/<?=$article->getId()?>/comments/<?=$comment->getId()?>/edit">edit</a> |
       <a href="/articles/<?=$article->getId()?>/comments/<?=$comment->getId()?>/delete">delete</a>
       <?php endif ?>
    </p>
    <hr>
<?php endforeach;?>
<?php else : ?>
    <p>No comments yet</p>
<?php endif ?>

