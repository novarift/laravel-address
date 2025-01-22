<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $state_id
 * @property string $code
 * @property string $name
 * @property State $state
 * @property Collection<Subdistrict> $subdistricts
 */
class District extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'state_id',
        'code',
        'name',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.districts', parent::getTable());
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(config('address.models.state'));
    }

    public function subdistricts(): HasMany
    {
        return $this->hasMany(config('address.models.subdistrict'));
    }
}
