<?php

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
    public function up()
    {
        Schema::create('plan_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->comment('reference to defines_table');
            $table->string('name', 50);
            $table->boolean('is_publish')->default('false')->comment('Jika true, maka hanya bisa R');
            $table->string('status', 10)->default(Statuses::tryFrom('active')->keyValue())->comment('enum in Statuses::class');
            $table->dateTime('deactived_at')->nullable()->comment('terisi ketika status nonactive');
            $table->timestamps();
            $table->softDeletes();

            /* foreign keys */
            $table->foreign('plan_id')->on('defines')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_packages');
    }
};
