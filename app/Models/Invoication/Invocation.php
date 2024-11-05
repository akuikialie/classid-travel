<?php

namespace App\Models\Invoication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $invoice_number
 * @property string $virtual_account
 * @property string $reference_id
 * @property string $tenant_id
 * @property string $type
 * @property string $description
 * @property Carbon $valid_until
 * */
class Invocation extends Model
{
    use HasUuids;

    protected $table = 'invocations';

    protected $fillable = [
        'invoice_number',
        'virtual_account',
        'reference_id',
        'tenant_id',
        'type',
        'valid_until',
        'description',
    ];
}
