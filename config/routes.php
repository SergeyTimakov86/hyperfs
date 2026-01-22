<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET'], '/', \App\Infra\Endpoint\Index::class);
Router::addRoute(['GET', 'POST', 'HEAD'], '/test', \App\Infra\Endpoint\Test::class);

Router::addRoute(['GET'], '/admin/account', \App\Infra\Endpoint\Admin\Account\Index::class);
Router::addRoute(['POST'], '/admin/account', \App\Infra\Endpoint\Admin\Account\Add::class);
Router::addRoute(['GET', 'POST'], '/admin/b', \App\Infra\Endpoint\Admin\B::class);
Router::addRoute(['GET', 'POST'], '/admin/c', \App\Infra\Endpoint\Admin\C::class);

Router::get('/favicon.ico', static function () {
    return '';
});
