<?php

namespace App\Models\GenerateNumber;

use App\Models\Tenant\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;


/**
 * Model Transaction
 *
 * @property string $id
 * @property string $institution_id
 * @property string $numerable_type
 * @property string $numerable_id
 * @property string $type
 * @property string $number_pattern
 * @property int $current_number
 * @property string<'none', 'daily', 'weekly', 'monthly', 'yearly'> $should_reset
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property array  $generated_numbers
 *
 * */
class GenerateNumber extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'generate_numbers';

    protected $fillable = [
        'tenant_id',
        'type',
        'number_pattern',
        'current_number',
        'should_reset',
        'created_at',
        'updated_at',
    ];

    public function tenant(): Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
