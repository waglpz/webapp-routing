<?php

declare(strict_types=1);

//use function Waglpz\Route\groupByPrefix;

use FastRoute\RouteCollector;
//use Waglpz\GoogleSSO\Login;

return static function (RouteCollector $router): void {
    /*
    *=================================================*
    | Example route register in the group with a HASH |
    *=================================================*

    if (\session_status() === \PHP_SESSION_NONE) {
        \session_start();
    }

    $prefixes = $_SESSION['hash_route'] ?? null;

    $prefixes = $_SESSION['hash_route'] ?? null;
    \Waglpz\Route\groupByPrefix(
        \Waglpz\Route\Example\Routes::EXAMPLE_ROUTE,
        ExampleRouteHandler::class,
        $router,
        $prefixes,
        ['GET', 'POST'],
    );
    */

    /*
    *===============================================*
    | Example route register for REST API component |
    *===============================================*

    $router->addGroup(
        '/api',
        static function (RouteCollector $routeCollector): void {
            $routeCollector->get('/ping', Ping::class);
            $routeCollector->get('/doc', SwaggerUI::class);
            $routeCollector->get('/doc.json', SwaggerUI::class);
        },
    );
    */

    /*
    *====================================*
    | Example route register for "/" Url |
    *====================================*

    $router->get('/', Index::class);
    */

    /*
    *========================================================*
    | Example route Google SSO component "/login" controller |
    *========================================================*

    $router->get('/login', Login::class);
    */

    // Example Route definition
    //$router->get(
    //    '/', get_class((new class() {
    //           public function __invoke(ServerRequestInterface $request): ResponseInterface
    //           {
    //               $response = new Response();
    //               $response->getBody()->write('Hallo World ;)');
    //                return  $response;
    //           }
    //       }))
    //);
};
