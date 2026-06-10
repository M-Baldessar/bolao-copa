<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BolaoGroup extends Model
{
    protected $fillable = ['name', 'description', 'code', 'owner_id'];

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'bolao_group_user')
            ->withPivot('joined_at');
    }

    public function buildRanking(): Collection
    {
        $this->loadMissing('members');

        return $this->members->map(function ($member) {
            $predictions = $member->predictions()
                ->where('bolao_group_id', $this->id)
                ->with('match')
                ->get();

            $points = $predictions->sum(fn($p) => $p->points());

            $championPick = ChampionPick::where('user_id', $member->id)
                ->where('bolao_group_id', $this->id)
                ->with(['team', 'runnerUp'])
                ->first();

            if ($championPick) {
                $points += $championPick->points();
                $points += $championPick->runnerUpPoints();
            }

            return ['user' => $member, 'points' => $points, 'championPick' => $championPick];
        })->sortByDesc('points')->values();
    }
}
