<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_name')->nullable();
            $table->string('service_location')->nullable();
            $table->string('coverage_area')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('user_role', ["supplier", "organiser"])->nullable();
            $table->enum('status', ["active", "inactive"])->default("inactive");
            $table->integer('is_feature')->default("0");
            $table->integer('is_verfied')->default("0");
            $table->string('password');
            $table->string('remember_token')->nullable(); 
            $table->string('affiliate_id')->unique();
            $table->integer('referred_by')->nullable(); 
            $table->integer('referral_wallet')->nullable();
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
        Schema::dropIfExists('front_users');
    }
}
