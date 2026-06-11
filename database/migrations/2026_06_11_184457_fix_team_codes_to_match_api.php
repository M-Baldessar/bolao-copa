<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $changes = [
        'REP' => 'CZE', // República Tcheca
        'BOS' => 'BIH', // Bósnia e Herzegovina
        'HTI' => 'HAI', // Haiti
        'URU' => 'URY', // Uruguai
    ];

    public function up(): void
    {
        foreach ($this->changes as $old => $new) {
            DB::table('teams')->where('code', $old)->update(['code' => $new]);
        }
    }

    public function down(): void
    {
        foreach ($this->changes as $old => $new) {
            DB::table('teams')->where('code', $new)->update(['code' => $old]);
        }
    }
};
