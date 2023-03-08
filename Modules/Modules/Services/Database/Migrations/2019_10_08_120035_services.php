<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Services extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
             $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug');
            $table->string('sort_description')->nullable();
            $table->text('description')->nullable();
            $table->text('about')->nullable();
            $table->string('image')->nullable();
            $table->string('tags')->nullable();
            $table->integer('is_featured')->default('0')->comment('0, 1');
            $table->enum('status', ['active', 'inactive'])->comment('active, inactive');
            $table->integer('order_by')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('services');
    }

}
