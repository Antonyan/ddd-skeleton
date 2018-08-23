<?php

return [
    'RestaurantDbMapper' => [
        'table' => 'restaurants',
        'columns' => [
            'id'   => 'restaurants.id',
            'name' => 'restaurants.name',
        ],
        'create' => 'id',
        'update' => ['id'],
    ],

    'RestaurantAttributeValueDbMapper' => [
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
