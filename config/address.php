<?php

return [
    'models' => [
        'address' => \Novarift\Address\Models\Address::class,
        'country' => \Novarift\Address\Models\Country::class,
    ],

    'tables' => [
        'address' => 'addresses',
        'country' => 'countries',
    ],
];
