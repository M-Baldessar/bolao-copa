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

        if ($match->group_id) {
            $this->recalculateGroupStandings($match->group_id);
        }

        return back()->with('success', 'Resultado da partida #' . $match->match_number . ' atualizado!');
    }

    public function clearResult(GameMatch $match)
    {
        $groupId = $match->group_id;
        $match->update(['home_score' => null, 'away_score' => null]);

        if ($groupId) {
            $this->recalculateGroupStandings($groupId);
        }

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

    private function recalculateGroupStandings(int $groupId): void
    {
        $teams = Team::where('group_id', $groupId)->get();

        $stats = [];
        foreach ($teams as $team) {
            $stats[$team->id] = [
                'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0,
                'goals_for' => 0, 'goals_against' => 0, 'points' => 0,
            ];
        }

        $matches = GameMatch::where('group_id', $groupId)
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->get();

        foreach ($matches as $m) {
            $h = $m->home_team_id;
            $a = $m->away_team_id;

            if (! isset($stats[$h], $stats[$a])) continue;

            $stats[$h]['played']++;
            $stats[$a]['played']++;
            $stats[$h]['goals_for']     += $m->home_score;
            $stats[$h]['goals_against'] += $m->away_score;
            $stats[$a]['goals_for']     += $m->away_score;
            $stats[$a]['goals_against'] += $m->home_score;

            if ($m->home_score > $m->away_score) {
                $stats[$h]['won']++;    $stats[$h]['points'] += 3;
                $stats[$a]['lost']++;
            } elseif ($m->home_score < $m->away_score) {
                $stats[$a]['won']++;    $stats[$a]['points'] += 3;
                $stats[$h]['lost']++;
            } else {
                $stats[$h]['drawn']++;  $stats[$h]['points']++;
                $stats[$a]['drawn']++;  $stats[$a]['points']++;
            }
        }

        // Ordenar: pontos → saldo de gols → gols marcados
        $sorted = collect($stats)->sortByDesc(fn($s, $id) =>
            [$s['points'], $s['goals_for'] - $s['goals_against'], $s['goals_for']]
        )->keys()->values();

        foreach ($teams as $team) {
            $s = $stats[$team->id];
            $team->update(array_merge($s, [
                'position' => $sorted->search($team->id) + 1,
            ]));
        }
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

    public function destroyUser(User $user)
    {
        if ($user->is_admin) {
            return back()->withErrors(['delete' => 'Não é possível deletar um administrador.']);
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors(['delete' => 'Você não pode deletar sua própria conta.']);
        }

        $user->delete();

        return back()->with('success', 'Usuário "' . $user->displayName() . '" removido.');
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
