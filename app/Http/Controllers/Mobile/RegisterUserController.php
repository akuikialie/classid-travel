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

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'unique:users,phone'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ]);

        // User::query()->create($validator);
        DB::beginTransaction();
        try {
            $createNewUser = new CreateNewUser();
            $newUser = $createNewUser->handle($validator);

            $jamaahHistory = new AddJamaahHistory();
            $jamaahHistory->handle($newUser['jamaah']);
            DB::commit();

            notify('Berhasil!!', 'Anda berhasil membuat akun, silahkan login menggunakan akun anda', 'success');
            return redirect(route('login'));
        }catch (\Throwable $throwable){
            DB::rollBack();
            notify('Gagal!!', $throwable->getMessage(), 'error');
            return redirect()->back();
        }


    }
}
