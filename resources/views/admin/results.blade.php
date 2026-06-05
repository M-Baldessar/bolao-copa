@extends('layouts.app')

@section('title', 'Admin — Resultados')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6 animate-in">
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-2.5 py-1 rounded-full uppercase tracking-wider">Admin</span>
            <div>
                <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Resultados das Partidas</h1>
                <p class="text-slate-500 text-sm mt-0.5">Insira os placares oficiais para ativar a pontuação dos palpites.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <span class="font-display font-bold text-xl text-slate-700 dark:text-slate-200">{{ $matches->whereNotNull('home_score')->count() }}</span>
                <span class="text-slate-400 text-sm">/{{ $matches->count() }}</span>
                <p class="text-xs text-slate-500 mt-0.5">com resultado</p>
            </div>
            <a href="{{ route('admin.knockout.create') }}"
               class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                + Eliminatória
            </a>
        </div>
    </div>

    {{-- Filtro por grupo --}}
    <div class="flex flex-wrap gap-1.5 mb-6 animate-in stagger-1" role="group" aria-label="Filtrar por grupo">
        <a href="{{ route('admin.results') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                  {{ !$activeGroup ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
           {{ !$activeGroup ? 'aria-current=true' : '' }}>
            Todos
        </a>
        @foreach($groups as $group)
            <a href="{{ route('admin.results', ['group' => $group->name]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ $activeGroup === $group->name ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
               {{ $activeGroup === $group->name ? 'aria-current=true' : '' }}>
                Grupo {{ $group->name }}
            </a>
        @endforeach
    </div>

    <div class="space-y-2">
        @forelse($matches as $i => $match)
            <div class="bg-white dark:bg-slate-900 border {{ !is_null($match->home_score) ? 'border-emerald-500/20' : 'border-slate-200 dark:border-slate-800' }} rounded-xl p-4 transition-all hover:border-slate-300 dark:hover:border-slate-700 animate-in"
                 style="animation-delay: {{ min($i * 0.02, 0.25) }}s">
                <form action="{{ route('admin.results.update', $match) }}" method="POST">
                    @csrf @method('PATCH')

                    <div class="flex items-center gap-3 flex-wrap">

                        {{-- Info da partida --}}
                        <div class="flex items-center gap-2 shrink-0 w-28">
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700">#{{ $match->match_number }}</span>
                            <span class="text-xs text-slate-500 font-medium truncate">
                                {{ $match->group ? 'Gr. ' . $match->group->name : (\App\Models\GameMatch::STAGE_LABELS[$match->stage] ?? $match->stage) }}
                            </span>
                        </div>

                        {{-- Time da casa --}}
                        <div class="flex items-center gap-2 flex-1 min-w-32 justify-end">
                            <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm">
                                {{ $match->homeTeam->flag_emoji }} {{ $match->homeTeam->name }}
                            </span>
                        </div>

                        {{-- Inputs de placar --}}
                        <div class="flex items-center gap-1.5 shrink-0">
                            <label for="home_score_{{ $match->id }}" class="sr-only">Gols {{ $match->homeTeam->name }}</label>
                            <input type="number" id="home_score_{{ $match->id }}" name="home_score" min="0" max="30"
                                   value="{{ $match->home_score }}"
                                   placeholder="–"
                                   class="w-12 text-center border rounded-xl py-2 text-lg font-bold text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-800/60 transition-all
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                          {{ !is_null($match->home_score) ? 'border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600' }}">
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-sm" aria-hidden="true">×</span>
                            <label for="away_score_{{ $match->id }}" class="sr-only">Gols {{ $match->awayTeam->name }}</label>
                            <input type="number" id="away_score_{{ $match->id }}" name="away_score" min="0" max="30"
                                   value="{{ $match->away_score }}"
                                   placeholder="–"
                                   class="w-12 text-center border rounded-xl py-2 text-lg font-bold text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-800/60 transition-all
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40
                                          {{ !is_null($match->away_score) ? 'border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600' }}">
                        </div>

                        {{-- Time visitante --}}
                        <div class="flex items-center gap-2 flex-1 min-w-32">
                            <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm">
                                {{ $match->awayTeam->flag_emoji }} {{ $match->awayTeam->name }}
                            </span>
                        </div>

                        {{-- Data --}}
                        <div class="shrink-0">
                            <label for="match_date_{{ $match->id }}" class="sr-only">Data e hora da partida #{{ $match->match_number }}</label>
                            <input type="datetime-local" id="match_date_{{ $match->id }}" name="match_date"
                                   value="{{ $match->match_date ? $match->match_date->format('Y-m-d\TH:i') : '' }}"
                                   class="border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/60 hover:border-slate-400 dark:hover:border-slate-600 rounded-xl px-3 py-2 text-xs text-slate-600 dark:text-slate-400
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500/40 transition-all">
                        </div>

                        {{-- Botão --}}
                        <div class="shrink-0">
                            <button type="submit"
                                class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all shadow-md shadow-emerald-500/10 focus-visible:ring-2 focus-visible:ring-emerald-500">
                                Salvar
                            </button>
                        </div>
                    </div>

                    @if(!is_null($match->home_score))
                        <div class="mt-3 pt-3 border-t border-emerald-500/10 flex items-center justify-between">
                            <span class="text-xs text-emerald-600 dark:text-emerald-500/70 font-medium flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Registrado: {{ $match->homeTeam->code }} {{ $match->home_score }}–{{ $match->away_score }} {{ $match->awayTeam->code }}
                            </span>
                            <button type="button"
                                onclick="document.getElementById('clear-{{ $match->id }}').submit()"
                                class="text-xs text-red-500 dark:text-red-400/70 hover:text-red-700 dark:hover:text-red-400 transition-colors focus-visible:ring-2 focus-visible:ring-red-500 rounded">
                                Limpar resultado
                            </button>
                        </div>
                    @endif
                </form>

                {{-- Form separado para limpar --}}
                <form id="clear-{{ $match->id }}" action="{{ route('admin.results.clear', $match) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-12 text-center text-slate-500">
                Nenhuma partida encontrada.
            </div>
        @endforelse
    </div>

</div>
@endsection
