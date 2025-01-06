<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'country_id',
        'code',
        'name',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.states') ?: parent::getTable();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(config('address.models.state'));
    }

    public function districts(): HasMany
    {
        return $this->hasMany(config('address.models.district'));
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(config('address.models.address'));
    }
}
