<?php $title = 'admin panel';?>
<?php if ($user !== null && $user->isAdmin()):?>
    <?php include __DIR__ . '/../header.php'; ?>
<p>
    Some Admin settings and information
</p>
    <?php include __DIR__ . '/../footer.php'; ?>
<?php endif?>
