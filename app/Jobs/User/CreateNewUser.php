<?php

namespace App\Jobs\User;

use App\Models\Jamaah\Jamaah;
use App\Models\User;
use App\Models\VA\VirtualAccount;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::query()->create($this->input);

        $VA = VirtualAccount::query()
            ->where(function ($subQuery) {
                $subQuery->where('va_label', 'tabungan')
                    ->whereMonth('created_at', Carbon::now());
            })->max('va_number');

        $newVANumber = createNewVA('tabungan', $VA);
        $newVA = new VirtualAccount([
            'va_number' => $newVANumber,
            'va_label' => 'tabungan',
        ]);

        $user->tabungan()->save($newVA);
        $user->save();

        $newJamaah = new Jamaah();
        $user->jamaah()->save($newJamaah);
    }
}
