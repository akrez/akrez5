<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('model_class')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('key')->nullable();
            $table->string('value');
            $table->decimal('seq')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->integer('created_by')->nullable();
            $table->string('blog_name', 60)->nullable();
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
        Schema::dropIfExists('labels');
    }
}
