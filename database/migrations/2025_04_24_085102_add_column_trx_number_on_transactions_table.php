<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_trx_type_check');
        DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_trx_method_check');

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('trx_method')->change();
            $table->string('trx_type')->change();
            if (!Schema::hasColumn('transactions', 'trx_number')){
                $table->string('trx_number')->nullable();
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
