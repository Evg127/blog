<?php include_once __DIR__.'/../header.php'; ?>
<a><button onclick='location.href="/users/<?=$userId?>"'>Back to User profile</button>
<?php if ($articles !== null):?>
    <?php foreach ($articles as $article):?>
        <h2><a href="/articles/<?=$article->getId()?>"><?=$article->getName()?></a></h2>
        <p><?=$article->getParsedText()?></p>
        <hr>
    <?php endforeach;?>
<?php else:?>
    <p>There is no articles yet</p>
<?php endif;?>
<?php include_once __DIR__.'/../footer.php'; ?>