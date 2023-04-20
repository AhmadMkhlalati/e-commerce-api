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
        Schema::create('currencies_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
//            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnDelete();
            $table->double('rate')->default(0);
            $table->timestamps(); // update_at will removed in model
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies_histories');
    }
};
