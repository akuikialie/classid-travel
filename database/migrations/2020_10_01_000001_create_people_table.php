<?php

use App\Enums\PersonId;
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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->string('id_type')->default(PersonId::KTP->value);
            $table->string('id_number');
            $table->string('nationality', 25)->default('wni');
            $table->string('birthplace', 100)->nullable();
            $table->date('birthday')->nullable();
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
        Schema::dropIfExists('people');
    }
};
