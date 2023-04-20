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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->unsignedBigInteger('currency_id');
//            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->boolean('is_virtual')->default(0);
            $table->unsignedBigInteger('original_price_id')->nullable();
//            $table->foreign('original_price_id')->references('id')->on('prices');
            $table->double('percentage',19,4)->default(0)->nullable();
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
        Schema::dropIfExists('prices');
    }
};
