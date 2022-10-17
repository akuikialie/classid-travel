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
    public function up()
    {
        Schema::create('plan_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->comment('reference to defines_table');
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->double('amount', 15, 2);
            $table->year('departure_year')->nullable()->comment('Tahun keberangkatan');
            $table->string('kuartal', 3)->nullable()->comment('kuartal keberangkatan, enum in Kuartal::class');
            $table->unsignedTinyInteger('long_days')->nullable()->comment('Lama perjalanan ');
            $table->boolean('is_publish')->default('true')->comment('Jika true, maka hanya bisa Read');
            $table->string('status', 10)->default(Statuses::tryFrom('active')->keyValue())->comment('enum in Statuses::class');
            $table->dateTime('deactived_at')->nullable()->comment('terisi ketika status nonactive');
            $table->timestamps();
            $table->softDeletes();

            /* foreign keys */
            $table->foreign('plan_id')->on('defines')->references('id')->onDelete('cascade');
        });

        Schema::create('model_has_package', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 50);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('plan_package_id')->comment('reference to destinations_table');

            $table->timestamps();

            /* foreign keys */
            $table->foreign('plan_package_id')->on('plan_packages')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('virtual_accounts')) {
            Schema::table('virtual_accounts', function (Blueprint  $table) {
                $table->dropConstrainedForeignId('package_id');
            });
        }

        if (Schema::hasTable('referal_links')) {
            Schema::table('referal_links', function (Blueprint  $table) {
                $table->dropConstrainedForeignId('package_id');
            });
        }

        Schema::dropIfExists('model_has_package');
        Schema::dropIfExists('plan_packages');
    }
};
