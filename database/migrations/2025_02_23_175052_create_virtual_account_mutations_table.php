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
        Schema::create('virtual_account_mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\User::class, 'actor_id')
                ->constrained('users')
                ->onDelete('restrict');
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class, 'tenant_id')
                ->constrained('tenants')
                ->onDelete('restrict');
            $table->foreignIdFor(\App\Models\VA\VirtualAccount::class, 'virtual_account_id')
                ->constrained('virtual_accounts')
                ->onDelete('restrict');
            $table->float('amount_before')->default(0);
            $table->float('amount')->default(0);
            $table->float('amount_after')->default(0);
            $table->float('currency_exchange_rate')->default(0);
            $table->float('usd_amount_before')->default(0);
            $table->float('usd_amount')->default(0);
            $table->float('usd_amount_after')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_account_mutations');
    }
};
