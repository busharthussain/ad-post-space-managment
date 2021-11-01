<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->enum('type', ['admin', 'company', 'company-users', 'app-users']);
            $table->string('name');
            $table->string('sur_name');
            $table->enum('sex', ['male', 'female']);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('device_token')->nullable();
            $table->string('device_type')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->binary('image')->nullable();
            $table->integer('community_id')->nullable();
            $table->integer('is_social')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('authorization_token')->nullable();
            $table->text('interest_tags')->nullable();
            $table->text('looking_tags')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
