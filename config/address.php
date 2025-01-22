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
        'addresses' => 'addresses',
        'countries' => 'countries',
        'states' => 'states',
        'districts' => 'districts',
        'subdistricts' => 'subdistricts',
        'post_offices' => 'post_offices',
    ],
];
