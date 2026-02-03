<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('1=>Online, 2=>POS');
            $table->string('invoice_no')->unique()->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->tinyInteger('free_shipping')->default(0)->comment('0=No, 1=Yes');
            $table->decimal('discount', 15, 2)->default(0);
            $table->smallInteger('discount_type')->default(1)->comment('1=percentage, 2=fixed');
            $table->integer('coupon_id')->nullable();
            $table->decimal('coupon_discount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->bigInteger('payment_method_id')->nullable();
            $table->string('payment_status')->default('0')->comment('0=unpaid, 1=paid, 2=partial, 3=refunded');
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->date('confirmed_at')->nullable();
            $table->date('shipped_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->smallInteger('status')->default(0)->comment('0=pending, 1=confirmed, 2=processing, 3=shipped, 4=delivered, 5=cancelled, 6=hold');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
