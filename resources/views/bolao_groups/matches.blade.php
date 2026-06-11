@extends('layouts.app')

@section('title', 'Palpites — ' . $bolaoGroup->name)

@section('content')
    <div class="flex items-start justify-between gap-4 mb-6 animate-in">
        <div>
            <a href="{{ route('bolao.show', $bolaoGroup) }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                {{ $bolaoGroup->name }}
            </a>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide mt-2">Fazer Palpites</h1>
            {{-- <p class="text-slate-500 mt-0.5 text-sm">Seus palpites neste bolão são independentes dos outros</p> --}}
        </div>

        {{-- Botões de ação em lote --}}
        <div class="flex-shrink-0 mt-1 flex items-center gap-2">

        {{-- Salvar todos --}}
        <button type="button" id="btn-save-all" onclick="saveAllPredictions()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all
                       bg-emerald-600 dark:bg-emerald-500 border border-emerald-600 dark:border-emerald-500
                       text-white
                       hover:bg-emerald-700 dark:hover:bg-emerald-400
                       focus-visible:ring-2 focus-visible:ring-emerald-500
                       disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Salvar todos
        </button>

        {{-- Preencher automaticamente --}}
        <button type="button" onclick="autoFillInputs()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all
                       bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                       text-slate-700 dark:text-slate-300
                       hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:border-emerald-300 dark:hover:border-emerald-500/40 hover:text-emerald-700 dark:hover:text-emerald-400
                       focus-visible:ring-2 focus-visible:ring-emerald-500">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span class="hidden sm:inline">Gerar resultados</span>
            <span class="sm:hidden">Auto</span>
        </button>

        </div>{{-- fim botões em lote --}}
    </div>

    {{-- Filtro por grupo / fase --}}
    <div class="mb-6 animate-in stagger-1">
        {{-- Linha sempre visível: "Todos" + botão toggle mobile --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('bolao.matches', $bolaoGroup) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ !$groupFilter && !$stageFilter ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
               {{ !$groupFilter && !$stageFilter ? 'aria-current=true' : '' }}>
                Todos
            </a>
            <button type="button" id="btn-filter-toggle"
                    onclick="toggleFilterPanel()"
                    class="md:hidden flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all
                           {{ $groupFilter || $stageFilter ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600' }}">
                <span>
                    @if($groupFilter) Grupo {{ $groupFilter }}
                    @elseif($stageFilter) {{ $knockoutStages[$stageFilter] ?? $stageFilter }}
                    @else Grupos / Fases
                    @endif
                </span>
                <svg id="filter-chevron" class="w-3 h-3 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        {{-- Painel de filtros: oculto no mobile por padrão, sempre visível no desktop --}}
        <div id="filter-panel"
             class="hidden md:flex flex-wrap gap-1.5 mt-2"
             role="group" aria-label="Filtrar partidas">
            @foreach($groups as $group)
                <a href="{{ route('bolao.matches', [$bolaoGroup, 'group' => $group->name]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                          {{ $groupFilter === $group->name ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
                   {{ $groupFilter === $group->name ? 'aria-current=true' : '' }}>
                    Grupo {{ $group->name }}
                </a>
            @endforeach
            @foreach($knockoutStages as $value => $label)
                <a href="{{ route('bolao.matches', [$bolaoGroup, 'stage' => $value]) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                          {{ $stageFilter === $value ? 'bg-amber-600 dark:bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-amber-300 dark:hover:border-amber-500/30 hover:text-amber-700 dark:hover:text-amber-300' }}"
                   {{ $stageFilter === $value ? 'aria-current=true' : '' }}>
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Lista de partidas --}}
    <div class="space-y-2" role="list" aria-label="Partidas para palpite">
        @forelse($matches as $i => $match)
            @php
                $prediction = $match->predictions->first();
                $locked = $match->match_date && now()->gte($match->match_date->copy()->subMinutes(5));
                $finished = $match->home_score !== null;
            @endphp

            <div class="bg-white dark:bg-slate-900 border {{ $prediction ? 'border-emerald-500/20' : 'border-slate-200 dark:border-slate-800' }} rounded-xl p-4 transition-all hover:border-slate-300 dark:hover:border-slate-700 animate-in"
                 style="animation-delay: {{ min($i * 0.03, 0.35) }}s"
                 role="listitem"
                 data-match-id="{{ $match->id }}"
                 data-home-strength="{{ $match->homeTeam->strength }}"
                 data-away-strength="{{ $match->awayTeam->strength }}"
                 data-available="{{ (!$locked && !$finished) ? 'true' : 'false' }}"
                 data-has-prediction="{{ $prediction ? 'true' : 'false' }}">

                {{-- Header info --}}
                <div class="flex items-center gap-2 mb-4">
                    @if($match->stage === 'group')
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                            Grupo {{ $match->group?->name }}
                        </span>
                    @else
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-2.5 py-0.5 rounded-full">
                            {{ \App\Models\GameMatch::STAGE_LABELS[$match->stage] ?? $match->stage }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 dark:text-slate-600">#{{ $match->match_number }}</span>
                    @if($match->match_date)
                        <time class="text-xs text-slate-500" datetime="{{ $match->match_date->toDateTimeString() }}">
                            {{ $match->match_date->format('d/m H:i') }}
                        </time>
                    @endif

                    <div class="ml-auto">
                        @if($finished)
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full border border-slate-200 dark:border-slate-700">Encerrado</span>
                        @elseif($locked)
                            <span class="text-xs font-semibold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 px-2.5 py-0.5 rounded-full">🔒 Bloqueado</span>
                        @elseif($prediction)
                            <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-full">✓ Salvo</span>
                        @endif
                    </div>
                </div>

                {{-- Formulário de palpite --}}
                <form method="POST"
                      action="{{ route('bolao.predict', [$bolaoGroup, $match]) }}"
                      class="flex flex-wrap items-center gap-3">
                    @csrf

                    {{-- Time da casa --}}
                    <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2.5 flex-1 min-w-0 sm:justify-end">
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate hidden sm:inline">{{ $match->homeTeam->name }}</span>
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->homeTeam->flag_emoji }}</span>
                        <span class="sm:hidden text-xs font-semibold text-slate-600 dark:text-slate-400 text-center leading-tight">{{ $match->homeTeam->code }}</span>
                    </div>

                    {{-- Inputs de placar --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        @if($locked && !$prediction)
                            <div class="w-12 h-12 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-xl font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">—</div>
                            <span class="text-slate-400 dark:text-slate-700 font-bold text-sm" aria-hidden="true">×</span>
                            <div class="w-12 h-12 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-xl font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">—</div>
                        @else
                            <label for="home_score_{{ $match->id }}" class="sr-only">Gols {{ $match->homeTeam->name }}</label>
                            <input type="number" id="home_score_{{ $match->id }}" name="home_score" min="0" max="20"
                                   value="{{ $prediction?->home_score ?? '' }}"
                                   placeholder="0"
                                   @if($locked || $finished) disabled @endif
                                   class="w-12 h-12 text-center border-2 rounded-xl text-lg font-bold text-slate-800 dark:text-slate-100 transition-all
                                          focus:outline-none
                                          {{ $prediction ? 'border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/60 hover:border-slate-400 dark:hover:border-slate-600' }}
                                          {{ $locked || $finished ? 'opacity-40 cursor-not-allowed' : '' }}">
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-sm" aria-hidden="true">×</span>
                            <label for="away_score_{{ $match->id }}" class="sr-only">Gols {{ $match->awayTeam->name }}</label>
                            <input type="number" id="away_score_{{ $match->id }}" name="away_score" min="0" max="20"
                                   value="{{ $prediction?->away_score ?? '' }}"
                                   placeholder="0"
                                   @if($locked || $finished) disabled @endif
                                   class="w-12 h-12 text-center border-2 rounded-xl text-lg font-bold text-slate-800 dark:text-slate-100 transition-all
                                          focus:outline-none
                                          {{ $prediction ? 'border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/60 hover:border-slate-400 dark:hover:border-slate-600' }}
                                          {{ $locked || $finished ? 'opacity-40 cursor-not-allowed' : '' }}">
                        @endif
                    </div>

                    {{-- Time visitante --}}
                    <div class="flex flex-col sm:flex-row items-center gap-1 sm:gap-2.5 flex-1 min-w-0">
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->awayTeam->flag_emoji }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate hidden sm:inline">{{ $match->awayTeam->name }}</span>
                        <span class="sm:hidden text-xs font-semibold text-slate-600 dark:text-slate-400 text-center leading-tight">{{ $match->awayTeam->code }}</span>
                    </div>

                    {{-- Botão salvar --}}
                    @if($locked || $finished)
                        <span class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700 cursor-not-allowed"
                              aria-disabled="true">
                            {{ $finished ? 'Encerrado' : '🔒 Bloqueado' }}
                        </span>
                    @else
                        <button type="submit"
                                class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                                       {{ $prediction
                                           ? 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-emerald-500/30 hover:text-emerald-600 dark:hover:text-emerald-400'
                                           : 'bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white shadow-md shadow-emerald-500/15' }}">
                            {{ $prediction ? 'Atualizar' : 'Salvar' }}
                        </button>
                    @endif
                </form>

                {{-- Status pós-jogo --}}
                @if($locked && !$finished)
                    <div class="mt-3 pt-3 border-t border-orange-100 dark:border-orange-500/10 text-xs text-orange-700 dark:text-orange-500/70 flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Partida em breve — palpites encerrados. Aguardando resultado oficial.
                    </div>
                @endif

                @if($finished)
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Resultado:
                            <span class="font-display font-bold text-slate-700 dark:text-slate-300 text-sm tracking-wide ml-1">
                                {{ $match->home_score }} × {{ $match->away_score }}
                            </span>
                        </span>
                        @if($prediction)
                            @php $pts = $prediction->points(); @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $pts > 0 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20' }}">
                                {{ $pts > 0 ? '+' . $pts . ' pts' : '0 pts' }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center">
                <p class="text-slate-500">Nenhuma partida encontrada.</p>
            </div>
        @endforelse
    </div>

<script>
function toggleFilterPanel() {
    var panel = document.getElementById('filter-panel');
    var chevron = document.getElementById('filter-chevron');
    var isHidden = panel.classList.contains('hidden');
    panel.classList.toggle('hidden', !isHidden);
    panel.classList.toggle('flex', isHidden);
    chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
}

@if($groupFilter || $stageFilter)
document.addEventListener('DOMContentLoaded', function() {
    var panel = document.getElementById('filter-panel');
    if (panel && window.innerWidth < 768) {
        panel.classList.remove('hidden');
        panel.classList.add('flex');
        var chevron = document.getElementById('filter-chevron');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
});
@endif

var batchUrl  = '{{ route('bolao.predict.batch', $bolaoGroup) }}';
var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

// Intercepta submits individuais — envia via AJAX ao endpoint de lote
// para não recarregar a página e preservar inputs dos outros cards
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-match-id][data-available="true"]').forEach(function (card) {
        var matchId = card.dataset.matchId;
        var form = card.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var homeInput = document.getElementById('home_score_' + matchId);
            var awayInput = document.getElementById('away_score_' + matchId);
            var btn = form.querySelector('button[type="submit"]');
            if (!homeInput || !awayInput || !btn || btn.disabled) return;

            var originalText = btn.textContent.trim();
            btn.disabled = true;
            btn.textContent = 'Salvando…';

            fetch(batchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    predictions: [{
                        match_id:   parseInt(matchId, 10),
                        home_score: parseInt(homeInput.value || '0', 10),
                        away_score: parseInt(awayInput.value || '0', 10),
                    }]
                }),
            })
            .then(function (r) {
                if (!r.ok) return r.json().then(function (err) { throw new Error(err.message || 'Erro ' + r.status); });
                return r.json();
            })
            .then(function (data) {
                btn.disabled = false;
                if (data.saved > 0) {
                    btn.textContent = '✓ Salvo';

                    card.classList.remove('border-slate-200', 'dark:border-slate-800');
                    card.classList.add('border-emerald-500/20');

                    [homeInput, awayInput].forEach(function (input) {
                        input.classList.remove(
                            'border-slate-300', 'dark:border-slate-700',
                            'hover:border-slate-400', 'dark:hover:border-slate-600',
                            'border-amber-400', 'bg-amber-50'
                        );
                        input.classList.add('border-emerald-500/40', 'bg-emerald-50', 'dark:bg-emerald-500/5');
                    });

                    setTimeout(function () { btn.textContent = 'Atualizar'; }, 1500);
                } else {
                    btn.textContent = originalText;
                    alert('Não foi possível salvar. A partida pode estar bloqueada ou já encerrada.');
                }
            })
            .catch(function (err) {
                btn.disabled = false;
                btn.textContent = originalText;
                alert('Erro ao salvar: ' + (err.message || 'Tente novamente.'));
            });
        });
    });
});

