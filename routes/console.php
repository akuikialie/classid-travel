<?php

use App\Http\Notifications\WaTestNotif;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('msnotif:test', function () {
    // $msg = "Rela di Test jilid 2";

    // $woowa = msnotif('woowa_eco');
    // return $woowa->send('msnotif.test', $msg, config('msnotif.woowa.eco.sender'), '089626336461');

    $user = User::query()->orderBy('created_at')->first();
    dispatch_sync(new WaTestNotif($user));
});
