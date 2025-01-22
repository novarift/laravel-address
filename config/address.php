<?php

return [
    'models' => [
        'address' => \Novarift\Address\Models\Address::class,
        'country' => \Novarift\Address\Models\Country::class,
        'state' => \Novarift\Address\Models\State::class,
        'district' => \Novarift\Address\Models\District::class,
        'subdistrict' => \Novarift\Address\Models\Subdistrict::class,
        'post-office' => \Novarift\Address\Models\PostOffice::class,
    ],

    'tables' => [
        'address' => 'addresses',
        'country' => 'countries',
        'states' => 'states',
        'district' => 'districts',
        'subdistrict' => 'subdistricts',
        'post-office' => 'post_offices',
    ],
];
