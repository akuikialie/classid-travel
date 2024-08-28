<?php

use App\Enums\Kuartal;
use App\Enums\Statuses;
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
        Schema::create('plan_packages', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            $table->foreignId('plan_id')->nullable()
                ->comment('reference to defines_table')
                ->constrained('defines')
                ->onDelete('restrict');
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->double('amount', 15, 2);
            $table->year('departure_year')->nullable()->comment('Tahun keberangkatan');
            $table->string('kuartal', 3)->nullable()->comment('kuartal keberangkatan, enum in Kuartal::class');
            $table->unsignedTinyInteger('long_days')->nullable()->comment('Lama perjalanan ');
            $table->boolean('is_publish')->default('true')->comment('Jika true, maka hanya bisa Read');
            $table->string('status', 10)->default(Statuses::tryFrom('active')->keyValue())->comment('enum in Statuses::class');
            $table->timestamp('deactivate_at')->nullable()->comment('terisi ketika status nonactive');
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['tenant_id', 'name'], 'plan_packages_main_unique');
            $table->index(['tenant_id', 'name', 'departure_year'], 'plan_packages_main_index');
            $table->index(['is_publish', 'status', 'deactivate_at'], 'plan_packages_active_index');
        });

        Schema::create('model_has_package', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            $table->foreignId('plan_package_id')->nullable()
                ->comment('reference to destinations_table')
                ->constrained('plan_packages')
                ->onDelete('restrict');
            $table->morphs('model');
            tableTimestamps($table);
            tableSoftDeletes($table);

            $table->unique(['plan_package_id', 'model_type', 'model_id'], 'model_has_package_main_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_package');
        Schema::dropIfExists('plan_packages');
    }
};
