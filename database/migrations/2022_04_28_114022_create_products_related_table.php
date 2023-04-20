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
        Schema::create('products_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_product_id')->nullable();
            $table->unsignedBigInteger('child_product_id')->nullable();
            $table->json('name')->nullable();
            $table->enum('child_name_status', ['default', 'hide', 'custom'])->default('hide');

//            $table->foreign('parent_product_id')->references('id')->on('products')->nullOnDelete()->cascadeOnUpdate();
//            $table->foreign('child_product_id')->references('id')->on('products')->nullOnDelete()->cascadeOnUpdate();

            $table->double('child_quantity')->default(1);
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
        Schema::dropIfExists('products_related');
    }
};
