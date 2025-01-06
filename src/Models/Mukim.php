<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mukim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'district_id',
        'code',
        'name',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.mukim') ?: parent::getTable();
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(config('address.models.district'));
    }
}
