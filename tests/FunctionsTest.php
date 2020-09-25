<?php

declare(strict_types=1);

namespace Waglpz\Route\Tests;

use FastRoute\RouteCollector;
use PHPUnit\Framework\TestCase;
use function Waglpz\Route\groupByPrefix;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function groupWhenPrefixesAreEmpty() : void
    {
        $routeCollector = $this->createMock(RouteCollector::class);
        $routeCollector->expects(self::once())
                       ->method('addRoute')
                       ->with(['GET'], '/test-route', \stdClass::class);
        $prefixes = [];

        groupByPrefix('/test-route', \stdClass::class, $routeCollector, $prefixes);
    }

    /** @test */
    public function groupByPrefixes() : void
    {
        $routeCollector = $this->createMock(RouteCollector::class);
        $routeCollector->expects(self::once())
                       ->method('addGroup')
                       ->with('xxx', self::isInstanceOf(\Closure::class));
        $prefixes = [
            '/test-route' => ['xxx' => true],
        ];

        groupByPrefix('/test-route', \stdClass::class, $routeCollector, $prefixes);
    }

    /** @test */
    public function throwInvalidArgumentExceptionWhenPrefixToShort() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ungültiger Hash Wert erwartet string.');
        $routeCollector = $this->createMock(RouteCollector::class);
        $routeCollector->expects(self::never())->method('addGroup');
        $prefixes = [
            '/test-route' => ['xx' => true],
        ];

        groupByPrefix('/test-route', \stdClass::class, $routeCollector, $prefixes);
    }

    /** @test */
    public function throwInvalidArgumentExceptionWhenPrefixOfFalseType() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ungültiger Hash Wert erwartet string.');
        $routeCollector = $this->createMock(RouteCollector::class);
        $routeCollector->expects(self::never())->method('addGroup');
        $prefixes = [
            '/test-route' => [0 => true],
        ];

        groupByPrefix('/test-route', \stdClass::class, $routeCollector, $prefixes);
    }
}
