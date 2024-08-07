<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ModelTrait
{
    /**
     * Scope by key.
     *
     * @param array<mixed> $keys
     */
    public static function scopeKey(Builder $builder, array $keys): void
    {
        $builder->whereKey($keys);
    }
}
