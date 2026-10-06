<?php

declare(strict_types=1);

namespace App\Framework;

final class Request
{
    public static function getQueryString(string $name, string $default = ''): string
    {
        $value = $_GET[$name] ?? $default;
        $value = filter_var($value, FILTER_UNSAFE_RAW, FILTER_REQUIRE_SCALAR);

        return $value ?? $default;
    }

    public static function getQueryInt(string $name, int $default = 1): int
    {
        $value = $_GET[$name] ?? $default;
        $value = filter_var($value, FILTER_VALIDATE_INT);

        return $value ?? $default;
    }
}
