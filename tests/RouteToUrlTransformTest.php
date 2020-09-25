<?php

declare(strict_types=1);

namespace Waglpz\Route\Tests;

use PHPUnit\Framework\TestCase;
use Waglpz\Route\Url;

final class RouteToUrlTransformTest extends TestCase
{
    /** @test */
    public function withArgumentAndQuery(): void
    {
        $fact = Url::forRoute('/a/{p:\d}', ['p' => 1], ['q' => '2', 's' => ' +!#'], Url::RETAIN_NOT);
        self::assertSame('/a/1?q=2&s=+%2B%21%23', $fact);
    }

    /** @test */
    public function withWebBaseArgumentAndQuery(): void
    {
        $url  = new Url('/web/base');
        $fact = $url('/a/{p:\d}', ['p' => 1], ['q' => '2', 's' => ' +!#'], Url::RETAIN_NOT);
        self::assertSame('/web/base/a/1?q=2&s=+%2B%21%23', $fact);
    }

    /** @test */
    public function withArgumentAndQueryAndRetainHash(): void
    {
        $fact = Url::forRoute('/a/{p:\d}', ['p' => 1], ['q' => '2', 's' => ' +!#'], Url::RETAIN_HASH);
        self::assertSame('/h~32612e9b/a/1?q=2&s=+%2B%21%23', $fact);
    }

    /** @test */
    public function withArgumentAndQueryAndRetainRouteHash(): void
    {
        $fact = Url::forRoute('/a/{p:\d}', ['p' => 1], ['q' => '2', 's' => ' +!#'], Url::RETAIN_ROUTE_HASH);
        self::assertSame('/rh~9a644b36/a/1?q=2&s=+%2B%21%23', $fact);
    }

    /** @test */
    public function withoutOptionalArgument(): void
    {
        $fact = Url::forRoute('/a/[{p:\d}]', null, null, Url::RETAIN_NOT);
        self::assertSame('/a/', $fact);
    }

    /** @test */
    public function throwsAnErrorWithoutRequiredRouteArgument(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "{p:\d}" für die Route "/a/{p:\d}" wurde nicht angegeben.');
        Url::forRoute('/a/{p:\d}');
    }

    /** @test */
    public function throwsAnErrorNotEnoughArguments(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ungültige Argumente für die Route "/a/{p:\d}/{p1:\d}" angegeben: "p1:\d".');
        Url::forRoute('/a/{p:\d}/{p1:\d}', ['p' => 1], null, Url::RETAIN_NOT);
    }

    /** @test */
    public function throwsAnErrorArgumentNotMatchedPattern(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Wert "b" für Argument "p" in der Route "/a/{p:\d}" entspricht nicht den Pattern "\d".'
        );
        Url::forRoute('/a/{p:\d}', ['p' => 'b'], null, Url::RETAIN_NOT);
    }
}
