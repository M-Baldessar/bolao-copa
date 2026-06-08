<?php

namespace App\Http\Controllers;

use App\Models\BolaoGroup;
use App\Models\ChampionPick;
use App\Models\GameMatch;
use App\Models\Group;
use App\Models\Prediction;
use App\Services\PredictionGenerator;
use Illuminate\Http\Request;

class BolaoGroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myGroups = BolaoGroup::where('owner_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->withCount('members')
            ->with('owner')
            ->latest()
            ->get();

        return view('bolao_groups.index', compact('myGroups'));
    }

    public function create()
    {
        return view('bolao_groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
        ]);

        $group = BolaoGroup::create([
            'name'     => $request->name,
            'code'     => BolaoGroup::generateCode(),
            'owner_id' => auth()->id(),
        ]);

        // Owner entra automaticamente como membro
        $group->members()->attach(auth()->id());

        return redirect()->route('bolao.show', $group)
            ->with('success', 'Grupo "' . $group->name . '" criado com sucesso! Código: ' . $group->code);
    }

    public function show(BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (!$isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        $bolaoGroup->load(['members', 'owner']);

        $members = $bolaoGroup->buildRanking();

        return view('bolao_groups.show', compact('bolaoGroup', 'members'));
    }

    public function matches(Request $request, BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (!$isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        $groupFilter = $request->query('group');
        $stageFilter = $request->query('stage');

        $query = GameMatch::with([
            'homeTeam',
            'awayTeam',
            'group',
            'predictions' => fn($q) => $q->where('user_id', $user->id)
                                          ->where('bolao_group_id', $bolaoGroup->id),
        ])->orderBy('match_number');

        if ($groupFilter) {
            $query->whereHas('group', fn($q) => $q->where('name', $groupFilter));
        } elseif ($stageFilter) {
            $query->where('stage', $stageFilter);
        }

        $matches        = $query->get();
        $groups         = Group::orderBy('name')->get();
        $knockoutStages = collect(GameMatch::STAGE_LABELS)->except('group');

        return view('bolao_groups.matches', compact(
            'bolaoGroup', 'matches', 'groups', 'groupFilter', 'stageFilter', 'knockoutStages'
        ));
    }

    public function storePrediction(Request $request, BolaoGroup $bolaoGroup, GameMatch $match)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (!$isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        if ($match->home_score !== null) {
            return back()->with('error', 'Não é possível alterar o palpite de uma partida com resultado já registrado.');
        }

        if ($match->match_date && now()->gte($match->match_date->copy()->subHour())) {
            return back()->with('error', 'Os palpites são bloqueados 1 hora antes do início da partida.');
        }

        $validated = $request->validate([
            'home_score' => ['nullable', 'integer', 'min:0', 'max:20'],
            'away_score' => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        Prediction::updateOrCreate(
            ['user_id' => $user->id, 'bolao_group_id' => $bolaoGroup->id, 'match_id' => $match->id],
            ['home_score' => $validated['home_score'] ?? 0, 'away_score' => $validated['away_score'] ?? 0]
        );

        return back()->with(
            'success',
            "Palpite para {$match->homeTeam->name} x {$match->awayTeam->name} salvo!"
        );
    }

    public function storeBatchPredictions(Request $request, BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (!$isMember) {
            abort(403);
        }

        $request->validate([
            'predictions'                => ['required', 'array', 'min:1'],
            'predictions.*.match_id'     => ['required', 'integer', 'exists:game_matches,id'],
            'predictions.*.home_score'   => ['nullable', 'integer', 'min:0', 'max:20'],
            'predictions.*.away_score'   => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        $saved   = 0;
        $skipped = 0;

        foreach ($request->predictions as $item) {
            $match = GameMatch::find($item['match_id']);

            // Ignora partidas com resultado ou bloqueadas
            if ($match->home_score !== null) { $skipped++; continue; }
            if ($match->match_date && now()->gte($match->match_date->copy()->subHour())) { $skipped++; continue; }

            Prediction::updateOrCreate(
                ['user_id' => $user->id, 'bolao_group_id' => $bolaoGroup->id, 'match_id' => $match->id],
                ['home_score' => (int)($item['home_score'] ?? 0), 'away_score' => (int)($item['away_score'] ?? 0)]
            );

            $saved++;
        }

        return response()->json(['saved' => $saved, 'skipped' => $skipped]);
    }

    public function autoFillPredictions(BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (!$isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        // IDs de partidas que o usuário já palpitou neste bolão
        $alreadyPredicted = Prediction::where('user_id', $user->id)
            ->where('bolao_group_id', $bolaoGroup->id)
            ->pluck('match_id')
            ->toArray();

        // Busca partidas da fase de grupos sem resultado e fora do bloqueio de 1h
        $matches = GameMatch::where('stage', 'group')
            ->whereNull('home_score')
            ->whereNotIn('id', $alreadyPredicted)
            ->where(function ($q) {
                $q->whereNull('match_date')
                  ->orWhere('match_date', '>', now()->addHour());
            })
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        $count = 0;
        foreach ($matches as $match) {
            $prediction = PredictionGenerator::generate(
                $match->homeTeam->strength,
                $match->awayTeam->strength
            );

            Prediction::create([
                'user_id'        => $user->id,
                'bolao_group_id' => $bolaoGroup->id,
                'match_id'       => $match->id,
                'home_score'     => $prediction['home_score'],
                'away_score'     => $prediction['away_score'],
            ]);

            $count++;
        }

        if ($count === 0) {
            return back()->with('success', 'Todos os palpites disponíveis já foram preenchidos!');
        }

        return back()->with('success', "{$count} palpite(s) gerado(s) automaticamente com base na força das seleções.");
    }

    public function watch(BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();
        $isMember = $bolaoGroup->members()->where('user_id', $user->id)->exists()
            || $bolaoGroup->owner_id === $user->id;

        if (! $isMember) {
            abort(403, 'Você não faz parte deste grupo.');
        }

        $bolaoGroup->load(['members', 'owner']);

        // Apenas partidas que já começaram (match_date <= agora)
        $startedMatches = GameMatch::whereNotNull('match_date')
            ->where('match_date', '<=', now())
            ->with(['homeTeam', 'awayTeam', 'group'])
            ->orderByDesc('match_date')
            ->get();

        $memberIds = $bolaoGroup->members->pluck('id');

        // Palpites de todos os membros para essas partidas neste bolão
        // indexados por [match_id][user_id] para lookup rápido na view
        $predictions = Prediction::where('bolao_group_id', $bolaoGroup->id)
            ->whereIn('match_id', $startedMatches->pluck('id'))
            ->whereIn('user_id', $memberIds)
            ->get()
            ->groupBy('match_id')
            ->map(fn($matchPreds) => $matchPreds->keyBy('user_id'));

        $members = $bolaoGroup->members->sortBy('name')->values();

        return view('bolao_groups.watch', compact('bolaoGroup', 'startedMatches', 'predictions', 'members'));
    }

    public function join()
    {
        return view('bolao_groups.join');
    }

    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $group = BolaoGroup::where('code', strtoupper(trim($request->code)))
            ->withCount('members')
            ->with('owner')
            ->first();

        return view('bolao_groups.join', compact('group'));
    }

    public function enter(Request $request)
    {
        $request->validate([
            'bolao_group_id' => 'required|exists:bolao_groups,id',
        ]);

        $group = BolaoGroup::findOrFail($request->bolao_group_id);
        $user  = auth()->user();

        $alreadyMember = $group->members()->where('user_id', $user->id)->exists();

        if ($alreadyMember) {
            return redirect()->route('bolao.show', $group)
                ->with('info', 'Você já faz parte deste grupo.');
        }

        $group->members()->attach($user->id);

        return redirect()->route('bolao.show', $group)
            ->with('success', 'Você entrou no grupo "' . $group->name . '"!');
    }

    public function leave(BolaoGroup $bolaoGroup)
    {
        $user = auth()->user();

        if ($bolaoGroup->owner_id === $user->id) {
            return back()->with('error', 'O dono do grupo não pode sair. Delete o grupo se necessário.');
        }

        $bolaoGroup->members()->detach($user->id);

        return redirect()->route('bolao.index')
            ->with('success', 'Você saiu do grupo "' . $bolaoGroup->name . '".');
    }

    public function destroy(BolaoGroup $bolaoGroup)
    {
        if ($bolaoGroup->owner_id !== auth()->id()) {
            abort(403);
        }

        $bolaoGroup->delete();

        return redirect()->route('bolao.index')
            ->with('success', 'Grupo deletado com sucesso.');
    }
}
