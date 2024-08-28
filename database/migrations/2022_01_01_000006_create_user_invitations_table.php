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
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            tableHashId($table);
            tableTenantId($table);
            tableUserId($table);
            tableUserId($table, 'invited_by');
            $table->foreignId('link_id')->nullable()
                ->constrained('referral_links')
                ->onDelete('restrict');
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
        Schema::dropIfExists('user_invitations');
    }
};
