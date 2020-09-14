<?php

use Ukrpochta\Pochta;

include __DIR__ . '/vendor/autoload.php';

$ukrpochta = new Pochta('API_KEY');

$result = $ukrpochta->createAddress(
    array(
        'postcode'        => '02099',
        'region'          => 'Полтавська',
        'district'        => 'Полтавський',
        'city'            => 'Полтава',
        'street'          => 'Шевченка',
        'houseNumber'     => '51',
        'apartmentNumber' => '20',
    )
);

// print_r($result);
//{"id":123175,"postcode":"02099","region":"Полтавська","district":"Полтавський","city":"Полтава",
//"street":"Шевченка","houseNumber":"51","apartmentNumber":"20","description":null,"countryside":false,
//"detailedInfo":"Україна, 02099, Полтавська, Полтавський, Полтава, Шевченка, 51, 20","country":"UA"}
