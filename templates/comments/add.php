<?php include_once __DIR__ . '/../header.php'; ?>
<?php if($user !== null): ?>
    <?php if(!empty($error)): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>
    <p><a><button onclick='location.href = "/articles/<?=$article->getId()?>"'>Back to article</button></a></p>
    <form action="/articles/<?=$article->getId()?>/comments/add" method="post">
        <label for="text">Add your Comment:</label><br>
        <textarea name="text" id="text" rows="4" cols="80"><?= $_POST['text'] ?? '' ?></textarea><br>
        <br>
        <input type="submit" value="Save">
    </form>
    <hr>
<?php endif; ?>
<?php include_once __DIR__ . '/../footer.php'; ?>
