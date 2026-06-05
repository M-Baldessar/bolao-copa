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
                ['name' => 'México',          'code' => 'MEX', 'flag_emoji' => '🇲🇽'],
                ['name' => 'África do Sul', 'code' => 'RSA', 'flag_emoji' => '🇿🇦'],
                ['name' => 'Coreia do Sul',  'code' => 'KOR', 'flag_emoji' => '🇰🇷'],
                ['name' => 'República Tcheca', 'code' => 'REP', 'flag_emoji' => '🇨🇿'],
            ],
            'B' => [
                ['name' => 'Canadá',   'code' => 'CAN', 'flag_emoji' => '🇨🇦'],
                ['name' => 'Bósnia e Herzegovina', 'code' => 'BOS', 'flag_emoji' => '🇧🇦'],
                ['name' => 'Catar',    'code' => 'QAT', 'flag_emoji' => '🇶🇦'],
                ['name' => 'Suíça',    'code' => 'SUI', 'flag_emoji' => '🇨🇭'],
            ],
            'C' => [
                ['name' => 'Brasil',   'code' => 'BRA', 'flag_emoji' => '🇧🇷'],
                ['name' => 'Marrocos', 'code' => 'MAR', 'flag_emoji' => '🇲🇦'],
                ['name' => 'Haiti',    'code' => 'HTI', 'flag_emoji' => '🇭🇹'],
                ['name' => 'Escócia',  'code' => 'SCO', 'flag_emoji' => '🏴󠁧󠁢󠁳󠁣󠁴󠁿'],
            ],
            'D' => [
                ['name' => 'Estados Unidos', 'code' => 'USA', 'flag_emoji' => '🇺🇸'],
                ['name' => 'Paraguai',       'code' => 'PAR', 'flag_emoji' => '🇵🇾'],
                ['name' => 'Austrália',      'code' => 'AUS', 'flag_emoji' => '🇦🇺'],
                ['name' => 'Turquia', 'code' => 'TUR', 'flag_emoji' => '🇹🇷'],
            ],
            'E' => [
                ['name' => 'Alemanha',  'code' => 'GER', 'flag_emoji' => '🇩🇪'],
                ['name' => 'Curaçao',   'code' => 'CUW', 'flag_emoji' => '🇨🇼'],
                ['name' => 'Costa do Marfim', 'code' => 'CIV', 'flag_emoji' => '🇨🇮'],
                ['name' => 'Equador',   'code' => 'ECU', 'flag_emoji' => '🇪🇨'],
            ],
            'F' => [
                ['name' => 'Holanda',  'code' => 'NED', 'flag_emoji' => '🇳🇱'],
                ['name' => 'Japão',    'code' => 'JPN', 'flag_emoji' => '🇯🇵'],
                ['name' => 'Suécia',    'code' => 'SWE', 'flag_emoji' => '🇸🇪'],
                ['name' => 'Tunísia',  'code' => 'TUN', 'flag_emoji' => '🇹🇳'],
            ],
            'G' => [
                ['name' => 'Bélgica',   'code' => 'BEL', 'flag_emoji' => '🇧🇪'],
                ['name' => 'Egito',     'code' => 'EGY', 'flag_emoji' => '🇪🇬'],
                ['name' => 'Irã',       'code' => 'IRN', 'flag_emoji' => '🇮🇷'],
                ['name' => 'Nova Zelândia', 'code' => 'NZL', 'flag_emoji' => '🇳🇿'],
            ],
            'H' => [
                ['name' => 'Espanha',        'code' => 'ESP', 'flag_emoji' => '🇪🇸'],
                ['name' => 'Cabo Verde',      'code' => 'CPV', 'flag_emoji' => '🇨🇻'],
                ['name' => 'Arábia Saudita', 'code' => 'KSA', 'flag_emoji' => '🇸🇦'],
                ['name' => 'Uruguai',        'code' => 'URU', 'flag_emoji' => '🇺🇾'],
            ],
            'I' => [
                ['name' => 'França',   'code' => 'FRA', 'flag_emoji' => '🇫🇷'],
                ['name' => 'Senegal',  'code' => 'SEN', 'flag_emoji' => '🇸🇳'],
                ['name' => 'Iraque',   'code' => 'IRQ', 'flag_emoji' => '🇮🇶'],
                ['name' => 'Noruega',  'code' => 'NOR', 'flag_emoji' => '🇳🇴'],
            ],
            'J' => [
                ['name' => 'Argentina', 'code' => 'ARG', 'flag_emoji' => '🇦🇷'],
                ['name' => 'Argélia',  'code' => 'ALG', 'flag_emoji' => '🇩🇿'],
                ['name' => 'Áustria',  'code' => 'AUT', 'flag_emoji' => '🇦🇹'],
                ['name' => 'Jordânia',  'code' => 'JOR', 'flag_emoji' => '🇯🇴'],
            ],
            'K' => [
                ['name' => 'Portugal',       'code' => 'POR', 'flag_emoji' => '🇵🇹'],
                ['name' => 'República Democrática do Congo', 'code' => 'COD', 'flag_emoji' => '🇨🇩'],
                ['name' => 'Uzbequistão',     'code' => 'UZB', 'flag_emoji' => '🇺🇿'],
                ['name' => 'Colômbia',        'code' => 'COL', 'flag_emoji' => '🇨🇴'],
            ],
            'L' => [
                ['name' => 'Inglaterra',       'code' => 'ENG', 'flag_emoji' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿'],
                ['name' => 'Croácia',          'code' => 'CRO', 'flag_emoji' => '🇭🇷'],
                ['name' => 'Gana',              'code' => 'GHA', 'flag_emoji' => '🇬🇭'],
                ['name' => 'Panamá',            'code' => 'PAN', 'flag_emoji' => '🇵🇦'],
            ],
        ];

        foreach ($teams as $groupName => $groupTeams) {
            $group = Group::where('name', $groupName)->first();

            foreach ($groupTeams as $teamData) {
                Team::firstOrCreate(
                    ['code' => $teamData['code'], 'group_id' => $group->id],
                    ['name' => $teamData['name'], 'flag_emoji' => $teamData['flag_emoji']]
                );
            }
        }
    }
}
