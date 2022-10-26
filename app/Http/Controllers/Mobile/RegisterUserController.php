<?php

namespace App\Http\Controllers\Mobile;

use App\Actions\Users\CreateNewUser;
use App\Actions\Jamaah\AddJamaahHistory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('pages.mobile.auth.register-index');
    }

    public function store(Request $request, CreateNewUser $newUser, AddJamaahHistory $jamaahHistory)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        $validator = array_merge( $validator, ['password' => Hash::make($validator['password'])]);

        // User::query()->create($validator);
        DB::beginTransaction();
        try {
            $createNewUser = $newUser->handle($validator);

            $jamaahHistory->handle($createNewUser['jamaah']);
            DB::commit();
        }catch (\Throwable $throwable){
            DB::rollBack();
            notify('Gagal!!', $throwable->getMessage(), 'error');
            return redirect()->back();
        }

        return redirect(route('login'));

    }
}
