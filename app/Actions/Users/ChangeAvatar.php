<?php

namespace App\Actions\Users;


use App\Models\User;
use Illuminate\Http\Request;

class ChangeAvatar
{
    public function handle(Request $request): void
    {
        try {
            $user = User::query()
                ->with(['media'])
                ->whereId(auth()->user()->id)->first();
            if ($request->hasfile('avatar')) {
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
