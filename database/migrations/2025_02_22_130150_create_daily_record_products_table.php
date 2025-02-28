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
        Schema::create('daily_record_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('daily_record_id')->nullable();
            $table->integer('quantity');
            $table->decimal('revenue', 10, 2);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('daily_record_id')->references('id')->on('daily_records')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_record_products');
    }
};
