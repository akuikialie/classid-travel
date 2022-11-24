<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Preloads
    |--------------------------------------------------------------------------
    | String of class name that instance of \Dentro\Yalr\Contracts\Bindable
    | Preloads will always been called even when laravel routes has been cached.
    | It is the best place to put Rate Limiter and route binding related code.
    */

    'preloads' => [
        App\Http\Routes\BindingRoute::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Router group settings
    |--------------------------------------------------------------------------
    | Groups are used to organize and group your routes. Basically the same
    | group that used in common laravel route.
    |
    | 'group_name' => [
    |     // laravel group route options can contains 'middleware', 'prefix',
    |     // 'as', 'domain', 'namespace', 'where'
    | ]
    */

    'groups' => [
        'web' => [
            'middleware' => 'web',
            'prefix' => '',
        ],
        'admin' => [
            'middleware' => 'web',
            'prefix' => 'admin',
            'as' => 'admin.'
        ],
        'mobile' => [
            'middleware' => 'mobile',
            'prefix' => 'app',
        ],
        'api' => [
            'middleware' => 'api',
            'prefix' => 'api',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    | Below is where our route is loaded, it read `groups` section above.
    | keys in this array are the name of route group and values are string
    | class name either instance of \Dentro\Yalr\Contracts\Bindable or
    | controller that use attribute that inherit \Dentro\Yalr\RouteAttribute
    */

    'mobile' => [
        /* mobile */
        App\Http\Routes\Mobile\AuthRoute::class,
        App\Http\Routes\Mobile\HomeRoute::class,
        App\Http\Routes\Mobile\JamaahRoute::class,
        App\Http\Routes\Mobile\PackageRoute::class,
        App\Http\Routes\Mobile\TabunganRoute::class,
        App\Http\Routes\Mobile\ProfileRoute::class,
        App\Http\Routes\Mobile\ReferralRoute::class,
        App\Http\Routes\Mobile\PerencanaanRoute::class,

    ],
    'web' => [
        /* web */
        App\Http\Routes\DefaultRoute::class,
    ],
    'admin' => [
        /* web */
        App\Http\Routes\Web\Admin\DefaultRoute::class,
        App\Http\Routes\Web\Admin\AuthRoute::class,
        App\Http\Routes\Web\Admin\TenantRoute::class,
        App\Http\Routes\Web\Admin\PackageRoute::class,
        App\Http\Routes\Web\Admin\DestinationRoute::class,
        App\Http\Routes\Web\Admin\FacilityRoute::class,
        App\Http\Routes\Web\Admin\ScheduleRoute::class,
        App\Http\Routes\Web\Admin\ItineraryRoute::class,
        App\Http\Routes\Web\Admin\UserRoute::class,
        App\Http\Routes\Web\Admin\RoleRoute::class,
    ],
    'api' => [
        /** @inject api **/
    ],
];
