<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->enum('option', ['email', 'notification', 'message']);
            $table->enum('type', ['community', 'user']);
            $table->enum('device_type', ['android','ios', 'all']);
            $table->text('ids');
            $table->text('read_user_ids')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_managements');
    }
}
