<?php

declare(strict_types=1);

namespace Novarift\Address\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostOffice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'state_id',
        'name',
        'postcodes',
    ];

    protected $attributes = [
        'postcodes' => '[]',
    ];

    protected function casts(): array
    {
        return [
            'postcodes' => 'array',
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('address.tables.post-office') ?: parent::getTable();
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(config('address.models.state'));
    }
}
