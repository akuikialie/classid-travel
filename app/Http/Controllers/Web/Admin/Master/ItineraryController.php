<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Itinerary\ItineraryActivity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = auth()->user();
        $itinerary = ItineraryActivity::query()
            ->tenantId($user->tenant_id)
            ->get();
        return view('pages.web.master.itinerary.itinerary-index', [
            'itineraries' => $itinerary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'view' => view('pages.web.master.itinerary.modal.modal-create-itinerary_activity', [
                ])->render(),
            ]);
        } else {
            notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.facility.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->validate([
           'activity' => ['required', 'string'],
           'detail' => ['required', 'string'],
        ]);

        try {
            $user = auth()->user();
            /* begin:: create new itinerary activity */
            $input = array_merge(
                ['tenant_id' => $user->tenant_id], $input
            );
            ItineraryActivity::query()->create($input);
            /* end:: create new itinerary activity */

            notify('Berhasil', 'Berhasil membuat kegiatan baru!.', 'success');
        }catch (\Throwable $e){
            notify('Gagal', $e->getMessage(), 'error');
        } finally {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $hash
     * @return Application|Redirector|RedirectResponse
     */
    public function show($hash)
    {
        notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('master.facility.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $hash
     * @return JsonResponse|RedirectResponse
     */
    public function edit($hash)
    {
        if (request()->ajax()) {
            $activity = ItineraryActivity::query()
                ->byHashOrFail($hash);
            return response()->json([
                'view' => view('pages.web.master.itinerary.modal.modal-edit-itinerary_activity', [
                    'activity' => $activity,
                ])->render(),
            ]);
        } else {
            notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $hash
     * @return RedirectResponse
     */
    public function update(Request $request, $hash)
    {
        $input = $request->validate([
            'activity' => ['required', 'string'],
            'detail' => ['required', 'string'],
        ]);

        try {
            /* begin:: update itinerary activity */
            $activity = ItineraryActivity::query()
                ->byHashOrFail($hash);
            $activity->activity = $input['activity'];
            $activity->detail = $input['detail'];
            $activity->save();
            /* end:: update itinerary activity */

            notify('Berhasil', 'Berhasil memperbarui data kegiatan!.', 'success');
        }catch (\Throwable $e){
            notify('Gagal', $e->getMessage(), 'error');
        } finally {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $hash
     * @return RedirectResponse
     */
    public function destroy($hash)
    {
        try {
           $activity = ItineraryActivity::query()
               ->withCount(['hasItineraries'])
               ->byHashOrFail($hash);

            if ($activity->has_itineraries_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus kegiatan, karena kegiatan ini sedang digunakan!', 500);
            }
            $activity->delete();

            notify('Berhasil', 'Data Aktifitas berhasil dihapus!', 'success')->autoClose();
        } catch (\Throwable $th) {
            notify('Oops!', $th->getMessage(), 'error');
        } finally {
            return redirect()->back();
        }
    }
}
