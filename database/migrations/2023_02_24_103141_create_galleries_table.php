<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->string('name', 60)->primary();
            $table->string('blog_name', 60)->nullable();
            $table->string('model_class')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('category')->nullable();
            $table->string('ext', 8);
            $table->decimal('seq')->nullable();
            $table->timestamp('selected_at', 0)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galleries');
    }
}
