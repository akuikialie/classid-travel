<?php

namespace App\Http\Controllers\Web\Admin\Jamaah;

use App\Http\Controllers\Web\Admin\Controller;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use App\Queries\JamaahQuery;
use App\Services\JamaahService;
use Dentro\Yalr\Attributes\Delete;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Middleware;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

#[Prefix('jamaah')]
#[Name('jamaah', false, true)]
#[Middleware(['auth:sanctum'])]
class JamaahController extends Controller
{
    protected string $forPage = 'jamaah';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->setBreadCrumb(['title' => 'Data Jamaah ', 'url' => routed('admin.jamaah.index')]);
        $this->setData('current_page', $this->forPage);
    }

    #[Post('datatable', name: 'datatable')]
    public function datatable(Request $request, string|null $type = null)
    {
        if (\request()->ajax()) {
            try {
                $filter = request()->input('filter');

                if (isset($filter)) {
                    request()->mergeIfMissing(extract_filters($filter));
                }

                $custom_filter = request()->input('custom');
                if (isset($custom_filter)) {
                    request()->mergeIfMissing($custom_filter);
                }


                $query = JamaahQuery::byTenant(activeTenant()->id)
                    ->filterColumn()
                    ->orderColumn()
                    ->build()
                    ->withCount(['planPackages', 'tabunganPackages']);

                $datatable = datatables()->eloquent($query)
                    ->filter(function (Builder $query) use ($request) {

                    })
                    ->addIndexColumn()
                    ->addColumn('name', function (Jamaah $data) {
                        return $data->user->name;
                    })->addColumn('package_total', function (Jamaah $data) {
                        return $data->plan_packages_count;
                    })
                    ->addColumn('savings_total', function (Jamaah $data) {
                        return $data->tabungan_packages_count + 1;
                    })
                    ->addColumn('created_at', function (Jamaah $data) {
                        if (is_null($data->created_at)) {
                            return '-';
                        }
                        return carbon($data->created_at)->format('d M, Y H:i:s');
                    })
                    ->addColumn('actions', function (Jamaah $data) use ($type) {
                        $this->setData('jamaah', $data);
                        return $this->view('pages.web.jamaah.action.action-datatable');
                    })
                    ->rawColumns(['actions']);

                return $datatable->make(true);
            } catch (Exception $e) {
                logError($e, title: 'Jamaah - datatable');
                if (isDevelopmentMode()) {
                    throw $e;
                }
                throw new \Exception('Terjadi kesalahan!');
            }
        }
        abort(404);
    }

    /**
     * @param Request $request
     * @return View
     * @throws Exception
     */
    #[Get('', name: 'index')]
    public function index(Request $request): View
    {
        $this->setPageTitle('Data Jamaah');
        $this->setBreadCrumb('Data Jamaah');

        $tenant = activeTenant();
        $jamaahs = Jamaah::query()
            ->byTenant($tenant->id)
            ->with(['user:id,name'])
            ->select('id', 'user_id')
            ->get();

        $packages = PlanPackage::query()
            ->when(!empty($request->input('package_id')), function (Builder $builder) use ($request) {
                $builder->where('id', PlanPackage::hashToId($request->input('package_id')));
            })
            ->byTenant($tenant->id)
            ->select('id', 'name')
            ->get();

        $this->setData('packages', $packages);
        $this->setData('jamaahs', $jamaahs);
        return $this->view('pages.web.jamaah.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    #[Post('add-to-package', name: 'add-to-package')]
    public function addToPackage(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'jamaah_id' => ['required', 'string'],
            'package_id' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = auth()->user();
        DB::beginTransaction();
        try {

            $package = PlanPackage::byHash($request->input('package_id'));

            if (!$package instanceof PlanPackage) {
                throw new Exception("Invalid package id.");
            }

            $jamaah = Jamaah::byHash($request->input('jamaah_id'));

            if (!$jamaah instanceof Jamaah) {
                throw new Exception("Invalid jamaah id.");
            }

            /* begin:: add package to jamaah */
            (new JamaahService($user->tenant_id))
                ->setPackage(package: $package)
                ->setJamaah(jamaah: $jamaah)
                ->addPackage();
            /* end:: add package to jamaah */

            DB::commit();
            notify('Berhasil', 'Berhasil menambahkan jamaah kedalam paket!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'package - store');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() >= 900){
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');
            return redirect()->back();
        }
    }

    #[Delete('{jamaah}/remove-from-package', name: 'remove-from-package')]
    public function removeFromPackage(Request $request, Jamaah $jamaah)
    {
        $request->validate([
            'package_id' => ['required', 'string'],
        ]);
        $jamaah->loadMissing(['tabunganPackages' => function ($query) use ($request){
            $query->where('package_id', '=', PlanPackage::hashToId($request->input('package_id')));
        }]);

        DB::beginTransaction();
        try {
            /** @var VirtualAccount $saving */
            $saving = $jamaah->tabunganPackages->first();
            if ($saving->balance > 0 || $saving->usd_balance > 0){
                throw new Exception('Masih terdapat saldo di tabungan');
            }

            $jamaah->planPackages()->detach(PlanPackage::hashToId($request->input('package_id')));
            $saving->forceDelete();

            DB::commit();

            notify('Berhasil', 'Data paket berhasil dibuat!', 'success')->autoClose();

            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollBack();
            logError($e, title: 'package - store');
            if (isDevelopmentMode()) {
                throw $e;
            }
            $message = 'Terjadi kesalahan!';
            if ($e->getCode() >= 900){
                $message = $e->getMessage();
            }
            notify('Oops!', $message, 'error');
            return redirect()->back();
        }
    }

}
