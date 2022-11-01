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
        Schema::create('referral_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->comment('reference to tenant_table');
            $table->unsignedBigInteger('package_id')->comment('reference to plan_packages_table');
            $table->unsignedBigInteger('created_by')->comment('reference to users_table');
            $table->string('summary', 50)->default('User Invitation');
            $table->text('link');
            $table->string('hash', 60)->unique();
            $table->string('expired_status', 10)->nullable()->default('never');
            $table->dateTime('expired_at')->nullable()->comment('set expired in referal link');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

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
        Schema::dropIfExists('referral_links');
    }
};
