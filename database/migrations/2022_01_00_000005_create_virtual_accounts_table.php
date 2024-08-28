<?php

use App\Enums\VirtualAccount;
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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->foreignId('package_id')->nullable()
                ->comment('reference to package_table')
                ->constrained('plan_packages')
                ->onDelete('restrict');
            $table->string('name', 150)->nullable();
            $table->morphs('model');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('va_number', 30)
                ->unique()
                ->comment('as a username to login in wallet service');
            $table->string('va_label', 30)->default(VirtualAccount::tryFrom('tabungan')->keyValue())->comment('tabungan pribadi, perencanaan');
            tableTimestamps($table, precision: 6);
            tableSoftDeletes($table, precision: 6);

            $table->index(['tenant_id', 'package_id', 'name', 'va_number'], 'virtual_accounts_main_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
