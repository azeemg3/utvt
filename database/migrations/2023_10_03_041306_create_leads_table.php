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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('cnic')->nullable();
            $table->unsignedBigInteger('spo')->nullable();
            $table->date('service_date_from')->nullable();
            $table->date('service_date_to')->nullable();
            $table->unsignedBigInteger('CID');
            $table->unsignedBigInteger('CTID');
            $table->json('services')->nullable();
            $table->text('sectors')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->text('other_details')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('takenover_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
