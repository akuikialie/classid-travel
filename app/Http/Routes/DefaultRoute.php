<?php

namespace App\Http\Routes;

use App\Enums\UserStatus;
use Dentro\Yalr\BaseRoute;

class DefaultRoute extends BaseRoute
{

    protected string $prefix = '';

    protected string $name = '';

    /**
     * Register routes handled by this class.
     *
     * @return void
     */
    public function register(): void
    {
        // $this->router->get('{path?}', function () {
        //     if (request()->expectsJson()) {
        //         return response()->json('Welcome to Multi School');
        //     }

        //     return 'Welcome to Multi School';
        // })->where('path', '.*');

        $this->router->get('/', function(){
            return view('pages.mobile.splashscreen-index');
        });

        $this->router->middleware(['auth', 'verified'])->group(function ($route) {

            $route->get($this->prefix('home'), function(){
                // $array1 = array('1' => 'Ayam Goreng', '2' => 'Nasi Goreng', '3' => 'Pisang Goreng');

                // $array2 = array('Nasi Goreng', 'Ayam Goreng', 'Capcai');

                // $TampungArray = array_diff($array1, $array2);
                // return $TampungArray;
                return view('pages.mobile.home.dashboard-index');
            });

        });
    }
}
