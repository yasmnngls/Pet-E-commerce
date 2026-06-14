<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('pets', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
        $table->string('breed')->nullable();
        $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
        $table->date('birthdate')->nullable();
        $table->integer('age_weeks')->nullable();
        $table->string('color')->nullable();
        $table->decimal('weight', 5, 2)->nullable();
        $table->decimal('price', 10, 2);
        $table->text('description')->nullable();
        $table->string('microchip_number')->nullable();
        $table->text('vaccination_details')->nullable();
        $table->text('pedigree')->nullable();
        $table->string('featured_image')->nullable();
        $table->enum('status', ['available', 'reserved', 'sold', 'pending'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
