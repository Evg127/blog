<p>
    <a href="<?=$pagination->getFirst()?>">&nbsp<<</a>
    <a href="<?=$pagination->getPrev()?>"><&nbsp</a>
    <?php if (!$pagination->isStart()): ?>
    <span>...</span>
    <?php endif ?>
    <?php foreach ($pagination->getPaginatorLinksArray() as $page): ?>
            <?php if ($page->isCurrent):?>
                <b><?= $page->number ?></b>
            <?php else: ?>
                <a href="<?= $page->link ?>"><?= $page->number ?></a>
            <?php endif ?>
    <?php endforeach ?>
    <?php if (!$pagination->isEnd()): ?>
    <span>...</span>
    <?php endif ?>
    <a href="<?=$pagination->getNext()?>">&nbsp></a>
    <a href="<?=$pagination->getLast()?>">&nbsp>></a>
</p>

