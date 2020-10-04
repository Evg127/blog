<?php
include __DIR__.'/../header.php'; ?>
    <a><button onclick='location.href = "/users/<?=$user->getId()?>/settings"'>Back to user's settings</button></a>
<form action="/users/<?=$user->getId()?>/settings/signature" method="post">
    <hr><br>
    <label for="text">Your signature:</label><br>
    <textarea name="text" id="text" rows="4" cols="80"><?= $user->getSignature() ?? '' ?></textarea><br>
    <br>
    <input type="submit" value="Save">
</form>
<?php include __DIR__.'/../footer.php'; ?>