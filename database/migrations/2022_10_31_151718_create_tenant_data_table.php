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
    public function up()
    {
        Schema::create('tenant_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->comment('reference to tenant_table');
            $table->string('key');
            $table->text('value')->nullable()->comment('main value');
            $table->jsonb('options')->nullable()->comment('optional information');
            $table->boolean('is_active')->default(true);
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_data');
    }
};
