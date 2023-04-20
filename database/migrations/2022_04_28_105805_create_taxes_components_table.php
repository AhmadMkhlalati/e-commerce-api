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
        Schema::create('taxes_components', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tax_id');
//            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnDelete();

            $table->unsignedBigInteger('component_tax_id');
//            $table->foreign('component_tax_id')->references('id')->on('taxes')->cascadeOnDelete();

            $table->integer('sort')->nullable();

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
        Schema::dropIfExists('taxes_components');
    }
};
