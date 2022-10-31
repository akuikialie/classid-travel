<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Schedule\Schedule;
use App\Services\ScheduleService;
use Carbon\Carbon;
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

    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $user = auth()->user();
        $schedules = $this->scheduleService
            ->byTenant($user->tenant?->id)
            ->get();
        return view('pages.web.master.schedule.schedule-index', [
            'schedules' => $schedules,
        ]);
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
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.schedule.index'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            Schedule::query()->create([
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            notify('Berhasil', 'Jadwal baru berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function show(int $id): Redirector|RedirectResponse|Application
    {
        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('master.schedule.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function edit(int $id): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (request()->ajax()) {

            $schedule = Schedule::query()->whereId($id)->first();

            return response()->json([
                'view' => view('pages.web.master.schedule.modal.wizard-edit-modal', [
                    'schedule' => $schedule,
                ])->render(),
            ]);
        }else{
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.schedule.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse|Response
     */
    public function update(Request $request, int $id): Response|RedirectResponse
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            Schedule::query()->whereId($id)->update([
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            notify('Berhasil', 'Data jadwal berhasil diperbarui!', 'success')->autoClose();
            return redirect()->back()->with('success', 'success');
        } catch (Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $schedule = Schedule::query()
                ->withCount(['jamaah'])
                ->whereId($id)->first();

            if ($schedule->jamaah_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus jadwal, karena jadwal ini sedang digunakan!', 500);
            }
            $schedule->delete();
            notify('Berhasil', 'Data jadwal berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