function saveAllPredictions() {
    var cards = document.querySelectorAll('[data-match-id][data-available="true"]');
    var predictions = [];

    cards.forEach(function (card) {
        var matchId   = card.dataset.matchId;
        var homeInput = document.getElementById('home_score_' + matchId);
        var awayInput = document.getElementById('away_score_' + matchId);

        if (!homeInput || !awayInput || homeInput.disabled) return;

        predictions.push({
            match_id:   parseInt(matchId, 10),
            home_score: parseInt(homeInput.value || '0', 10),
            away_score: parseInt(awayInput.value || '0', 10),
        });
    });

    if (predictions.length === 0) {
        alert('Preencha pelo menos um palpite antes de salvar.');
        return;
    }

    var btn = document.getElementById('btn-save-all');
    btn.disabled = true;
    btn.textContent = 'Salvando…';

    fetch(batchUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ predictions: predictions }),
    })
    .then(function (r) {
        if (!r.ok) {
            return r.json().then(function (err) {
                throw new Error(err.message || 'Erro ' + r.status);
            });
        }
        return r.json();
    })
    .then(function (data) {
        if (data.saved > 0) {
            window.location.reload();
        } else {
            alert('Nenhum palpite foi salvo. As partidas podem estar bloqueadas ou já encerradas.');
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Salvar todos';
        }
    })
    .catch(function (err) {
        alert('Erro ao salvar: ' + (err.message || 'Tente novamente.'));
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Salvar todos';
    });
}

