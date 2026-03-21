<?php

declare(strict_types=1);

namespace Modules\Catalog\Exceptions;

use DomainException;

class ProductNotFoundException extends DomainException
{
    public static function forId(int $id): self
    {
        return new self("Product #{$id} not found.");
    }
}
