<?php

declare(strict_types=1);

namespace Waglpz\Route;

use FastRoute\RouteCollector;

if (! \function_exists('Waglpz\Route\groupByPrefix')) {
    /**
     * @param array<mixed>|null $prefixes example ['/route-name' => ['xxx-xxx => true]]
     * @param array<string>     $methods  example ['GET', 'POST',]
     */
    function groupByPrefix(
        string $route,
        string $handlerClass,
        RouteCollector $router,
        ?array $prefixes,
        array $methods = ['GET']
    ) : void {
        if (isset($prefixes[$route])) {
            foreach (\array_keys($prefixes[$route]) as $hash) {
                if (! \is_string($hash) || \strlen($hash) < 3) {
                    throw new \InvalidArgumentException('Ungültiger Hash Wert erwartet string.');
                }

                $router->addGroup(
                    $hash,
                    static fn(RouteCollector $router) => $router->addRoute($methods, $route, $handlerClass)
                );
            }
        } else {
            $router->addRoute($methods, $route, $handlerClass);
        }
    }
}