function autoFillInputs() {
    var cards = document.querySelectorAll('[data-match-id][data-available="true"]');
    var filled = 0;

    cards.forEach(function (card) {
        if (card.dataset.hasPrediction === 'true') return;

        var matchId      = card.dataset.matchId;
        var homeStrength = parseInt(card.dataset.homeStrength, 10);
        var awayStrength = parseInt(card.dataset.awayStrength, 10);

        var homeInput = document.getElementById('home_score_' + matchId);
        var awayInput = document.getElementById('away_score_' + matchId);

        if (!homeInput || !awayInput || homeInput.disabled) return;

        var result = generatePrediction(homeStrength, awayStrength);

        homeInput.value = result.home;
        awayInput.value = result.away;

        // Destaque visual âmbar para sinalizar que foi preenchido automaticamente
        [homeInput, awayInput].forEach(function (el) {
            el.classList.remove('border-slate-300', 'dark:border-slate-700', 'border-emerald-500/40');
            el.classList.add('border-amber-400', 'bg-amber-50');
        });

        filled++;
    });

    if (filled === 0) {
        alert('Nenhum palpite disponível para preencher nesta visualização.');
    }
}

function generatePrediction(homeStrength, awayStrength) {
    var diff = homeStrength - awayStrength;

    // Probabilidade de vitória do mandante (com leve vantagem de campo)
    var homeWinProb = 0.52 + (diff / 100) * 0.38;
    homeWinProb = Math.min(0.88, Math.max(0.12, homeWinProb));

    // Probabilidade de empate: maior quando equilíbrio
    var drawProb = Math.max(0.08, 0.26 - Math.abs(diff) / 350);
    var awayWinProb = Math.max(0.04, 1.0 - homeWinProb - drawProb);

    // Normaliza
    var total = homeWinProb + drawProb + awayWinProb;
    homeWinProb /= total;
    drawProb    /= total;

    var rand = Math.random();
    var outcome = rand < homeWinProb ? 'home' : (rand < homeWinProb + drawProb ? 'draw' : 'away');

    // Teto de gols pela média de força
    var avg    = (homeStrength + awayStrength) / 2;
    var maxWin = avg >= 78 ? 4 : (avg >= 60 ? 3 : 2);
    var maxLoss = Math.max(0, maxWin - 1);

    var home, away;

    if (outcome === 'home') {
        home = randInt(1, maxWin);
        away = randInt(0, Math.min(home - 1, maxLoss));
    } else if (outcome === 'away') {
        away = randInt(1, maxWin);
        home = randInt(0, Math.min(away - 1, maxLoss));
    } else {
        // Empate ponderado: 0×0 = 30%, 1×1 = 50%, 2×2 = 20%
        var dr = Math.random();
        var score = dr < 0.30 ? 0 : (dr < 0.80 ? 1 : 2);
        home = away = score;
    }

    return { home: home, away: away };
}

function randInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
</script>
@endsection
