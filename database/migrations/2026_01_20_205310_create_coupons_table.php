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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('code')->unique();
            $table->tinyInteger('discount_type')->default(1)->comment('1=>Flat, 2=>Percent');
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('min_purchase_price', 10, 2)->default(0);
            $table->integer('total_use_limit')->nullable();
            $table->integer('use_limit_per_user')->nullable();
            $table->bigInteger('applicable_for')->default(0)->nullable()->comment('0=>All customers');
            $table->text('applicable_products')->nullable()->comment('0=>All products');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date');
            $table->tinyInteger('status')->default(0)->comment('0=>Inactive, 1=>Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
