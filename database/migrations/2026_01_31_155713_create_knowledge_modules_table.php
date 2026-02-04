<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('knowledge_modules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

            // Entrada del profe
            $table->string('topic'); // tema solicitado
            $table->enum('pedagogy_model', [
                'arcs_keller',
                'gagne_9_events',
                'blooms_taxonomy',
                'constructivism_scaffold',
                'spaced_retrieval'
            ])->default('arcs_keller');

            // Salida IA (guardamos estructurado)
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content')->nullable(); // lectura principal
            $table->json('activities')->nullable();  // preguntas/actividades
            $table->json('key_concepts')->nullable();

            $table->unsignedInteger('estimated_minutes')->default(10);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->index(['course_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_modules');
    }
};
