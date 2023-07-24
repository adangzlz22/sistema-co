<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('o_name',150);
            $table->string('ext',5);
            $table->string('path',250);
            $table->string('url',250);
            $table->integer('modulo');
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('file_category_id')->default(1);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('status_id')->default(Status::ACTIVO);
            $table->timestamps();

            $table->foreign('file_category_id')->references('id')->on('file_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
