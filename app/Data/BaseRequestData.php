<?php

declare(strict_types=1);

namespace App\Data;

use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

abstract class BaseRequestData extends Data
{
    /**
     * Properties to exclude from forceFill (e.g. nested relations, computed fields).
     *
     * @var string[]
     */
    protected array $excludeFromFill = [];

    /**
     * Returns filled (non-Optional) properties as snake_case array for forceFill.
     *
     * @return array<string, mixed>
     */
    public function getFilledPropertiesAsArray(): array
    {
        $result = [];

        foreach ($this->toArray() as $key => $value) {
            if (! in_array($key, $this->excludeFromFill, true)) {
                $result[Str::snake($key)] = $value;
            }
        }

        return $result;
    }
}
