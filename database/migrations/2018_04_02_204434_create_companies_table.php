<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('parent_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('company_number')->nullable();
            $table->string('company_address')->nullable();
            $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('is_stat');
            $table->tinyInteger('is_notification');
            $table->integer('communities')->default(1);
            $table->string('image')->nullable();
            $table->string('privacy_document')->nullable();
            $table->timestamps();
        });

        Schema::create('company_post', function (Blueprint $table) {
            $table->integer('company_id');
            $table->integer('post_id');
        });

        Schema::create('ad_company', function (Blueprint $table) {
            $table->integer('ad_id');
            $table->integer('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
