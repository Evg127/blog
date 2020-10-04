<?php include_once __DIR__.'/../header.php'; ?>
<?php foreach ($articles as $article):?>
    <h3><a href="/articles/<?=$article->getId()?>"><?=$article->getName()?></a></h3>
    <p style="font-size: small"><b>Posted at</b> <?=$article->getStringCreatedAt()?> <b>by</b> <?=$article->getAuthor()->getNickname()?>:  <i><?=$article->getParsedShortText50()?></i></p>
    <p><?php if ($user !== null && $user->isAdmin()):?>
            <a href="/admin/articles/<?=$article->getId()?>/edit">edit</a> |
            <a href="/articles/<?=$article->getId()?>/delete">delete</a>
        <?php endif ?>
    </p>
    <hr>
<?php endforeach;?>
<?php include_once __DIR__.'/../footer.php'; ?>