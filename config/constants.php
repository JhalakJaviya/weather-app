<?php

return [
    'openweathermap' => [
        'units' => 'metric',
        'apis' => [
            'weather_data' => [
                'current' => 'https://api.openweathermap.org/data/2.5/weather',
                'forecast5' => 'api.openweathermap.org/data/2.5/forecast',
            ],
        ],
    ],
];
