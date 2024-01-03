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
        Schema::create('business_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('BID');
            $table->string('contact_name');
            $table->string('contact_mobile');
            $table->string('contact_wahts_app');
            $table->string('contact_email');
            $table->string('contact_country');
            $table->string('contact_city');
            $table->string('contact_address');
            $table->text('contact_other_details');
            $table->foreign('BID')->references('id')->on('business_settings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_contacts');
    }
};
