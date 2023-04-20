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
        Schema::create('discounts_entities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('discount_id')->nullable();
//            $table->foreign('discount_id')->references('id')->on('discounts')->nullOnDelete();

            $table->unsignedBigInteger('category_id')->nullable();
//            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();

            $table->unsignedBigInteger('brand_id')->nullable();
//            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();

            $table->unsignedBigInteger('tag_id')->nullable();
//            $table->foreign('tag_id')->references('id')->on('tags')->nullOnDelete();

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
        Schema::dropIfExists('discounts_entities');
    }
};
