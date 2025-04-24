<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Geo\City;
use App\Models\Jamaah\Jamaah;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Rules\OldPasswordRule;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            ->with(['peopleInvites.user'])
            ->withCount('peopleInvites')
            ->where('id', auth()->user()->id)
            ->first();

        $jamaah = Jamaah::query()
            ->with(['planPackages'])
            ->withCount(['planPackages'])
            ->where('user_id', $user->id)->first();

        $peopleInvites = $user->peopleInvites;

        return view('pages.mobile.profile.profile-index', [
            'user' => $user,
            'total_tabungan' => $jamaah->plan_packages_count + 1,
            'planPackages' => $jamaah->planPackages,
            'people_invited' => $peopleInvites,
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
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function edit(int $id)
    {
        $user = User::query()
            ->with(['jamaah'])->where('id', $id)->first();
//        dd($user);

        $schedules = Schedule::query()
            ->tenantId($user->tenant_id)
            ->whereDate('departure_date', '>', Carbon::now()->addDay(7))
            ->get();

        $cities = City::query()->get();

        setDefaultRequest([
            'schedule_id' => $user->jamaah->schedule_id,
            'departure_city_id' => $user->jamaah->departure_city_id,
        ]);

        $this->setData('user', $user);
        $this->setData('cities', $cities);
        $this->setData('schedules', $schedules);
        return $this->view('pages.mobile.profile.profile-edit');
    }

    /**
     * @throws Throwable
     */
    public function editInformation(Request $request, int $id)
    {
        $validator = $request->validate([
            'name' => ['required', 'string'],
            'username' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
        ]);

        try {
            $user = User::query()
                ->with(['tabungan'])
                ->find($id);

            if (!$user instanceof User) {
                throw new ModelNotFoundException();
            }

            $user->fill($validator);
            $user->tabungan->name = $validator['name'] . ' - '. \Illuminate\Support\Str::after($user->tabungan->name, ' - ');
            $user->push();

            notify('Berhasil', 'Data berhasil diperbarui!.', 'success');
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Mobile profile');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
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

            Auth::guard("web")->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect(route('login'));
        } catch (Throwable $e) {
            logError($e, title: 'Mobile profile');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @throws Throwable
     */
    public function changeProfile(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'max:2048']
        ]);

        DB::beginTransaction();
        try {
//            $changeAvatar->handle($request);

            notify('Berhasil!', 'Photo profil berhasil di perbarui', 'success');
            DB::commit();
            return \redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'Mobile profile');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws Throwable
     */
    public function reschedule(Request $request, User $user)
    {
        $input = $request->validate([
            'departure_city_id' => ['required', 'integer'],
            'schedule_id' => ['required', 'string'],
        ]);
        try {
            $user->jamaah->schedule_id = $input['schedule_id'];
            $user->jamaah->departure_city_id = $input['departure_city_id'];
            $user->push();
            notify('Berhasil', 'Data berhasil diperbarui!.', 'success');
            return \redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Mobile package');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
