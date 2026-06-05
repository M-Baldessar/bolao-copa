<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_matches', function (Blueprint $table) {
            $table->string('stage')->default('group')->after('match_number');
            $table->foreignId('group_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('game_matches', function (Blueprint $table) {
            $table->dropColumn('stage');
            $table->foreignId('group_id')->nullable(false)->change();
        });
    }
};
