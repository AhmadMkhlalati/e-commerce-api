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
        Schema::create('brands_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
//            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('field_id');
//            $table->foreign('field_id')->references('id')->on('fields')->cascadeOnDelete()->cascadeOnUpdate();

            $table->text('value')->nullable();

            $table->unsignedBigInteger('field_value_id')->nullable();
//            $table->foreign('field_value_id')->references('id')->on('fields_values')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('brands_fields');
    }
};
