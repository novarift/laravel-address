<?php

namespace Novarift\Address\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Novarift\Address\Address
 */
class Address extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Novarift\Address\Address::class;
    }
}
