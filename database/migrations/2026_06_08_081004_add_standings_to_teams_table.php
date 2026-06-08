<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->default(0)->after('group_id');
            $table->unsignedTinyInteger('points')->default(0)->after('position');
            $table->unsignedTinyInteger('played')->default(0)->after('points');
            $table->unsignedTinyInteger('won')->default(0)->after('played');
            $table->unsignedTinyInteger('drawn')->default(0)->after('won');
            $table->unsignedTinyInteger('lost')->default(0)->after('drawn');
            $table->unsignedTinyInteger('goals_for')->default(0)->after('lost');
            $table->unsignedTinyInteger('goals_against')->default(0)->after('goals_for');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['position', 'points', 'played', 'won', 'drawn', 'lost', 'goals_for', 'goals_against']);
        });
    }
};
