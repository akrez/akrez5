<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTables extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at', 0)->nullable();
            $table->softDeletes();
            $table->string('name', 60);
            $table->string('model_class')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('blog_name', 60)->nullable();
        });
    }

    public function down()
    {
        Schema::drop('tags');
    }
}
