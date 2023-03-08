<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('faq', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('categories_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('order_by')->nullable();
            $table->enum('status', ["active", "inactive"])->default("inactive");
            $table->foreign('categories_id')->references('id')->on('faq_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('faq');
    }

}
