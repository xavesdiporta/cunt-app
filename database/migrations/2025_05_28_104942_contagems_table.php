<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contagems', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pessoa_id')->index();
            $table->unsignedInteger('palavra_id')->index();
            $table->decimal('valor', 8, 2);
            $table->timestamps();

            // Removido o UNIQUE constraint para permitir múltiplos registros
            // Mantemos apenas os índices simples para performance
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('contagems');
    }
};
