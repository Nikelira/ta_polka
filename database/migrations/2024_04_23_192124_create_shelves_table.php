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
        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->integer('number_shelv');
            $table->integer('number_wardrobe');
            $table->unsignedBigInteger('shelf_status_id')->nullable();
            $table->foreign('shelf_status_id')->references('id')->on('shelf_statuses');
            $table->integer('length');
            $table->integer('wigth');
            $table->double('cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelves');
    }
};
