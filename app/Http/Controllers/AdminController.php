<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Group;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function results(Request $request)
    {
        $groups = Group::orderBy('name')->get();
        $activeGroup = $request->group;

        $matches = GameMatch::with(['homeTeam', 'awayTeam', 'group'])
            ->when($activeGroup, fn($q) => $q->whereHas('group', fn($q) => $q->where('name', $activeGroup)))
            ->orderBy('match_number')
            ->get();

        return view('admin.results', compact('matches', 'groups', 'activeGroup'));
    }

    public function updateResult(Request $request, GameMatch $match)
    {
        $data = $request->validate([
            'home_score' => 'nullable|integer|min:0|max:30',
            'away_score' => 'nullable|integer|min:0|max:30',
            'match_date' => 'nullable|date',
        ]);

        // Se um dos placares foi preenchido, o outro também deve ser
        if (isset($data['home_score']) xor isset($data['away_score'])) {
            return back()->withErrors(['score' => 'Informe o placar dos dois times.'])->withInput();
        }

        $match->update($data);

        return back()->with('success', 'Resultado da partida #' . $match->match_number . ' atualizado!');
    }

    public function clearResult(GameMatch $match)
    {
        $match->update(['home_score' => null, 'away_score' => null]);

        return back()->with('success', 'Resultado da partida #' . $match->match_number . ' removido.');
    }

    public function users()
    {
        $totalGroupMatches = GameMatch::where('stage', 'group')->count();

        $users = User::addSelect([
            'group_predictions_count' => DB::table('predictions')
                ->selectRaw('COUNT(DISTINCT predictions.match_id)')
                ->join('game_matches', 'predictions.match_id', '=', 'game_matches.id')
                ->where('game_matches.stage', 'group')
                ->whereColumn('predictions.user_id', 'users.id'),
        ])->orderByDesc('created_at')->paginate(20);

        return view('admin.users', array_merge($this->buildUserStats(), compact('users', 'totalGroupMatches')));
    }

    public function userStats()
    {
        return response()->json($this->buildUserStats());
    }

    private function buildUserStats(): array
    {
        $now = now();

        return [
            'total'   => User::count(),
            'last_24h' => User::where('created_at', '>=', $now->copy()->subHours(24))->count(),
            'last_7d'  => User::where('created_at', '>=', $now->copy()->subDays(7))->count(),
            'last_30d' => User::where('created_at', '>=', $now->copy()->subDays(30))->count(),
        ];
    }

    public function createKnockout()
    {
        $teams = Team::with('group')->orderBy('name')->get();

        return view('admin.knockout', [
            'teams'  => $teams,
            'stages' => collect(GameMatch::STAGE_LABELS)->except('group'),
        ]);
    }

    public function storeKnockout(Request $request)
    {
        $data = $request->validate([
            'stage'        => ['required', 'in:round_of_32,round_of_16,quarterfinal,semifinal,third_place,final'],
            'home_team_id' => ['required', 'exists:teams,id'],
            'away_team_id' => ['required', 'exists:teams,id', 'different:home_team_id'],
            'match_date'   => ['nullable', 'date'],
        ]);

        $lastNumber = GameMatch::max('match_number') ?? 0;

        GameMatch::create([
            'group_id'     => null,
            'stage'        => $data['stage'],
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'match_number' => $lastNumber + 1,
            'match_date'   => $data['match_date'] ?? null,
        ]);

        return redirect()->route('admin.results')
            ->with('success', 'Partida eliminatória criada com sucesso!');
    }
}
