<?php

use App\Enums\VirtualAccount;
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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->comment('reference to tenant_table');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('package_id')->nullable()->comment('reference to package_table');
            $table->string('model_type', 80)->comment('users_table, jamaah table');
            $table->string('va_number', 30);
            $table->string('va_label', 30)->default(VirtualAccount::tryFrom('tabungan')->keyValue())->comment('tabungan pribadi, perencanaan');
            $table->timestamps(precision: 6);
            $table->softDeletes(precision: 6);

            /* foreign key */
            $table->foreign('package_id')->on('plan_packages')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('virtual_accounts');
    }
};
