<?php

namespace App\Http\Controllers\Web\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\Destination\Destination;
use App\Services\DestinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // toast('Signed in successfully', 'success')->autoClose();
        $destinations = Destination::query()
            ->with(['myAddress'])
            ->withCount(['media', 'packages'])
            ->get();

        return view('pages.web.setup.destination.destination-index', [
            'destinations' => $destinations,
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
                'view' => view('pages.web.setup.destination.modal.wizard-create-modal', [])->render(),
            ]);
        } else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('setup.destination.index'));
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
        } catch (\Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
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
        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('setup.destination.index'));
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

            $destination = Destination::query()
                ->with(['myAddress'])
                ->whereId($id)->first();

            return response()->json([
                'view' => view('pages.web.setup.destination.modal.wizard-edit-modal', [
                    'destination' => $destination,
                ])->render(),
            ]);
        } else {
            notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
            return redirect(route('setup.destination.index'));
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
        } catch (\Throwable $th) {
            DB::rollBack();
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
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
            $destination = Destination::query()
                ->withCount(['packages'])
                ->whereId($id)->first();

            if ($destination->packages_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus destinasi, karena destinasi ini sedang digunakan!', 500);
            }
            $destination->delete();
            notify('Berhasil', 'Data destinasi berhasil dihapus!', 'success')->autoClose();
            return redirect();
        } catch (\Throwable $th) {
            notify('Opps!', $th->getMessage(), 'error');
            return redirect()->back();
            throw $th;
        }
    }
}
