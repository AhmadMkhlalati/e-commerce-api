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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug',250)->nullable()->unique();
            $table->unsignedBigInteger('category_id')->nullable();
//            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate();
            $table->string('code',250)->unique();
            $table->string('sku',250)->nullable();
            $table->enum('type',['normal','bundle','service','variable','variable_child']);
            $table->unsignedBigInteger('unit_id')->nullable();
//            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnUpdate();
            $table->double('quantity')->default(0);
            $table->double('reserved_quantity')->nullable();
            $table->double('minimum_quantity')->default(0);
            $table->json('summary')->nullable();
            $table->enum('bundle_price_status', ['default', 'from_products'])->nullable();
            $table->boolean('is_same_price_as_parent')->default(0);
            $table->json('specification')->nullable();
            $table->boolean('is_same_dimensions_as_parent')->default(false);
            $table->text('image')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
//            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnUpdate();
            $table->unsignedBigInteger('tax_id')->nullable();
//            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keyword')->nullable();
            $table->json('description')->nullable();
            $table->enum('website_status',['draft','published','pending_review'])->default('draft');
            $table->string('barcode',250)->nullable();
            $table->double('bundle_reserved_quantity')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('length')->nullable();
            $table->double('weight')->nullable();
            $table->boolean('is_disabled')->default(0);
            $table->integer('sort')->nullable();
            $table->unsignedBigInteger('parent_product_id')->nullable();
            $table->unsignedBigInteger('products_statuses_id');
//            $table->foreign('products_statuses_id','products_statuses_id_products_statuses')->references('id')->on('products_statuses')->nullOnDelete();
            $table->boolean('is_default_child')->default(0);
            $table->boolean('is_show_related_product')->default(0);
            $table->boolean('pre_order')->default(0);

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
        Schema::dropIfExists('products');
    }
};
