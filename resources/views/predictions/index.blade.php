@extends('layouts.app')

@section('title', 'Meus Palpites')

@section('content')
    <div class="flex items-center justify-between mb-6 animate-in">
        <div>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Meus Palpites</h1>
            <p class="text-slate-500 mt-1 text-sm">{{ $predictions->count() }} palpite(s) enviado(s)</p>
        </div>
        <a href="{{ route('bolao.index') }}"
           class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
            + Adicionar Palpites
        </a>
    </div>

    @if($predictions->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center animate-in">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                <span class="text-3xl leading-none">⚽</span>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-base mb-2">Você ainda não fez nenhum palpite.</p>
            <p class="text-slate-500 text-sm mb-6">Entre em um bolão e faça suas previsões para os jogos!</p>
            <a href="{{ route('bolao.index') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-6 py-2.5 rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/15 text-sm focus-visible:ring-2 focus-visible:ring-emerald-500">
                Fazer Palpites
            </a>
        </div>
    @else
        <div class="space-y-2" role="list" aria-label="Meus palpites">
            @foreach($predictions as $i => $prediction)
                @php
                    $match  = $prediction->match;
                    $result = $prediction->result();
                    $statusMap = [
                        'pending'        => ['color' => 'slate',  'label' => 'Aguardando'],
                        'exact'          => ['color' => 'emerald','label' => 'Placar Exato +20'],
                        'winner_score'   => ['color' => 'blue',   'label' => 'Placar Vencedor +15'],
                        'goal_diff'      => ['color' => 'sky',    'label' => 'Diff. de Gols +12'],
                        'loser_score'    => ['color' => 'amber',  'label' => 'Placar Perdedor +10'],
                        'correct_winner' => ['color' => 'indigo', 'label' => 'Vencedor Certo +8'],
                        'draw'           => ['color' => 'purple', 'label' => 'Empate Certo +8'],
                        'wrong'          => ['color' => 'red',    'label' => 'Errado'],
                    ];
                    $s = $statusMap[$result] ?? $statusMap['pending'];
                @endphp

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl p-4 transition-all animate-in"
                     style="animation-delay: {{ min($i * 0.03, 0.3) }}s"
                     role="listitem">

                    {{-- Header --}}
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

                        <div class="ml-auto flex items-center gap-2">
                            <a href="{{ route('bolao.show', $prediction->bolaoGroup) }}"
                               class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors hidden sm:inline focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                                {{ $prediction->bolaoGroup->name }}
                            </a>
                            @if($match->match_date)
                                <span class="text-xs text-slate-400 dark:text-slate-600 hidden sm:inline" aria-hidden="true">·</span>
                                <div class="flex items-center gap-1 text-xs text-slate-500">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <time datetime="{{ $match->match_date->toDateTimeString() }}">
                                        {{ $match->match_date->format('d/m/Y') }}
                                        <span class="text-slate-400 dark:text-slate-700" aria-hidden="true">·</span>
                                        {{ $match->match_date->format('H:i') }}
                                    </time>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Scoreboard --}}
                    <div class="flex items-center gap-3">
                        {{-- Time da casa --}}
                        <div class="flex items-center gap-2.5 flex-1 min-w-0 justify-end">
                            <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate hidden sm:inline">{{ $match->homeTeam->name }}</span>
                            <div class="flex flex-col items-center flex-shrink-0">
                                <span class="text-2xl leading-none" aria-hidden="true">{{ $match->homeTeam->flag_emoji }}</span>
                                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 tracking-wide sm:hidden">{{ $match->homeTeam->code }}</span>
                            </div>
                        </div>

                        {{-- Palpite --}}
                        <div class="flex flex-col items-center gap-1 flex-shrink-0">
                            <div class="flex items-center gap-1.5">
                                <div class="w-11 h-11 flex items-center justify-center border-2 border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-xl font-bold text-slate-700 dark:text-slate-300">
                                    {{ $prediction->home_score }}
                                </div>
                                <span class="text-slate-400 dark:text-slate-600 font-bold text-sm" aria-hidden="true">×</span>
                                <div class="w-11 h-11 flex items-center justify-center border-2 border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-xl font-bold text-slate-700 dark:text-slate-300">
                                    {{ $prediction->away_score }}
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-600 font-medium uppercase tracking-wide">Palpite</span>
                        </div>

                        {{-- Time visitante --}}
                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                            <div class="flex flex-col items-center flex-shrink-0">
                                <span class="text-2xl leading-none" aria-hidden="true">{{ $match->awayTeam->flag_emoji }}</span>
                                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 mt-0.5 tracking-wide sm:hidden">{{ $match->awayTeam->code }}</span>
                            </div>
                            <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate hidden sm:inline">{{ $match->awayTeam->name }}</span>
                        </div>
                    </div>

                    {{-- Rodapé: resultado real + status --}}
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                        {{-- Resultado real --}}
                        <div class="text-xs text-slate-500 dark:text-slate-500">
                            @if($match->home_score !== null)
                                Resultado:
                                <span class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ $match->home_score }} × {{ $match->away_score }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-600 italic">Aguardando resultado</span>
                            @endif
                        </div>

                        {{-- Status badge --}}
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-500/10
                            text-{{ $s['color'] }}-700 dark:text-{{ $s['color'] }}-400
                            border border-{{ $s['color'] }}-200 dark:border-{{ $s['color'] }}-500/20">
                            {{ $s['label'] }}
                        </span>
                    </div>

                    {{-- Nome do bolão (mobile) --}}
                    <div class="mt-2 sm:hidden">
                        <a href="{{ route('bolao.show', $prediction->bolaoGroup) }}"
                           class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                            {{ $prediction->bolaoGroup->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
