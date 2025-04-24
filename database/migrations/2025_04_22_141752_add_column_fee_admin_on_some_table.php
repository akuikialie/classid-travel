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
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'fee_admin')){
                $table->decimal('fee_admin', 15, 2)->default(0);
            }
        });

        Schema::table('mutations', function (Blueprint $table) {
            if (!Schema::hasColumn('mutations', 'fee_admin')){
                $table->decimal('fee_admin', 15, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
