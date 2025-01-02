<?php

declare(strict_types=1);

namespace Novarift\Address\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin Model
 */
trait HasAddresses
{
    public function getAddress(string $type): MorphOne
    {
        return $this->morphOne(config('address.models.address'), 'addressable')
            ->withDefault(['types' => [$type]])
            ->whereIn('types', $type);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(config('address.models.address'), 'addressable');
    }

    public function address(): MorphOne
    {
        return $this->getAddress('primary');
    }
}
