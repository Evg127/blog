<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$title ?? ''?></title>
    <link rel="stylesheet" href="/styles.css">
    <link rel="icon" href="/images/common/favicon.ico" type="image/x-icon">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="<?=$colspan ?? '2'?>" class="header">
            BLOG TEMPLATE
        </td>
    </tr>
    <tr>

        <td colspan = "<?=$colspan ?? '2'?>">
            <div class="display-flex">
                <div>
                    <?php if (isset($user) && $user !== null && $user->isAdmin()):?>
                        <ul class="list">
                            <li><a href="/admin/articles">All articles</a></li>
                            <li><a href="/admin/comments">All Comments</a></li>
                            <li><a href="/admin/users">All Users</a></li>
                        </ul>
                    <?php endif?>
                </div>
                <div>
                    <?php if (!isset($error)):?>
                    <?php if (isset($user) && $user !== null): ?>
                        Hello, <?= $user->getNickname() ?> | <a href="/users/logout">Log out</a>  | <a href="/users/<?=$user->getId()?>/settings">&#9881; Settings</a>
                    <?php else: ?>
                        <a href="/users/login">Log in</a> | <a href="/users/register">Sign Up</a>
                    <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <?php if (isset($settingsOn)): ?>
        <td width="150px" class="sidebar">
            <div class="sidebarHeader">PROFILE EDIT</div>
            <ul>
                <li><a href="/users/<?=$user->getId()?>/settings/avatar">Avatar edit</a></li>
                <li><a href="/users/<?=$user->getId()?>/settings/signature">Signature edit</a></li>
            </ul>
        </td>
        <?php endif ?>
        <td style="overflow:auto">