<?php

namespace App\Jobs\VA;

use App\Enums\VirtualAccount as EnumsVirtualAccount;
use App\Models\Jamaah\Jamaah;
use App\Models\Plan\PlanPackage;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateVirtualAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $va_type;
    protected $planPackage;
    protected $jamaah;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($va_type, Jamaah $jamaah = null, PlanPackage $planPackage = null)
    {
        $this->va_type = EnumsVirtualAccount::tryFrom($va_type)->keyValue();
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
            /* create new VA */
            $VA = VirtualAccount::query()
                ->where(function ($subQuery) {
                    $subQuery->where('va_label', $this->va_type)
                        ->whereMonth('created_at', Carbon::now());
                })->max('va_number');

            $newVANumber = createNewVA($this->va_type, $VA);

            switch ($this->va_type) {
                case 'tabungan':
                    $user = User::query()->find(auth()->user()->id);
                    $newVA = new VirtualAccount([
                        'va_number' => $newVANumber,
                        'va_label' => $this->va_type,
                    ]);

                    $user->tabungan()->save($newVA);
                    $user->save();
                    break;

                case 'perencanaan':
                    $newVA = new VirtualAccount([
                        'va_number' => $newVANumber,
                        'va_label' => $this->va_type,
                    ]);

                    $this->jamaah->tabunganPackages()->save($newVA);

                    $newVA->myPackage()->associate($this->planPackage);
                    $newVA->save();
                    break;

                default:
                    break;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
