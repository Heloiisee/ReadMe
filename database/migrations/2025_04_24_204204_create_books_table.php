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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable(); // ✔️ Meilleure pratique pour les champs potentiellement vides
            $table->text('description')->nullable();
            $table->string('cover_path')->nullable(); // ✔️ Nommage plus clair que 'cover_image'
            $table->string('file_path'); 
            $table->string('file_type', 10); // ✔️ Ajout du type (epub/pdf) avec longueur limitée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
