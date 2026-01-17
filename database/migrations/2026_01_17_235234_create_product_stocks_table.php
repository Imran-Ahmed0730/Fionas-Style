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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('supplier_id')->nullable();
            $table->string('variant_name')->nullable();
            $table->string('variant_sku')->nullable();
            $table->decimal('buying_price', 8, 2)->default(0);
            $table->string('sku');
            $table->integer('qty');
            $table->integer('damaged_qty')->default(0);
            $table->tinyInteger('track_error')->default(0)->comment('1=>Yes, 0=>No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
