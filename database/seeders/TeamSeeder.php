<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            'A' => [
                ['name' => 'México',              'code' => 'MEX', 'flag_emoji' => '🇲🇽', 'strength' => 78],
                ['name' => 'África do Sul',        'code' => 'RSA', 'flag_emoji' => '🇿🇦', 'strength' => 53],
                ['name' => 'Coreia do Sul',        'code' => 'KOR', 'flag_emoji' => '🇰🇷', 'strength' => 72],
                ['name' => 'República Tcheca',     'code' => 'REP', 'flag_emoji' => '🇨🇿', 'strength' => 67],
            ],
            'B' => [
                ['name' => 'Canadá',               'code' => 'CAN', 'flag_emoji' => '🇨🇦', 'strength' => 74],
                ['name' => 'Bósnia e Herzegovina', 'code' => 'BOS', 'flag_emoji' => '🇧🇦', 'strength' => 60],
                ['name' => 'Catar',                'code' => 'QAT', 'flag_emoji' => '🇶🇦', 'strength' => 54],
                ['name' => 'Suíça',                'code' => 'SUI', 'flag_emoji' => '🇨🇭', 'strength' => 78],
            ],
            'C' => [
                ['name' => 'Brasil',               'code' => 'BRA', 'flag_emoji' => '🇧🇷', 'strength' => 91],
                ['name' => 'Marrocos',             'code' => 'MAR', 'flag_emoji' => '🇲🇦', 'strength' => 76],
                ['name' => 'Haiti',                'code' => 'HTI', 'flag_emoji' => '🇭🇹', 'strength' => 42],
                ['name' => 'Escócia',              'code' => 'SCO', 'flag_emoji' => '🏴󠁧󠁢󠁳󠁣󠁴󠁿', 'strength' => 68],
            ],
            'D' => [
                ['name' => 'Estados Unidos',       'code' => 'USA', 'flag_emoji' => '🇺🇸', 'strength' => 72],
                ['name' => 'Paraguai',             'code' => 'PAR', 'flag_emoji' => '🇵🇾', 'strength' => 62],
                ['name' => 'Austrália',            'code' => 'AUS', 'flag_emoji' => '🇦🇺', 'strength' => 68],
                ['name' => 'Turquia',              'code' => 'TUR', 'flag_emoji' => '🇹🇷', 'strength' => 72],
            ],
            'E' => [
                ['name' => 'Alemanha',             'code' => 'GER', 'flag_emoji' => '🇩🇪', 'strength' => 86],
                ['name' => 'Curaçao',              'code' => 'CUW', 'flag_emoji' => '🇨🇼', 'strength' => 45],
                ['name' => 'Costa do Marfim',      'code' => 'CIV', 'flag_emoji' => '🇨🇮', 'strength' => 68],
                ['name' => 'Equador',              'code' => 'ECU', 'flag_emoji' => '🇪🇨', 'strength' => 68],
            ],
            'F' => [
                ['name' => 'Holanda',              'code' => 'NED', 'flag_emoji' => '🇳🇱', 'strength' => 86],
                ['name' => 'Japão',                'code' => 'JPN', 'flag_emoji' => '🇯🇵', 'strength' => 74],
                ['name' => 'Suécia',               'code' => 'SWE', 'flag_emoji' => '🇸🇪', 'strength' => 72],
                ['name' => 'Tunísia',              'code' => 'TUN', 'flag_emoji' => '🇹🇳', 'strength' => 62],
            ],
            'G' => [
                ['name' => 'Bélgica',              'code' => 'BEL', 'flag_emoji' => '🇧🇪', 'strength' => 82],
                ['name' => 'Egito',                'code' => 'EGY', 'flag_emoji' => '🇪🇬', 'strength' => 60],
                ['name' => 'Irã',                  'code' => 'IRN', 'flag_emoji' => '🇮🇷', 'strength' => 64],
                ['name' => 'Nova Zelândia',        'code' => 'NZL', 'flag_emoji' => '🇳🇿', 'strength' => 50],
            ],
            'H' => [
                ['name' => 'Espanha',              'code' => 'ESP', 'flag_emoji' => '🇪🇸', 'strength' => 90],
                ['name' => 'Cabo Verde',           'code' => 'CPV', 'flag_emoji' => '🇨🇻', 'strength' => 53],
                ['name' => 'Arábia Saudita',       'code' => 'KSA', 'flag_emoji' => '🇸🇦', 'strength' => 60],
                ['name' => 'Uruguai',              'code' => 'URU', 'flag_emoji' => '🇺🇾', 'strength' => 80],
            ],
            'I' => [
                ['name' => 'França',               'code' => 'FRA', 'flag_emoji' => '🇫🇷', 'strength' => 90],
                ['name' => 'Senegal',              'code' => 'SEN', 'flag_emoji' => '🇸🇳', 'strength' => 74],
                ['name' => 'Iraque',               'code' => 'IRQ', 'flag_emoji' => '🇮🇶', 'strength' => 55],
                ['name' => 'Noruega',              'code' => 'NOR', 'flag_emoji' => '🇳🇴', 'strength' => 73],
            ],
            'J' => [
                ['name' => 'Argentina',            'code' => 'ARG', 'flag_emoji' => '🇦🇷', 'strength' => 94],
                ['name' => 'Argélia',              'code' => 'ALG', 'flag_emoji' => '🇩🇿', 'strength' => 65],
                ['name' => 'Áustria',              'code' => 'AUT', 'flag_emoji' => '🇦🇹', 'strength' => 70],
                ['name' => 'Jordânia',             'code' => 'JOR', 'flag_emoji' => '🇯🇴', 'strength' => 52],
            ],
            'K' => [
                ['name' => 'Portugal',                          'code' => 'POR', 'flag_emoji' => '🇵🇹', 'strength' => 87],
                ['name' => 'República Democrática do Congo',   'code' => 'COD', 'flag_emoji' => '🇨🇩', 'strength' => 52],
                ['name' => 'Uzbequistão',                      'code' => 'UZB', 'flag_emoji' => '🇺🇿', 'strength' => 48],
                ['name' => 'Colômbia',                         'code' => 'COL', 'flag_emoji' => '🇨🇴', 'strength' => 78],
            ],
            'L' => [
                ['name' => 'Inglaterra',           'code' => 'ENG', 'flag_emoji' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'strength' => 87],
                ['name' => 'Croácia',              'code' => 'CRO', 'flag_emoji' => '🇭🇷', 'strength' => 80],
                ['name' => 'Gana',                 'code' => 'GHA', 'flag_emoji' => '🇬🇭', 'strength' => 65],
                ['name' => 'Panamá',               'code' => 'PAN', 'flag_emoji' => '🇵🇦', 'strength' => 50],
            ],
        ];

        foreach ($teams as $groupName => $groupTeams) {
            $group = Group::where('name', $groupName)->first();

            foreach ($groupTeams as $teamData) {
                Team::updateOrCreate(
                    ['code' => $teamData['code'], 'group_id' => $group->id],
                    ['name' => $teamData['name'], 'flag_emoji' => $teamData['flag_emoji'], 'strength' => $teamData['strength']]
                );
            }
        }
    }
}
