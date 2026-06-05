@extends('layouts.app')

@section('title', 'Partidas')

@section('content')
    <div class="flex items-center justify-between mb-6 animate-in">
        <div>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Partidas</h1>
            <p class="text-slate-500 mt-1 text-sm">Para fazer palpites, acesse um dos seus grupos de bolão</p>
        </div>
        <a href="{{ route('bolao.index') }}"
           class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
            Meus Bolões
        </a>
    </div>

    {{-- Filtro por grupo / fase --}}
    <div class="flex flex-wrap gap-1.5 mb-6 animate-in stagger-1" role="group" aria-label="Filtrar partidas">
        <a href="{{ route('matches.index') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                  {{ !$groupFilter && !$stageFilter ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
           {{ !$groupFilter && !$stageFilter ? 'aria-current=true' : '' }}>
            Todos
        </a>
        @foreach($groups as $group)
            <a href="{{ route('matches.index', ['group' => $group->name]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ $groupFilter === $group->name ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
               {{ $groupFilter === $group->name ? 'aria-current=true' : '' }}>
                Grupo {{ $group->name }}
            </a>
        @endforeach
        @foreach($knockoutStages as $value => $label)
            <a href="{{ route('matches.index', ['stage' => $value]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ $stageFilter === $value ? 'bg-amber-600 dark:bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-amber-300 dark:hover:border-amber-500/30 hover:text-amber-700 dark:hover:text-amber-300' }}"
               {{ $stageFilter === $value ? 'aria-current=true' : '' }}>
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Lista de partidas --}}
    <div class="space-y-2" role="list" aria-label="Lista de partidas">
        @forelse($matches as $i => $match)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl p-4 transition-all animate-in"
                 style="animation-delay: {{ min($i * 0.03, 0.3) }}s"
                 role="listitem">
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
                        <div class="ml-auto flex items-center gap-1.5 text-xs text-slate-500">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <time datetime="{{ $match->match_date->toDateTimeString() }}">
                                {{ $match->match_date->format('d/m/Y') }}
                                <span class="text-slate-400 dark:text-slate-700" aria-hidden="true">·</span>
                                {{ $match->match_date->format('H:i') }}
                            </time>
                        </div>
                    @else
                        <span class="ml-auto text-xs text-slate-400 dark:text-slate-700 italic">Horário a definir</span>
                    @endif
                </div>

                {{-- Scoreboard --}}
                <div class="flex items-center gap-3">
                    {{-- Time da casa --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0 justify-end">
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate">{{ $match->homeTeam->name }}</span>
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->homeTeam->flag_emoji }}</span>
                    </div>

                    {{-- Placar --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        @if($match->home_score !== null)
                            <div class="w-11 h-11 flex items-center justify-center border-2 border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5 rounded-xl text-xl font-bold text-emerald-700 dark:text-emerald-400"
                                 aria-label="{{ $match->home_score }} gols">
                                {{ $match->home_score }}
                            </div>
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-sm" aria-hidden="true">×</span>
                            <div class="w-11 h-11 flex items-center justify-center border-2 border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5 rounded-xl text-xl font-bold text-emerald-700 dark:text-emerald-400"
                                 aria-label="{{ $match->away_score }} gols">
                                {{ $match->away_score }}
                            </div>
                        @else
                            <div class="flex items-center gap-1 px-4" aria-label="Partida não realizada">
                                <div class="w-11 h-11 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-base font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">–</div>
                                <span class="text-slate-400 dark:text-slate-700 font-bold text-xs mx-1" aria-hidden="true">vs</span>
                                <div class="w-11 h-11 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-base font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">–</div>
                            </div>
                        @endif
                    </div>

                    {{-- Time visitante --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->awayTeam->flag_emoji }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate">{{ $match->awayTeam->name }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                    <span class="text-3xl leading-none">⚽</span>
                </div>
                <p class="text-slate-500">Nenhuma partida encontrada para este filtro.</p>
            </div>
        @endforelse
    </div>
@endsection
