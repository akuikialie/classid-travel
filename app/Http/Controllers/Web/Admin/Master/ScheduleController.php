<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Schedule\Schedule;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Exception;
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

class ScheduleController extends Controller
{


    protected string $forPage = 'schedule';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @return JsonResponse|void
     * @throws Throwable
     * @throws \Yajra\DataTables\Exceptions\Exception
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $schedules = Schedule::query()
                    ->withCount(['jamaah'])
                    ->tenantId($user->tenant_id)
                    ->latest();

                $datatable = datatables()->eloquent($schedules)
                    ->addIndexColumn()
                    ->addColumn('departure_date', function ($model) {
                        return Carbon::parse($model->departure_date)->format('d M, Y');
                    })->addColumn('status', function ($model) {
                        if ($model->is_active) {
                            return '<span class="badge badge-success text-uppercase">active</span>';
                        } else {
                            return '<span class="badge badge-danger text-uppercase">inactive</span>';
                        }
                    })
                    ->addColumn('actions', function ($model) {
                        $this->setData('schedule', $model);
                        return $this->view('pages.web.master.schedule.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status' ]);

                return $datatable->make(true);
            } catch (Throwable $e) {
                logError($e, title: 'Schedule');
                if (isDevelopmentMode()) {
                    throw $e;
                } else {
                    throw new Exception('Terjadi kesalahan!.');
                }
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $this->setPageTitle('Jadwal');
        $this->setBreadCrumb('Jadwal');
        return $this->view('pages.web.master.schedule.schedule-index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function create(): JsonResponse|Redirector|Application|RedirectResponse
    {
        if (request()->ajax()) {
            return response()->json([
                'view' => view('pages.web.master.schedule.modal.wizard-create-modal', [])->render(),
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
    public function store(Request $request): RedirectResponse
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            $user = auth()->user();
            Schedule::query()->create([
                'tenant_id' => $user->tenant_id,
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            notify('Berhasil', 'Jadwal baru berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Schedule');
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
     * @param Schedule $schedule
     * @return Application|Redirector|RedirectResponse
     */
    public function show(Schedule $schedule): Redirector|RedirectResponse|Application
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Schedule $schedule
     * @return Application|JsonResponse|Redirector|RedirectResponse
     * @throws Throwable
     */
    public function edit(Schedule $schedule): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (request()->ajax()) {

            $this->setData('schedule', $schedule);
            return response()->json([
                'view' => $this->view('pages.web.master.schedule.modal.wizard-edit-modal')->render(),
            ]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Schedule $schedule
     * @return RedirectResponse|Response
     * @throws Throwable
     */
    public function update(Request $request, Schedule $schedule): Response|RedirectResponse
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            $schedule->update([
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            notify('Berhasil', 'Data jadwal berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back()->with('success', 'success');
        } catch (Throwable $e) {
            logError($e, title: 'Schedule');
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
     * @param Schedule $schedule
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        try {
            if ($schedule->jamaah_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus jadwal, karena jadwal ini sedang digunakan!', 500);
            }
            $schedule->delete();
            notify('Berhasil', 'Data jadwal berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $e) {
            logError($e, title: 'Schedule');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @param Schedule $schedule
     * @return RedirectResponse
     * @throws Throwable
     */
    public function changeStatus(Request $request, Schedule $schedule)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        /* begin:: start tenant service */
        try {
            $user = auth()->user();
            $destinationService = new ScheduleService($user->tenant_id);
            if ($request->has('status')) {
                $destinationService
                    ->setSchedule($schedule)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
            }else{
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }
            return redirect()->back();
        }catch (Throwable $e){
            logError($e, title: 'Schedule');
            if (isDevelopmentMode()) {
                throw $e;
            } else {
                notify('Oops!', 'Terjadi kesalahan!', 'error');
            }
            return redirect()->back();
        }
        /* end:: start tenant service */
    }
}
