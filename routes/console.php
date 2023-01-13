<?php

use App\Http\Notifications\WaTestNotif;
use App\Models\User;
use App\Services\Whatsapp\NotificationService;
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
    $user = User::query()->orderBy('created_at')->first();

    (new NotificationService())
        ->setReceiver($user)
        ->setSubject([
            'invited_by' => 'winata',
            'package' => 'wowww',
        ])
        ->setMessage(
            'Selamat *:receiver.name*, Anda telah terdaftar di :subject.package bersama :subject.invited_by'
        )
        ->sendMessage();

});
