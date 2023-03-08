<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepmanageTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stepmange', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('number')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_by')->nullable();
            $table->enum('status', ["active", "inactive"])->default("inactive");
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
        Schema::dropIfExists('stepmange');
    }
}
