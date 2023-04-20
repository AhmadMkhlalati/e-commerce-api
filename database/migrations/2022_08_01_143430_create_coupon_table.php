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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->json('title');
            $table->string('code')->unique();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('min_amount')->nullable();
            $table->boolean('is_one_time')->default(0);
            $table->boolean('is_used')->default(0);

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
        Schema::dropIfExists('coupons');
    }
};
