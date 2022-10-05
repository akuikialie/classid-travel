<?php

use App\Enums\UserStatus;
use App\Enums\UserType;
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
            $table->string('name');
            $table->string('username')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at', precision: 6)->nullable();
            $table->string('password');
            $table->rememberToken();
            // $table->string('type', 20)->default(UserType::VOLUNTEER->value);
            $table->string('va_number')->nullable();
            $table->string('locale')->default('id_ID');
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('status', 20)->default(UserStatus::ACTIVE->value);
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
        Schema::dropIfExists('users');
    }
};
