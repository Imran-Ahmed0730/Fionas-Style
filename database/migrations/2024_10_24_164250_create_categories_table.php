<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->bigInteger('parent_id')->default(0);
            $table->integer('level')->default(1);
            $table->integer('priority')->default(1);
            $table->integer('included_to_home')->default(0);
            $table->text('icon')->nullable();
            $table->text('cover_photo')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            $table->tinyInteger('is_featured')->default(0)->comment('1=>Featured,0=>Not Featured');
            $table->tinyInteger('status')->default(1)->comment('1=>Active,0=>Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
