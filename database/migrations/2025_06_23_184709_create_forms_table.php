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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
             $table->foreignId('centre_id')->constrained()->onDelete('cascade');
            $table->foreignId('form_type_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->string('patient_surname');
            $table->json('form_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
