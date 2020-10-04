<?php

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\View\View;
use \MyProject\Exceptions\DbException;
use \MyProject\Exceptions\UnauthorizedException;

require __DIR__ . '/../vendor/autoload.php';
$routes = require __DIR__.'/../src/routes.php';

$route = $_GET['route'] ?? '';
$isRouteFound = false;

foreach ($routes as $pattern => $controllerNameAndAction) {
    preg_match($pattern, $route, $matches);
    if (!empty($matches)) {
        $isRouteFound = true;
        break;
    }
}

try {
    if (!$isRouteFound) {
        throw new NotFoundException('Wrong page');
    }
    unset($matches[0]);
    $controllerName = $controllerNameAndAction[0];
    $controllerAction = $controllerNameAndAction[1];

    $controller = new $controllerName();
    $controller->$controllerAction(...$matches);
} catch (DbException $exception) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php',['error' => $exception->getMessage(), 'title' => 'Error 500'], 500);
} catch (NotFoundException $exception) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php',['error' => $exception->getMessage(), 'title' => 'Error 404'], 404);
} catch (UnauthorizedException $exception) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php',['error' => $exception->getMessage(), 'title' => 'Error 401'], 401);
} catch (ForbiddenException $exception) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $exception->getMessage(), 'title' => 'Error 403'], 403);
} catch (InvalidArgumentException $exception) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('400.php', ['error' => $exception->getMessage(), 'title' => 'Error 400']);
    exit();
}
