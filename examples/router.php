<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Waglpz\Route\Examples\ExampleRouteHandler;
use Waglpz\Route\Examples\IndexRouteHandler;

return static function (RouteCollector $router) : void {
    /*
     *=======================*
     | simple route register |
     *=======================*
    */
    $router->get('/', IndexRouteHandler::class);

    /*
     *=================================================*
     | Example route register in the group with a HASH |
     *=================================================*
    */

    $prefixes = $_SESSION['hash_route'] ?? null;
    \Waglpz\Route\groupByPrefix(
        \Waglpz\Route\Example\Routes::EXAMPLE_ROUTE,
        ExampleRouteHandler::class,
        $router,
        $prefixes,
        ['GET', 'POST']
    );

    /*
     *======================================================*
     | example how to skip route for a specific environment |
     *======================================================*
    */
    if (\constant('APP_ENV')  !== 'test') {
        return;
    }

    $router->get(
        '/still-forbidden-test-route',
        new class {
        }
    );
};
