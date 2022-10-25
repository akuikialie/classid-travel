<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Destination\Destination;
use App\Services\DestinationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;

class DestinationController extends Controller
{

    protected DestinationService $destinationService;

    public function __construct(DestinationService $destinationService)
    {
        $this->destinationService = $destinationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        // toast('Signed in successfully', 'success')->autoClose();
        $destinations = Destination::query()
            ->with(['myAddress'])
            ->withCount(['media', 'packages'])
            ->get();

        return view('pages.web.master.destination.destination-index', [
            'destinations' => $destinations,
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
                'view' => view('pages.web.master.destination.modal.wizard-create-modal', [])->render(),
            ]);
        } else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.destination.index'));
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
            'name' => ['required', 'string',],
            'address' => ['required', 'string'],
            'roaming_in_destination' => ['required', 'integer'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $newDestination = $this->destinationService->createNewDestination($validator);

            if ($request->hasfile('photo_collection')) {
                $this->destinationService->addImageToDestination($newDestination, $request);
            }

            DB::commit();
            notify('Berhasil', 'Data destinasi berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
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
        return redirect(route('master.destination.index'));
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

            $destination = Destination::query()
                ->with(['myAddress'])
                ->whereId($id)->first();

            return response()->json([
                'view' => view('pages.web.master.destination.modal.wizard-edit-modal', [
                    'destination' => $destination,
                ])->render(),
            ]);
        } else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('master.destination.index'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = $request->validate([
            'name' => ['required', 'string',],
            'address' => ['required', 'string'],
            'roaming_in_destination' => ['required', 'integer'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $destination = Destination::query()->whereId($id)->first();

            if ($request->hasfile('photo_collection')) {
                $this->destinationService->addImageToDestination($destination, $request);
            }

            if (isset($validator['address']) && !empty($validator['address'])) {
                $this->destinationService->updateDestinationAddress($destination, $validator);
            }

            $destination->name = $validator['name'];
            $destination->roaming_in_destination = $validator['roaming_in_destination'];

            DB::commit();
            notify('Berhasil', 'Data destinasi berhasil diperbarui!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(int $id): Redirector|RedirectResponse|Application
    {
        try {
            $destination = Destination::query()
                ->withCount(['packages'])
                ->whereId($id)->first();

            if ($destination->packages_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus destinasi, karena destinasi ini sedang digunakan!', 500);
            }
            $destination->delete();
            notify('Berhasil', 'Data destinasi berhasil dihapus!', 'success')->autoClose();
            return redirect();
        } catch (Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
