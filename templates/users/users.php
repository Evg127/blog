<?php include __DIR__.'/../header.php'; ?>
<?php if ($user !== null && $user->isAdmin()):?>
    <h2>USERS</h2>
    <?php foreach ($users as $user): ?>
        <p>
            <a class="<?= $user->isOnline() ? 'online' : 'offline';?>"
               href="/users/<?=$user->getId()?>"
               style = "color:<?= $user->getRole() === 'admin' ? 'red' : 'green'?>">
                <?=$user->getNickname()?>
            </a>
        </p>

        <hr>
    <?php endforeach; ?>
<?php endif?>
<?php include __DIR__.'/../footer.php'; ?>