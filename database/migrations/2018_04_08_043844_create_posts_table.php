<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('batch_id');
            $table->string('active')->default(1);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->integer('category_id');
            $table->integer('parent_category_id');
            $table->integer('product_condition_id');
            $table->integer('created_by');
            $table->tinyInteger('is_completed')->default(0)->nullable();
            $table->date('borrow_to')->nullable();
            $table->date('borrow_from')->nullable();
            $table->timestamps();
        });

        Schema::create('post_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('user_id');
            $table->string('message');
            $table->timestamps();
        });

        Schema::create('post_favourite', function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('user_id');
            $table->tinyInteger('favourite')->default(0);
            $table->timestamps();
        });

        Schema::create('post_start_conversation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('user_id');
            $table->integer('receiver_id');
            $table->text('message')->nullable();
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->tinyInteger('is_request')->default(1);
            $table->tinyInteger('is_read')->default(0)->nullable();
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
        Schema::dropIfExists('posts');
    }
}
