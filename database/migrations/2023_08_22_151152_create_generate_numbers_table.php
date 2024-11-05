<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generate_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(\App\Models\Tenant\Tenant::class, 'tenant_id')
                ->constrained((new \App\Models\Tenant\Tenant())->getTable());
            $table->string('type');
            $table->string('number_pattern')->comment('2308 or 2022###');
            $table->unsignedBigInteger('current_number')->default(0);
            $table->enum('should_reset', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Table::GENERATE_NUMBERS->value);
    }
};
