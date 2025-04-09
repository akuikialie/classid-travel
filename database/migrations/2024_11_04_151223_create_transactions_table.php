<?php

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoication\Invocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class, 'tenant_id')
                ->constrained((new \App\Models\Tenant\Tenant())->getTable());
            $table->foreignIdFor(Invocation::class, 'invocation_id')->nullable()
                ->constrained((new Invocation())->getTable());
            $table->decimal('amount', 14);
            $table->enum('trx_method', TransactionMethod::values());
            $table->enum('trx_type', TransactionType::values());
            $table->dateTime('trx_date');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
