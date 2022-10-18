<?php

namespace App\Http\Controllers\Web\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\Schedule\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::query()
            ->withCount(['jamaah'])
            ->get();
        return view('pages.web.setup.schedule.schedule-index', [
            'schedules' => $schedules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'view' => view('pages.web.setup.schedule.modal.wizard-create-modal', [])->render(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            Schedule::query()->create([
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            return redirect()->back()->with('success', 'success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {

            $schedule = Schedule::query()->whereId($id)->first();

            return response()->json([
                'view' => view('pages.web.setup.schedule.modal.wizard-edit-modal', [
                    'schedule' => $schedule,
                ])->render(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'departure_date' => ['required', 'string'],
        ]);

        try {
            Schedule::query()->whereId($id)->update([
                'departure_date' => Carbon::parse($validator['departure_date']),
            ]);

            return redirect()->back()->with('success', 'success');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::query()
                ->withCount(['jamaah'])
                ->whereId($id)->first();

            if ($schedule->jamaah_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus jadwal, karena jadwal ini sedang digunakan!', 500);
            }
            $schedule->delete();
            return redirect()->back()->with('success', 'work');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
