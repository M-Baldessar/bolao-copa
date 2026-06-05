<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Limpa palpites existentes (dados de teste sem contexto de bolão)
        DB::table('predictions')->truncate();

        // Adiciona a coluna e o novo índice primeiro
        Schema::table('predictions', function (Blueprint $table) {
            $table->foreignId('bolao_group_id')
                  ->after('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unique(['user_id', 'bolao_group_id', 'match_id']);
        });

        // Remove o índice antigo separadamente (MySQL exige que o novo já exista)
        Schema::table('predictions', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'match_id']);
        });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'bolao_group_id', 'match_id']);
            $table->dropConstrainedForeignId('bolao_group_id');
            $table->unique(['user_id', 'match_id']);
        });
    }
};
