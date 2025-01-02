<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'country_id',
        'types',
        'street_1',
        'street_2',
        'street_3',
        'post_code',
        'city',
        'state',
        'properties',
    ];

    protected $attributes = [
        'type' => '[]',
        'properties' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'array',
            'properties' => 'array',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.address') ?: parent::getTable();
    }

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('address.models.country'));
    }

    public function scopeOfType(Builder $query, Arrayable|array|string $types): void
    {
        $query->whereIn('types', collect($types));
    }
}
