<?php

$defaultResPath = 'notify';

return [
    'view' => [
        /* web */
        'web' => [
            "{$defaultResPath}.web.sweetalert-notify",
        ],
        /* mobile */
        'mobile' => [
            "{$defaultResPath}.mobile.custom-notify",
        ],
    ], // register view
    'duration' => 5000,
    'notify_position' => 'top-right',
];
