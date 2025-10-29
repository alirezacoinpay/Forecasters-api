<?php

return [
    'total_budget' => 30,

    'factor_weights' => [
        'time'       => 30.00,   // ~1/3
        'difficulty'       => 30.00,   // ~1/3
        'popularity'       => 40.00,
    ],

    'factor_min_score' => 1,

    'popularity_k' => 100,
    'penalty_score' => -2,
];
