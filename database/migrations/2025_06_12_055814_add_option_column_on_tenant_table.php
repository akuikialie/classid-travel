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
        $tableName = (new \App\Models\Tenant\Tenant())->getTable();
        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (! Schema::hasColumn($tableName, 'options')) {
                $default = [
                    'style' => [
                        'bg_color' => '#611e91',
                        'bg_inverse' => '#fff',
                        'color' => '#611e91',
                    ],
                ];
                $table->jsonb('options')->default(json_encode($default));
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
