<?php

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
        Schema::create('invocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class, 'tenant_id')
                ->constrained((new \App\Models\Tenant\Tenant())->getTable())
                ->onDelete('restrict');
            $table->string('invoice_number');
            $table->string('virtual_account');
            $table->string('reference_id');
            $table->string('type');
            $table->string('description')->nullable();
            $table->timestamp('valid_until');

            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invocations');
    }
};
