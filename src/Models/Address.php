<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $addressable_type
 * @property integer $addressable_id
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $post_office_id
 * @property array $types
 * @property string $street_1
 * @property ?string $street_2
 * @property ?string $street_3
 * @property ?string $postcode
 * @property float $latitude
 * @property float $longitude
 * @property array $properties
 * @property Model $addressable
 * @property Country $country
 * @property State $state
 * @property PostOffice $postOffice
 */
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

    public function postOffice(): BelongsTo
    {
        return $this->belongsTo(config('address.models.post-office'));
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
