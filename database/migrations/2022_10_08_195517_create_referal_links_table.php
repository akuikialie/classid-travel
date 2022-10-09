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
        Schema::create('referal_links', function (Blueprint $table) {
            $table->id();
            $table->string('summary', 50)->default('User Invitation');
            $table->text('link');
            $table->string('hash', 60)->unique();
            $table->unsignedBigInteger('package_id')->comment('reference to plan_packages_table');
            $table->unsignedBigInteger('created_by')->comment('reference to users_table');
            $table->string('expired_status', 10)->nullable()->default('never');
            $table->dateTime('expired_at')->nullable()->comment('set expired in referal link');
            $table->timestamps();

            /* foreign key */
            $table->foreign('package_id')->on('plan_packages')->references('id')->onDelete('cascade');
            $table->foreign('created_by')->on('users')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referal_link');
    }
};
