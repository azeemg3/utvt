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
        Schema::create('business_smtps', function (Blueprint $table) {
            $table->id();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_address')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_from')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->unsignedBigInteger('BID');
            $table->foreign('BID')->references('id')->on('business_settings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_smtps');
    }
};
