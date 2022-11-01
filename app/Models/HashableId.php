<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

trait HashableId
{
    public function hash(): Attribute
    {
        return Attribute::make(
            get: fn ($val) => Hashids::encode($this->getOriginal($this->getKeyName())),
        );
    }

    public function scopeByHash(Builder $query, ?string $hash, ?string $keyName = null)
    {
        if (!$hash) return null;

        //untuk beberapa hash return array
        $id = HashIds::decode($hash);
        if (is_array($id)) {
            $id = $id[0];
        }

        return $query->where($keyName ?? $this->getKeyName(), $id)->first();
    }

    public function scopeByHashOrFail(Builder $query, ?string $hash, ?string $keyName = null)
    {
        $data = $this->scopeByHash($query, $hash, $keyName);

        if (!$data) {
            abort(404);
        }

        return $data;
    }
}
