<?php use MyProject\Services\Flasher;

$title = 'user profile';
include __DIR__.'/../header.php'; ?>
<?php if (isset($user) && $user->isAdmin()):?>
    <h3>USER INFO:</h3>
    <ul class="user">
        <?php if ($userById->getAvatar() !== null): ?>
            <li><img src="<?=$userById->getAvatar();?>" alt="User's avatar"></li>
        <?php endif ?>
        <li><b>Nickname:</b> <?=$userById->getNickname()?></li>
        <li><b>Role:</b> <?=$userById->getRole()?></li>
        <li><b>Registration date:</b> <?=$userById->getCreatedAt()?></li>
        <?php if ($user !== null && $user->isAdmin()):?>
            <li><b>Email:</b> <?=$userById->getEmail()?></li>
        <?php endif?>
        <li><b>Last visit:</b> <?=$userById->getLastVisitTime()?></li>
        <li><b>Activate status:</b> <?=$userById->getIsConfirmed() === 1? ' activated' : ' not activated';?></li>
        <li><div><b>Online Status: <?= $userById->isOnline() ? '<div class="online"></div>' : '<div class="offline"></div>'?></b><div></li>
        <li><b><a href="/users/<?=$userById->getId()?>/articles">All articles from <?=$userById->getNickname()?></a></b></li>
        <li><b><a href="/users/<?=$userById->getId()?>/comments">All comments from <?=$userById->getNickname()?></a></b></li>
    </ul>
    <?php if ($userById->getNickname() !== $user->getNickname()):?>
        <hr>
        <h3>USER MODERATE:</h3>
        <form action="/users/<?=$userById->getId()?>/roleControl" method="post">
            Set new role:
                <?php if ($userById->getRole() === 'admin'):?>
                    <label>
                        <input type="radio" name="role" value="user" checked>User
                    </label>
                <?php elseif ($userById->getRole() === 'user'):?>
                    <label>
                        <input type="radio" name="role" value="admin" checked>Admin
                    </label>
                <?php endif?>
        <input type="submit"  name="button" value = Confirm>
        <?php if ($successMessage = Flasher::get('successRoleControl')): ?>
            <p style="color: green"><?= $successMessage ?></p>
        <?php endif ?>

        <?php if ($errorMessage = Flasher::get('errorRoleControl')): ?>
            <p style="color: red"><?= $errorMessage ?></p>
        <?php endif ?>
        </form>
    <br>
        <form action="/users/<?=$userById->getId()?>/activationControl" method="post">
            User activation control:
        <?php if (!$userById->getIsConfirmed()):?>
            <label>
                <input type="radio" name = "activation" value="activate" checked>activate
            </label>
        <?php elseif ($userById->getIsConfirmed()):?>
            <label>
                <input type="radio" name="activation" value="deactivate" checked>deactivate
            </label>
        <?php endif ?>
            <input type="submit"  name="button" value = Confirm>
            <?php if ($successMessage = Flasher::get('successActivationControl')): ?>
                <p style="color: green"><?= $successMessage ?></p>
            <?php endif ?>

            <?php if ($errorMessage = Flasher::get('errorActivationControl')): ?>
                <p style="color: red"><?= $errorMessage ?></p>
            <?php endif ?>
        </form>
    <?php endif?>
<?php endif?>
<?php include __DIR__.'/../footer.php'; ?>