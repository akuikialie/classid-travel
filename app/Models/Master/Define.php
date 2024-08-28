<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

class Define extends Model
{
    use HasFactory, SoftDeletes, HashableId;

    protected bool $shouldHashPersist = true;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "defines";

    // protected $keyType = 'uuid';
    // public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['tenant_id', 'type', 'is_active', 'key', 'value', 'order'];

    // SCOPES

    // ACCESSOR & MUTATOR

    // RELATIONSHIPS

    public function addresses(): MorphTo
    {
        return $this->morphTo('model');
    }

    // METHODS
}
