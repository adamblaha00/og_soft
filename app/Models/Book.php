<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory, ModelTrait;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Search scope.
     */
    public static function scopeSearch(Builder $builder, string $search): void
    {
        if (\config('app.env') === 'testing') {
            $builder->getQuery()->where($builder->qualifyColumn('name'), $search);
        } else {
            $builder->getQuery()->whereFullText($builder->qualifyColumn('name'), $search);
        }
    }
}
