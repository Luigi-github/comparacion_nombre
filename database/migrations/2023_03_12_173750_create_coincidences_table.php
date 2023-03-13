<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coincidences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('match_percentage');
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->string('town')->nullable();
            $table->integer('active_years')->nullable();
            $table->string('person_type')->nullable();
            $table->string('position_type')->nullable();
            $table->bigInteger('match_search_id')->unsigned();
            $table->foreign('match_search_id')->references('id')->on('match_searches');
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
        Schema::dropIfExists('coincidences');
    }
};
