<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_incidents', function (Blueprint $table) {
            $table->id();
            $table->longText('message_reply');
            $table->foreignId('from_user_id')->references('id')->on('users');
            $table->foreignId('incident_id')->references('id')->on('incidents');
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
        Schema::dropIfExists('detail_incidents');
    }
}
