<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('knowledge_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('knowledge_module_id')->constrained('knowledge_modules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->unsignedInteger('duration_sec')->default(0);

            // Métricas del monitoreo (WS + eventos del navegador)
            $table->json('proctoring_metrics')->nullable(); // todo el paquete
            $table->unsignedInteger('alert_count')->default(0);

            // Resultado pedagógico (mini-quiz/actividad)
            $table->json('answers')->nullable();
            $table->unsignedInteger('score')->default(0);

            // Estado
            $table->enum('status', ['reading', 'completed', 'flagged', 'closed'])->default('reading');

            $table->timestamps();
            $table->index(['knowledge_module_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_sessions');
    }
};
