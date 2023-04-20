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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('prefix')->unique();
            $table->time('time')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->double('currency_rate')->nullable();
            $table->double('total')->nullable();
            $table->double('tax_total')->nullable();
            $table->double('discount_percentage')->nullable();
            $table->double('discount_amount')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->text('customer_comment')->nullable();
            $table->unsignedBigInteger('order_status_id')->nullable();


            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->text('shipping_address_one')->nullable();
            $table->text('shipping_address_two')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_company_name')->nullable();
            $table->unsignedBigInteger('shipping_country_id')->nullable();
            $table->string('shipping_email')->nullable();
            $table->date('date')->nullable();
            $table->string('shipping_phone_number')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->unsignedBigInteger('currency_id');
            $table->boolean('is_billing_as_shipping')->default(0);

            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->text('billing_address_one')->nullable();
            $table->string('billing_company_name')->nullable();
            $table->text('billing_address_two')->nullable();
            $table->string('billing_city')->nullable();
            $table->unsignedBigInteger('billing_country_id')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone_number')->nullable();
            $table->text('billing_customer_notes')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();


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
        Schema::dropIfExists('orders');
    }
};
