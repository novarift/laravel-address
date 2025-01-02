<?php

return [
    'models' => [
        'address' => \Novarift\Address\Models\Address::class,
        'country' => \Novarift\Address\Models\Country::class,
        'state' => \Novarift\Address\Models\State::class,
        'district' => \Novarift\Address\Models\District::class,
        'mukim' => \Novarift\Address\Models\Mukim::class,
        'post-office' => \Novarift\Address\Models\PostOffice::class,
    ],

    'tables' => [
        'address' => 'addresses',
        'country' => 'countries',
        'states' => 'states',
        'district' => 'districts',
        'mukim' => 'mukims',
        'post-office' => 'post_offices',
    ],
];
