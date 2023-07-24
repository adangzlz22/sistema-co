<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->boolean('dropdown');
            $table->unsignedBigInteger('icon_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('published')->default(true);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('menus');
    }
}
