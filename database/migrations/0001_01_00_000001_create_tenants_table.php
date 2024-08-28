<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('app_domain')->unique();
            $table->string('bcn')->unique()->comment('Bank Code Number');
            $table->boolean('is_active')->default(true);
            $table->jsonb('wallet_login')->nullable();
            $table->string('locale', 20)->default('id_ID');
            $table->string('timezone', 100)->default('Asia/Jakarta');
            tableTimestamps($table, constrained: false);
            tableSoftDeletes($table, constrained: false);

            $table->index(['slug', 'app_domain', 'name', 'bcn', 'is_active'], 'tenants_main_index');
        });

        Schema::create('tenant_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            tableTenantId($table);
            $table->string('key');
            $table->text('value')->nullable()->comment('main value');
            $table->jsonb('options')->nullable()->comment('optional information');
            $table->boolean('is_active')->default(true);
            tableTimestamps($table, constrained: false);
            tableSoftDeletes($table, constrained: false);

            $table->index(['tenant_id', 'key', 'is_active'], 'tenant_data_main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants_data');
        Schema::dropIfExists('tenants');
    }
};
