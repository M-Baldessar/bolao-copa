<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bolao_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 8)->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('bolao_group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bolao_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('joined_at')->useCurrent();
            $table->unique(['bolao_group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bolao_group_user');
        Schema::dropIfExists('bolao_groups');
    }
};
