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
        // Schema::create('book_categories', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId(column:'category_id')->references(column:'id')->on(table:'categories')->onDelete(action:'cascade');
        //     $table->foreignId(column:'book_id')->references(column:'id')->on(table:'books')->onDelete(action:'cascade');
        //     $table->timestamps();
        // });
    }
# php artisan make:migration 2023_11_03_210539_create_book_categories_table
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_categories');
    }
};
