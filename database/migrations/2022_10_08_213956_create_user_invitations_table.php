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
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('reference to users_table');
            $table->unsignedBigInteger('invited_by')->comment('reference to users_table');
            $table->unsignedBigInteger('link_id')->comment('reference to referal_links_table');
            $table->timestamps();

            /* foreign key */
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->foreign('invited_by')->on('users')->references('id')->onDelete('cascade');
            $table->foreign('link_id')->on('referal_links')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_invitations');
    }
};
