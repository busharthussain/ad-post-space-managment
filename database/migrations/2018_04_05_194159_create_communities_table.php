<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('borrow_image')->nullable();
            $table->string('swap_image')->nullable();
            $table->string('give_away_image')->nullable();
            $table->string('wanted_image')->nullable();
            $table->string('relative_path')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('is_lock')->default(0);
            $table->string('password')->nullable();
            $table->string('qrcode')->nullable();
            $table->string('qrcode_image')->nullable();
            $table->string('relative_qrcode_path')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });

        Schema::create('community_post', function (Blueprint $table) {
            $table->integer('community_id');
            $table->integer('post_id');
        });

        Schema::create('ad_community', function (Blueprint $table) {
            $table->integer('ad_id');
            $table->integer('community_id');
        });

        Schema::create('community_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('community_id');
            $table->integer('user_id');
            $table->tinyInteger('is_allow')->default(0);
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
        Schema::dropIfExists('communities');
    }
}
