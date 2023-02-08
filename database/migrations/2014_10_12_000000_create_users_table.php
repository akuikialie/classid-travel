<?php

use App\Enums\UserStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable()->comment('reference to tenant_table');
            $table->string('name');
            $table->string('username')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at', precision: 6)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('status', 20)->default(UserStatus::ACTIVE->value);
            $table->boolean('is_super')->default('false');
            $table->dateTime('last_login_at')->nullable();
            $table->string('locale')->default('id_ID');
            $table->string('timezone')->default('Asia/Jakarta');
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
        if (Schema::hasTable('referral_links')) {
            Schema::table('referral_links', function(Blueprint  $table){
                $table->dropConstrainedForeignId('created_by');
            });
        }

        Schema::dropIfExists('users');
    }
};
