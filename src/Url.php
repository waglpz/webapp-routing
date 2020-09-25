<?php

declare(strict_types=1);

namespace Waglpz\Route;

use FastRoute\RouteParser\Std;

class Url
{
    public const RETAIN_NOT        = 2;
    public const RETAIN_ROUTE_HASH = 1;
    public const RETAIN_HASH       = 0;
    public const HASH_ALGO         = 'crc32';

    private string $webBase;

    public function __construct(string $webBase)
    {
        $this->webBase = $webBase;
    }

    /**
     * @param array<string,mixed>|null $routeArguments
     * @param array<string,mixed>|null $queryParams
     */
    public function __invoke(
        string $route,
        ?array $routeArguments = null,
        ?array $queryParams = null,
        int $retainHash = self::RETAIN_HASH
    ): string {
        return $this->webBase . self::forRoute($route, $routeArguments, $queryParams, $retainHash);
    }

    /**
     * @param array<string,mixed>|null $routeArguments
     * @param array<string,mixed>|null $queryParams
     */
    public static function forRoute(
        string $route,
        ?array $routeArguments = null,
        ?array $queryParams = null,
        int $retainHash = self::RETAIN_HASH
    ): string {
        $query            = $queryParams !== null ? '?' . \http_build_query($queryParams) : '';
        $routeComponents  = [];
        $routePlaceholder = null;
        $route            = (string) \preg_replace('/\s+/', '', $route);
        $parts            = (new Std())->parse($route);

        $reversedParts = \array_reverse($parts);

        foreach ($reversedParts as $partsOfParts) {
            foreach ($partsOfParts as $part) {
                if (\is_string($part)) {
                    $routeComponents[] = $part;
                    continue;
                }

                if (! isset($routeArguments)) {
                    if (\stripos($route, '[{' . \implode(':', $part) . '}]') !== false) {
                        break;
                    }

                    throw new \InvalidArgumentException(
                        \sprintf(
                            'Argument "{%s}" für die Route "%s" wurde nicht angegeben.',
                            \implode(':', $part),
                            $route
                        )
                    );
                }

                if (! \array_key_exists($part[0], $routeArguments)) {
                    $routeComponents  = [];
                    $routePlaceholder = \implode(':', $part);
                    break;
                }

                if (\preg_match('#' . $part[1] . '#', (string) $routeArguments[$part[0]]) !== 1) {
                    throw new \InvalidArgumentException(
                        \sprintf(
                            'Wert "%s" für Argument "%s" in der Route "%s" entspricht nicht den Pattern "%s".',
                            $routeArguments[$part[0]],
                            $part[0],
                            $route,
                            $part[1]
                        )
                    );
                }

                $routeComponents[] = $routeArguments[$part[0]];
            }

            if (\count($routeComponents) > 0) {
                break;
            }
        }

        if (\count($routeComponents) < 1) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Ungültige Argumente für die Route "%s" angegeben: "%s".',
                    $route,
                    $routePlaceholder
                )
            );
        }

        $hashes = [];

        $uri                             = \implode('', $routeComponents);
        $hashes[self::RETAIN_ROUTE_HASH] = static fn () => '/rh~' . \hash('crc32', $uri);

        $uriQuery                  = $uri . $query;
        $hashes[self::RETAIN_HASH] = static fn () => '/h~' . \hash('crc32', $uriQuery);

        $hashes[self::RETAIN_NOT] = static fn () => '';

        $hash = $hashes[$retainHash]();

        $uri = $hash . $uriQuery;

        self::retainHash($hash, $uri, $route);

        return $uri;
    }

    private static function retainHash(string $hash, string $uri, string $route): void
    {
        $_SESSION['hash_uri'][$uri] = $hash;

        if (! isset($_SESSION['hash_route'][$route])) {
            $_SESSION['hash_route'][$route] = [];
        }

        $_SESSION['hash_route'][$route][$hash] = true;
    }
}
