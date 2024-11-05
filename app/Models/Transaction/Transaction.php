<?php

namespace App\Models\Transaction;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $invocation_id
 * @property string $amount
 * @property string $trx_method
 * @property string $trx_type
 * @property string $trx_date
 * */
class Transaction extends Model
{
    use HasUuids;

    protected $table = 'invocations';

    protected $fillable = [
        'tenant_id',
        'invocation_id',
        'amount',
        'trx_method',
        'trx_type',
        'trx_date',
    ];
}
