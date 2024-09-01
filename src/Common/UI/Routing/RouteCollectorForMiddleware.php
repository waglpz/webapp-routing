<?php

declare(strict_types=1);

namespace Waglpz\Route\Common\UI\Routing;

use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Waglpz\Webapp\Middleware\MiddlewareStackFactory;

final class RouteCollectorForMiddleware
{
    private RouteCollector $routeCollector;
    private ContainerInterface $container;

    public function __construct(private readonly MiddlewareStackFactory $middleware)
    {
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setWrappedRouteCollector(RouteCollector $routeCollector): void
    {
        $this->routeCollector = $routeCollector;
    }

    /**
     * Adds a route to the collection.
     *
     * @param string|string[] $httpMethod
     * @param class-string    $handler
     */
    public function addRoute(string|array $httpMethod, string $route, string $handler): void
    {
        if (! $this->container->has($handler)) {
            throw new \RuntimeException(
                \sprintf(
                    'Can not build an instance via DI Container of given Type. Got "%s"',
                    $handler,
                ),
            );
        }

        $handlerObject = $this->container->get($handler);
        \assert(\is_callable($handlerObject));
        $middlewareHandler = $this->middleware->create($handlerObject);
        $this->routeCollector->addRoute($httpMethod, $route, $middlewareHandler);
    }

    /** @param class-string $handler */
    public function get(string $route, string $handler): void
    {
        $this->addRoute('GET', $route, $handler);
    }

    /** @param class-string $handler */
    public function post(string $route, string $handler): void
    {
        $this->addRoute('POST', $route, $handler);
    }

    /** @param class-string $handler */
    public function put(string $route, string $handler): void
    {
        $this->addRoute('PUT', $route, $handler);
    }

    /** @param class-string $handler */
    public function delete(string $route, string $handler): void
    {
        $this->addRoute('DELETE', $route, $handler);
    }

    /** @param class-string $handler */
    public function patch(string $route, string $handler): void
    {
        $this->addRoute('PATCH', $route, $handler);
    }

    /** @param class-string $handler */
    public function head(string $route, string $handler): void
    {
        $this->addRoute('HEAD', $route, $handler);
    }
}
