<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('contact_type')->nullable();
            $table->string('title')->nullable();
            $table->string('content', 1023)->nullable();
            $table->text('link')->nullable();
            $table->decimal('seq')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('contact_status')->nullable();
            $table->string('blog_name', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
