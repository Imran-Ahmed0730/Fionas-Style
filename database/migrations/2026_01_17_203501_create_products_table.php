<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default('1')->comment('1=> Single Product, 2=>Group Product');
            $table->string('name')->unique();
            $table->text('short_description');
            $table->text('detailed_description');
            $table->text('additional_information')->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('brand_id')->default(1);
            $table->integer('unit_id')->nullable();
            $table->integer('weight')->nullable();
            $table->string('sku')->nullable();
            $table->string('tags')->nullable();
            $table->text('thumbnail');
            $table->text('video_link')->nullable();
            $table->decimal('regular_price', 8, 2);
            $table->decimal('discount', 8, 2)->default(0);
            $table->tinyInteger('discount_type')->default(1)->comment('1=>Flat, 2=>Percent');
            $table->decimal('selling_price', 8, 2);
            $table->decimal('tax', 8, 2)->default(0);
            $table->tinyInteger('tax_inclusion')->default(1)->comment('1=>Included with price, 2=>Excluded with price');

            $table->integer('stock_qty')->default(0);
            $table->integer('package_stock_qty')->default(0);
            $table->integer('min_buying_qty')->default(1);

            $table->text('color')->nullable();
            $table->tinyInteger('is_variant')->default(0);
            $table->text('attribute_values')->nullable();

            $table->text('shipping_and_return_policy')->nullable();
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->string('shipping_duration')->nullable();
            $table->text('slug');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_image')->nullable();

            $table->integer('review_count')->default(0);
            $table->decimal('rating', 8,1)->default(0);
            $table->bigInteger('sell_count')->default(0);
            $table->bigInteger('view_count')->default(0);

            $table->tinyInteger('tangible')->default(1)->comment('1=>Yes (Physical Product), 0=>No (Digital Product)');
            $table->tinyInteger('cod_available')->default(0)->comment('1=>Yes, 0=>No');
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('include_to_todays_deal')->default(0);
            $table->tinyInteger('is_replaceable')->default(0);
            $table->tinyInteger('is_trending')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0=>Inactive, 1=>Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
