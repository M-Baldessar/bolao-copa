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
        Schema::table('game_matches', function (Blueprint $table) {
            $table->foreignId('winner_team_id')->nullable()->after('away_score')
                  ->constrained('teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('game_matches', function (Blueprint $table) {
            $table->dropForeign(['winner_team_id']);
            $table->dropColumn('winner_team_id');
        });
    }
};
