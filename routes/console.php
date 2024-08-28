<?php

use App\Models\User;
use App\Services\Whatsapp\NotificationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

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
