<?php


return [
    '~^hello/(.*)$~' => [\MyProject\Controllers\MainController::class, 'sayHello'],
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
    '~^blog/(\d*)$~' => [\MyProject\Controllers\MainController::class, 'main'],
    '~^about$~' => [\MyProject\Controllers\MainController::class, 'about'],
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^(admin)/articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'delete'],
    '~^users/register$~' => [\MyProject\Controllers\UsersController::class, 'registration'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],
    '~^users/logout$~' => [\MyProject\Controllers\UsersController::class, 'logout'],
    '~^users/(\d+)/settings$~' => [\MyProject\Controllers\UsersController::class, 'settings'],
    '~^users/(\d+)/settings/signature$~' => [\MyProject\Controllers\UsersController::class, 'signatureSet'],
    '~^users/(\d+)/settings/avatar$~' => [\MyProject\Controllers\UsersController::class, 'avatarSet'],
    '~^articles/(\d+)/comments/add$~' => [\MyProject\Controllers\CommentsController::class, 'add'],
    '~^articles/(\d+)/comments/(\d+)/delete$~' => [\MyProject\Controllers\CommentsController::class, 'delete'],
    '~^articles/(\d+)/comments/(\d+)/edit$~' => [\MyProject\Controllers\CommentsController::class, 'edit'],
    '~^admin$~' => [\MyProject\Controllers\AdminController::class, 'main'],
    '~^admin/users$~' => [\MyProject\Controllers\AdminController::class, 'users'],
    '~^users/(\d+)$~' => [\MyProject\Controllers\UsersController::class, 'view'],
    '~^users/(\d+)/roleControl$~' => [\MyProject\Controllers\UsersController::class, 'roleControl'],
    '~^users/(\d+)/activationControl$~' => [\MyProject\Controllers\UsersController::class, 'activationControl'],
    '~^users/(\d+)/articles$~' => [\MyProject\Controllers\ArticlesController::class, 'articlesByUser'],
    '~^admin/articles$~' => [\MyProject\Controllers\AdminController::class, 'articles'],
    '~^users/(\d+)/comments$~' => [\MyProject\Controllers\CommentsController::class, 'commentsByUser'],
    '~^admin/comments$~' => [\MyProject\Controllers\AdminController::class, 'comments'],
];