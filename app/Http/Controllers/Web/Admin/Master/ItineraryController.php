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
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class ItineraryController extends Controller
{

    protected string $forPage = 'itinerary';

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws Exception
     * @throws Throwable
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $itineraries = ItineraryActivity::query()
                    ->tenantId($user->tenant_id)
                    ->get();

                $datatable = datatables()->of($itineraries)
                    ->addIndexColumn()
                    ->addColumn('name', function ($model) {
                        return $model->activity;
                    })->addColumn('detail', function ($model) {
                        return ($model->detail ?? '-');
                    })
                    ->addColumn('actions', function ($model) {
                        $this->setData('itinerary', $model);
                        return $this->view('pages.web.master.itinerary.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status' ]);

                return $datatable->make(true);
            } catch (Throwable $e) {
                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                    throw new \Exception('Terjadi kesalahan!.');
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $this->setPageTitle('Rencana');
        $this->setBreadCrumb('Rencana');
        return $this->view('pages.web.master.itinerary.itinerary-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'view' => view('pages.web.master.itinerary.modal.modal-create-itinerary_activity', [
                ])->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
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
            return redirect()->back();
        }catch (\Throwable $e){
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ItineraryActivity $activity
     * @return void
     */
    public function show(ItineraryActivity $activity)
    {
        abort(404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ItineraryActivity $activity
     * @return JsonResponse|RedirectResponse
     */
    public function edit(ItineraryActivity $activity)
    {
        if (request()->ajax()) {
            return response()->json([
                'view' => view('pages.web.master.itinerary.modal.modal-edit-itinerary_activity', [
                    'activity' => $activity,
                ])->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ItineraryActivity $activity
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, ItineraryActivity $activity)
    {
        $input = $request->validate([
            'activity' => ['required', 'string'],
            'detail' => ['required', 'string'],
        ]);

        try {
            /* begin:: update itinerary activity */
            $activity->activity = $input['activity'];
            $activity->detail = $input['detail'];
            $activity->save();
            /* end:: update itinerary activity */

            notify('Berhasil', 'Berhasil memperbarui data kegiatan!.', 'success');
            return redirect()->back();
        }catch (\Throwable $e){
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ItineraryActivity $activity
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(ItineraryActivity $activity)
    {
        try {
            if ($activity->has_itineraries_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus kegiatan, karena kegiatan ini sedang digunakan!', 500);
            }
            $activity->delete();

            notify('Berhasil', 'Data Aktifitas berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (\Throwable $e) {
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }
}
