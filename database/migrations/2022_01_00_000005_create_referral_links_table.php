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
        Schema::create('referral_links', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->foreignId('package_id')->nullable()
                ->comment('reference to package_table')
                ->constrained('plan_packages')
                ->onDelete('restrict');
            $table->string('summary', 50)->default('User Invitation');
            $table->string('link', 1024);
            $table->string('hash', 60)->unique();
            $table->string('expired_status', 10)->nullable()->default('never');
            $table->timestamp('expired_at', precision: 6)->nullable()->comment('set expired in referal link');
            tableTimestamps($table);
            tableSoftDeletes($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_links');
    }
};
