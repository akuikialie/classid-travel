<?php

use App\Models\Transaction\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->morphs('mutable');
            $table->foreignIdFor(Transaction::class,'transaction_id')
                ->constrained((new Transaction())->getTable())
                ->onUpdate('restrict');
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class,'tenant_id')
                ->constrained((new \App\Models\Tenant\Tenant())->getTable())
                ->onUpdate('restrict');
            $table->string('type');
            $table->string('info')->nullable();
            $table->decimal('amount', 14);
            $table->decimal('amount_before', 14);
            $table->decimal('amount_after', 14);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
