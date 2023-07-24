<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('folio',20);
            $table->string('name',150);
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->text('link')->nullable();
            $table->unsignedBigInteger('place_id')->nullable();
            $table->unsignedBigInteger('meeting_type_id');
            $table->unsignedBigInteger('modality_id')->default(1);
            $table->unsignedBigInteger('status_id')->default(1);
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('meeting_type_id')->references('id')->on('meeting_types')->onDelete('cascade');
            $table->foreign('modality_id')->references('id')->on('modalities')->onDelete('cascade');
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
        Schema::dropIfExists('meetings');
    }
}
