<?php

namespace App\Http\Controllers\Web\Admin\Master;

use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Destination\Destination;
use App\Services\DestinationService;
use Exception;
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

    protected string $forPage = 'destination';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setData('current_page', $this->forPage);
    }

    /**
     * @throws Throwable
     */
    public function datatable()
    {
        if (\request()->ajax()) {
            try {
                $user = auth()->user();
                $destinations = Destination::query()
                    ->with(['myAddress'])
                    ->withCount(['media', 'packages'])
                    ->tenantId($user->tenant_id)
                    ->get();

                $datatable = datatables()->of($destinations)
                    ->addIndexColumn()
                    ->addColumn('name', function ($destination) {
                        return $destination->name;
                    })->addColumn('location', function ($destination) {
                        return ($destination->myAddress->address ?? '-');
                    })->addColumn('status', function ($destination) {
                        if ($destination->is_active) {
                            return '<span class="badge badge-success text-uppercase">active</span>';
                        } else {
                            return '<span class="badge badge-danger text-uppercase">inactive</span>';
                        }
                    })
                    ->addColumn('actions', function ($destination) {
                        $this->setData('destination', $destination);
                        return $this->view('pages.web.master.destination.action.action-datatable');
                    })
                    ->rawColumns(['actions', 'status' ]);

                return $datatable->make(true);
            } catch (Throwable $e) {
                throw $e;
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
        $user = auth()->user();
//        $destinations = Destination::query()
//            ->with(['myAddress'])
//            ->withCount(['media', 'packages'])
//            ->tenantId($user->tenant_id)
//            ->get();

        return $this->view('pages.web.master.destination.destination-index');
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
        }

        abort(404);
        notify('Oops!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('admin.destination.index'));
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
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();

            $destinationService = new DestinationService($user->tenant_id);
            $destinationService
                ->createDestination($validator)
                ->addAddress($validator)
                ->addGallery($request)
                ->get();

            DB::commit();
            notify('Berhasil', 'Data destinasi berhasil dibuat!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            DB::rollBack();
            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Destination $destination
     * @return Application|JsonResponse|Redirector|RedirectResponse
     */
    public function edit(Destination $destination): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (request()->ajax()) {

//            $destination = Destination::query()
//                ->with(['myAddress'])
//                ->whereId($id)->first();

            return response()->json([
                'view' => view('pages.web.master.destination.modal.wizard-edit-modal', [
                    'destination' => $destination,
                ])->render(),
            ]);
        }
        abort(404);

        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('master.destination.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param Destination $destination
     * @return Application|Redirector|RedirectResponse
     */
    public function show(Destination $destination): Redirector|RedirectResponse|Application
    {
        abort(404);

        notify('Opps!', 'Terjadi kesalahan saat memuat halaman!', 'error')->autoClose();
        return redirect(route('master.destination.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Destination $destination
     * @return RedirectResponse
     */
    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validator = $request->validate([
            'name' => ['required', 'string',],
            'address' => ['nullable', 'string'],
            'photo_collection' => ['nullable', 'array'],
        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();

//            $destination = Destination::query()->whereId($id)->first();

            $destinationService = new DestinationService($user->tenant_id);
            $destinationService
                ->setDestination($destination)
                ->addAddress($validator)
                ->addGallery($request)
                ->update($validator);

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
     * @param Destination $destination
     * @return RedirectResponse
     */
    public function destroy(Destination $destination): RedirectResponse
    {
        try {
//            $destination = Destination::query()
//                ->withCount(['packages'])
//                ->where('id', $id)->first();

            if (!isset($destination)) {
                throw new InvalidArgumentException('Data destinasi tidak ditemukan', 500);
            }

            if ($destination->packages_count > 0) {
                throw new InvalidArgumentException('Tidak dapat mengapus destinasi, karena destinasi ini sedang digunakan!', 500);
            }
            $destination->delete();
            notify('Berhasil', 'Data destinasi berhasil dihapus!', 'success')->autoClose();
            return redirect()->back();
        } catch (Throwable $th) {
            notify('Oops!', $th->getMessage(), 'error');
            return redirect()->back();
        }
    }


    /**
     * @param Request $request
     * @param Destination $destination
     * @return RedirectResponse
     */
    public function changeStatus(Request $request, Destination $destination)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        /* begin:: start tenant service */
        try {
            $user = auth()->user();
            $destinationService = new DestinationService($user->tenant_id);
            if ($request->has('status')) {
                $destinationService
                    ->setDestination($destination)
                    ->setStatus($request->get('status'));
                notify('Berhasil!', "Status telah berubah", 'success');
                DB::commit();
            }else{
                throw new InvalidArgumentException('Tidak ada yang berubah!');
            }
            return redirect()->back();
        }catch (Throwable $e){
            notify('Oops!', $e->getMessage(), 'error');
            return redirect()->back();
        }
        /* end:: start tenant service */
    }
}
