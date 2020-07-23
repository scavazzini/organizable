<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTypesTable extends Migration
{
    public function up()
    {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('description');
        });

        DB::table('notification_types')->insert([
            [
                'id' => 'upcoming-events',
                'name' => 'Upcoming Events',
                'description' => 'Receive email reminders of your upcoming events.'
            ],
            [
                'id' => 'guest-joined',
                'name' => 'Guest joined your event',
                'description' => 'Receive email notification when an invited guest join your event.'
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('notification_types');
    }
}
