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
            $table->string('name',50);
            $table->unsignedBigInteger('product_status_id')->nullable();
            $table->foreign('product_status_id')->references('id')->on('product_statuses');
            $table->unsignedBigInteger('rental_application_id')->nullable();
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->text('description', 255);
            $table->string('photo_path', 50);
            $table->double('cost');
            $table->text('message', 255)->nullable();
            $table->integer('count');
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
