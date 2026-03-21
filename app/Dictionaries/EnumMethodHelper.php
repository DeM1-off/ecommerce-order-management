<?php

declare(strict_types=1);

namespace App\Dictionaries;

use Throwable;

trait EnumMethodHelper
{
    private static function tryFromName(string $name): ?static
    {
        try {
            return constant("self::{$name}");
        } catch (Throwable) {
            return null;
        }
    }

    public function __call(string $name, array $arguments): bool
    {
        return match (true) {
            str_starts_with($name, 'is') => $this === self::tryFromName(
                strtoupper(preg_replace('/(?<=\w)(?=[A-Z])/', '_', substr($name, 2)))
            ),
            str_starts_with($name, 'has') => in_array($this, $arguments[0] ?? [], true),
            default => false,
        };
    }
}
