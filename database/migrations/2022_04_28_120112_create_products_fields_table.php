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
        Schema::create('products_fields', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
//            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

            $table->unsignedBigInteger('field_id');
//            $table->foreign('field_id')->references('id')->on('fields')->cascadeOnDelete();

            $table->unsignedBigInteger('field_value_id')->nullable();
//            $table->foreign('field_value_id')->references('id')->on('fields_values')->nullOnDelete();

            $table->text('value')->nullable();

            $table->boolean('is_used_for_variations')->default(0);

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
        Schema::dropIfExists('products_fields');
    }
};
