<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTypeUserTable extends Migration
{
    public function up()
    {
        Schema::create('notification_type_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('notification_type_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('notification_type_id')->references('id')->on('notification_types');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_type_user');
    }
}
