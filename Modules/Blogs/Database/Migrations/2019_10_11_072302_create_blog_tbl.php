<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTbl extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('blog_category_id');
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('order_by')->nullable();
            $table->enum('is_featured', ["0", "1"])->default("0");
            $table->enum('status', ["active", "inactive"])->default("inactive");
            $table->timestamp('created_time')->nullable();
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('blogs');
    }

}
