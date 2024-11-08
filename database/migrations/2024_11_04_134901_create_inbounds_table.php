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
        Schema::create('inbounds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip');
            $table->string('user_agent');
            $table->string('method');
            $table->text('url');
            $table->text('actions');
            $table->jsonb('headers');
            $table->jsonb('params');
            $table->jsonb('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbounds');
    }
};
