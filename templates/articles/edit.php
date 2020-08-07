<?php
/**
 * @var Article $article
 */

use MyProject\Models\Articles\Article;
$title = 'article\'s edit';
include __DIR__ . '/../header.php';
?>
<a href="/articles/<?=$article->getId()?>"><button>To article</button></a>
<h1>Article edit form</h1>
<?php if(!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
<form action="<?= $prefix ? '/' . $prefix : ''?>/articles/<?= $article->getId() ?>/edit" method="post">
    <label for="name">Article Name</label><br>
    <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? $article->getName() ?>" size="50"><br>
    <br>
    <label for="text">Article text</label><br>
    <textarea name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? $article->getText() ?></textarea><br>
    <br>
    <input type="submit" value="Save">
</form>
<?php include __DIR__ . '/../footer.php'; ?>
