<?php $title = 'comment\s edit'?>?
<?php include __DIR__ . '/../header.php'; ?>
    <a><button onclick='location.href="/articles/<?=$articleId?>"'>Back to article</button></a>
    <h1>Comment edit form</h1>
<?php if(!empty($error)): ?>
    <div style="color: #ff0000;"><?= $error ?></div>
<?php endif; ?>
    <form action="/articles/<?=$comment->getArticleId()?>/comments/<?=$comment->getId()?>/edit" method="post">

        <label for="text">Current comment:</label><br>
        <textarea name="text" id="text" rows="4" cols="80"><?= $_POST['text'] ?? $comment->getcommentText() ?></textarea><br>
        <br>
        <input type="submit" value="Save">
    </form>
<?php include __DIR__ . '/../footer.php'; ?>