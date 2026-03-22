<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 */
#[Fillable(['name'])]
class Category extends Model
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
