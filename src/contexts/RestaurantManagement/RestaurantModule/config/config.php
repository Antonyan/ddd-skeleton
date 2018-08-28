<?php

return [
    'RestaurantDbTranslator' => [
        'table' => 'restaurants',
        'columns' => [
            'id'   => 'restaurants.id',
            'name' => 'restaurants.name',
        ],
        'create' => 'id',
        'update' => ['id'],
    ],

    'RestaurantAttributeValueDbTranslator' => [
        'table' => 'restaurantAttributeValues',
        'columns' => [
            'id'   => 'restaurantAttributeValues.id',
            'value' => 'restaurantAttributeValues.value',
            'restaurantId' => 'restaurantAttributeValues.restaurantId',
        ],
        'create' => 'id',
        'update' => ['id'],
    ]
];
