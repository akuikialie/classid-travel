<?php

namespace App\Jobs\Plan\Package;

use App\Enums\VirtualAccount;
use App\Jobs\VA\CreateVirtualAccount;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Services\VirtualAccountService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddPackageToJamaah implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key = 'perencanaan';

    protected $planPackage;
    protected $jamaah;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PlanPackage $planPackage, Jamaah $jamaah)
    {
        $this->planPackage = $planPackage;
        $this->jamaah = $jamaah;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            /* check package on jamaah */
            // dd($this->jamaah->with(['planPackages'])->first());


            /* add package to jamaah */
            $this->jamaah->planPackages()->attach($this->planPackage->id);


            /* creating va */
            dispatch(new CreateVirtualAccount(VirtualAccount::tryFrom($this->key)->keyValue(), $this->jamaah, $this->planPackage));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
