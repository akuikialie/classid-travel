<?php

use App\Models\Base\NotificationLog;
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
        try {
            Schema::create('notification_logs', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('process_id')->comment('notification process id');
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->string('channel', 50)->comment('woowa, telegram, fcm');
                $table->string('source')->comment('notif dikirim dari');
                $table->string('sender', 100);
                $table->string('receiver', 100);
                $table->text('content');
                $table->string('sent_status', 20)->default('rto')->comment('ok, rto');
                $table->string('resp_status', 50)->default('pending')->comment('ok, failed, pending');
                $table->string('resp_id', 100)->nullable();
                $table->string('channel_status', 50)->nullable();
                $table->string('type', 50)->default('system')->comment('system, manual');
                $table->timestamp('requested_at', precision: 6)->nullable()->comment('timestamp ketika msnotif request ke channel');
                $table->boolean("is_group")->default(false);
                $table->string('request_id')->nullable();
                $table->string('sender_request_id')->nullable();
                $table->jsonb('raw_response')->nullable();
                $table->timestamps(precision: 6);
                $table->softDeletes(precision: 6);

                $table->index(['channel', 'sender']);
            });
        } catch (\Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
