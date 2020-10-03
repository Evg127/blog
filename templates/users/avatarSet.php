<?php
$title = 'avatar set';
use \MyProject\Services\Flasher;
use \MyProject\Services\ImageServices;
include __DIR__.'/../header.php';?>
    <a><button onclick='location.href="/users/<?=$user->getId()?>/settings"'>Back to user's settings</button></a>
    <form action="/users/<?=$user->getId()?>/settings/avatar" method="post" enctype="multipart/form-data">
        <hr>
        <h3>Your current avatar</h3>
        <?php if ($user->getAvatar()): ?>
            <img src="<?=$user->getAvatar();?>" alt="User's avatar">
            <br>
        <?php endif ?>
        <label>
            <input type="radio" name="avatar" value="none" <?=!$user->getAvatar() ? 'disabled' : '';?>>
            Do not use an avatar
        </label>
        <hr>
        <h3>Custom avatar</h3>
        <label>
            <input type="radio" name="avatar" value="custom" class="choose-file" disabled>
            Use custom avatar
        </label>
        <p style="font-size: small; color: gray"><i>Maximum size of custom image is 128*128 pixels and 20 KB<br>
           To change an avatar click the SAVE CHANGES button after an option choosing'</i></p>
        <p>
            <input type="file" name="attachment" id="attachment">
        </p>
        <script src="/JS/customAvatarSet.js">
        </script>
        <span style="color: red"><?=Flasher::get('error')?></span>
        <span style="color: green"><?=Flasher::get('success')?></span>
        <hr>
        <input type="submit" value="Save changes" name="post">
    </form>
<?php include __DIR__.'/../footer.php'; ?>