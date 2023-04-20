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
        Schema::create('categories_fields', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id');
//            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate();

            $table->unsignedBigInteger('field_id');
//            $table->foreign('field_id')->references('id')->on('fields')->cascadeOnUpdate();

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
        Schema::dropIfExists('categories_fields');
    }
};
