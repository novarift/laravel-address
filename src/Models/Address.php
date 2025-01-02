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
        'state_id',
        'types',
        'street_1',
        'street_2',
        'street_3',
        'postcode',
        'latitude',
        'longitude',
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

    public function state(): BelongsTo
    {
        return $this->belongsTo(config('address.models.state'));
    }

    public function scopeOfType(Builder $query, Arrayable|array|string $types): void
    {
        $query->whereIn('types', collect($types));
    }

    public function formatted(bool $country = true): string
    {
        return collect([
            $this->street_1,
            $this->street_2,
            $this->street_3,
            $this->postcode,
            $this->state->name,
            $country ? $this->country->name : null,
        ])->filter()
            ->join(', ');
    }
}
