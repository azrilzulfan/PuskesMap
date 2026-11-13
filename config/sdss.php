<?php

return [
    'maut' => [
        'weights' => [
            'darurat' => [
                'distance' => 0.40,
                'igd' => 0.30,
                'hours' => 0.15,
                'nakes' => 0.15,
                'crowd' => 0.00,
            ],
            'reguler' => [
                'hours' => 0.30,
                'distance' => 0.20,
                'nakes' => 0.20,
                'crowd' => 0.20,
                'igd' => 0.10,
            ],
            'pemda' => [
                'nakes' => 0.30,
                'distance' => 0.20,
                'crowd' => 0.20,
                'igd' => 0.15,
                'hours' => 0.15,
            ],
        ],
        'top_n' => 3,
    ],

    'psw' => [
        'weights' => [
            'pop' => 0.40,
            'edu' => 0.20,
            'pos' => 0.20,
            'kur' => 0.20,
        ],
        'thresholds' => [
            'high' => 0.70,
            'ideal' => 0.40,
        ],
    ],

    'capacity' => [
        'default_monthly' => 5603,
        'low_threshold' => 60.0,
        'medium_threshold' => 85.0,
    ],

    'nakes' => [
        'professions' => [
            'dokter_umum',
            'dokter_gigi',
            'bidan',
            'perawat',
            'farmasi',
            'gizi',
            'kesling',
            'kesmas',
            'atlm',
        ],
        'complete_threshold' => 100.0,
    ],

    'equity' => [
        'population_per_facility' => 30000,
    ],

    'coverage' => [
        'allowed_buffers_km' => [1.5, 3.0],
        'default_buffer_km' => 1.5,
    ],

    'spatial' => [
        'srid' => 4326,
        'timezone' => 'Asia/Jakarta',
        'bogor_center' => [
            'lat' => -6.597147,
            'lng' => 106.806039,
        ],
    ],

    'pagination' => [
        'default_per_page' => 15,
        'min_per_page' => 10,
        'max_per_page' => 25,
    ],
];
