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
        Schema::create('composition_rental_applications', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('shelf_id')->nullable();
            $table->foreign('shelf_id')->references('id')->on('shelves');
            
            $table->unsignedBigInteger('rental_application_id')->nullable();
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_rental_applications');
    }
};
