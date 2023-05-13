<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at', 0)->nullable();
            $table->string('blog_name', 60)->nullable();
            $table->string('ip', 60)->nullable();
            $table->string('method', 11)->nullable();
            $table->text('url')->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedTinyInteger('http_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
