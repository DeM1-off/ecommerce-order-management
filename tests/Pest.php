<?php

$moduleFeaturePaths = array_map(
    fn(string $path) => '../' . $path,
    glob('Modules/*/tests/Feature', GLOB_ONLYDIR) ?: [],
);

uses(Tests\TestCase::class)->in('Feature', ...$moduleFeaturePaths);

uses(PHPUnit\Framework\TestCase::class)->in('Unit');
