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
    Schema::create('seller_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('store_name');
        $table->string('store_type');
        $table->string('legal_name');
        $table->string('id_upload_path')->nullable();
        $table->string('business_registration_path')->nullable();
        $table->text('store_description')->nullable();
        $table->string('facebook_link')->nullable();
        $table->string('instagram_link')->nullable();
        $table->string('website_link')->nullable();
        $table->text('business_address');
        $table->string('customer_support_contact');
        $table->string('bank_name')->nullable();
        $table->string('bank_account_number')->nullable();
        $table->string('bank_account_holder')->nullable();
        $table->json('product_categories')->nullable();
        $table->json('shipping_methods')->nullable();
        $table->boolean('terms_accepted')->default(false);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->text('admin_notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_applications');
    }
};
