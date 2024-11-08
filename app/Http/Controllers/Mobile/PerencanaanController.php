<?php

namespace App\Http\Controllers\Mobile;

use App\Enums\Statuses;
use App\Models\Plan\PlanPackage;
use Carbon\Carbon;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use function request;

class PerencanaanController extends Controller
{
    public function index()
    {
        $hasilSimulasi = [];
        if (request()->has('type')) {
            try {
                switch (request()->get('type')) {
                    case 'perencanaan-ibadah':
                        $package = PlanPackage::query()
                            ->when(request()->has('package'), function ($subQuery) {
                                $subQuery->where('id', request()->get('package'));
                            }, function ($subQuery) {
                                $subQuery->limit(0);
                            })
                            ->first();

                        if (request()->has('besaran_menabung') && isset($package)) {
                            $besaranMenabung = (int)request()->get('besaran_menabung');
                            $priceOfPackage = $package->amount;
                            $targetSavings = $besaranMenabung;
                            $estimatedDeparture = Carbon::now()
                                ->addMonth($priceOfPackage / $besaranMenabung);

                            $hasilSimulasi = [
                                'package' => (isset($package) ? $package->name : 'unknown'),
                                'price' => numberFormat($priceOfPackage),
                                'target_savings' => numberFormat($targetSavings),
                                'estimated_departure' => $estimatedDeparture->format('F, Y'),
                            ];
                        }

                        break;

                    case 'berangkat-langsung':
                        $package = PlanPackage::query()
                            ->when(request()->has('package'), function ($subQuery) {
                                $subQuery->where('id', request()->get('package'));
                            }, function ($subQuery) {
                                $subQuery->limit(0);
                            })
                            ->first();

                        if (request()->has('tanggal_keberangkatan') && isset($package)) {
                            $tanggalBerangkat = Carbon::parse(request()->get('tanggal_keberangkatan'));

                            /* proses perhitungan biaya keberangkatan */
                            $today = Carbon::now();
                            $range = $today->diffInMonths($tanggalBerangkat);

                            $priceOfPackage = $package->amount;
                            $targetSavings = $priceOfPackage / $range;
                            $estimatedDeparture = $tanggalBerangkat;

                            $hasilSimulasi = [
                                'package' => (isset($package) ? $package->name : 'unknown'),
                                'price' => numberFormat($priceOfPackage),
                                'target_savings' => numberFormat($targetSavings),
                                'estimated_departure' => $estimatedDeparture->format('F, Y'),
                            ];
                        }
                        break;

                    default:
                        $hasilSimulasi = [];
                        break;
                }
            } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
                notify('Gagal', $e->getMessage(), 'error');
                logError($e, title: 'Mobile perencanaan');
                return redirect()->back();
            }
        }

        $user = auth()->user();
        $planPackages = PlanPackage::query()
            ->where(function ($queryScope) {
                $queryScope->where('is_publish', true)
                    ->where('status', Statuses::Active->keyValue());
            })
            ->tenantId($user->tenant_id)
            ->get();

        return view('pages/mobile/perencanaan/check-estimasi-index', [
            'planPackages' => $planPackages,
            'hasil_simulasi' => fluent($hasilSimulasi),
        ]);
    }
}
