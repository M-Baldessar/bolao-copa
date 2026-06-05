<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('champion_picks', function (Blueprint $table) {
            $table->foreignId('runner_up_team_id')->nullable()->after('team_id')->constrained('teams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('champion_picks', function (Blueprint $table) {
            $table->dropForeign(['runner_up_team_id']);
            $table->dropColumn('runner_up_team_id');
        });
    }
};
