<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string $code
 * @property string $name
 * @property string $alpha_2
 * @property Collection<State> $states
 * @property Collection<PostOffice> $postOffices
 * @property Collection<District> $districts
 * @property Collection<Address> $addresses
 */
class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'alpha_2',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.country', parent::getTable());
    }

    public function states(): HasMany
    {
        return $this->hasMany(config('address.models.state'));
    }

    public function postOffices(): HasMany
    {
        return $this->hasMany(config('address.models.post-office'));
    }

    public function districts(): HasManyThrough
    {
        return $this->hasManyThrough(config('address.models.district'), config('address.models.state'));
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(config('address.models.address'));
    }
}
