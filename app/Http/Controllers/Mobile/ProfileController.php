<?php

namespace App\Http\Controllers\Mobile;

use App\Actions\Users\ChangeAvatar;
use App\Http\Controllers\AuthenticationSessionController;
use App\Http\Controllers\Controller;
use App\Models\Jamaah\Jamaah;
use App\Models\Referal\UserInvitation;
use App\Models\User;
use App\Rules\OldPasswordRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        $user = User::query()
            ->with(['peopleInviteds.user'])
            ->withCount('peopleInviteds')
            ->where('id', auth()->user()->id)
            ->first();

        // dd($user);

        $jamaah = Jamaah::query()
            ->with(['planPackages'])
            ->withCount(['planPackages'])
            ->where('id', $user->id)->first();

        $peopleInviteds = $user->peopleInviteds;

        return view('pages.mobile.profile.profile-index', [
            'user' => $user,
            'total_tabungan' => $jamaah->plan_packages_count + 1,
            'planPackages' => $jamaah->planPackages,
            'people_invited' => $peopleInviteds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $user = User::query()->where('id', $id)->first();
        // dd($user);
        return view('pages.mobile.profile.profile-edit', ['user' => $user]);
    }

    public function editInformation(Request $request, int $id)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'username' => ['required', 'string'],
            'email' => ['nullable', 'email'],
        ]);

        try {
            User::query()->where('id', $id)
                ->update($validator);

            notify('Berhasil', 'Data berhasil diperbarui!.', 'success');
            return redirect()->back();
        } catch (Throwable $th) {
            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request, int $id)
    {
        $request->validate([
            'old_password' => ['required', 'string', new OldPasswordRule()],
            'new_password' => ['required', 'string'],
            'confirm_password' => ['required_with:new_password', 'same:new_password'],
        ]);

        try {

            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect(route('login'));
        } catch (Throwable $th) {
            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    public function changeProfile(Request $request, ChangeAvatar $changeAvatar){
        $request->validate([
            'avatar' => ['required', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
            $changeAvatar->handle($request);

            notify('Berhasil!', 'Photo profil berhasil di perbarui', 'success');
            DB::commit();
            return \redirect()->back();
        }catch (\Throwable $th){
            DB::rollBack();
            notify('Oops!', $th->getMessage(), 'error');
            return \redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
