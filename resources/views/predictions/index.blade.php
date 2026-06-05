@extends('layouts.app')

@section('title', 'Meus Palpites')

@section('content')
    <div class="flex items-center justify-between mb-8 animate-in">
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
        {{-- Legenda --}}
        <div class="flex flex-wrap gap-2 mb-5 animate-in stagger-1" role="list" aria-label="Legenda de pontuação">
            @php
                $badges = [
                    ['color' => 'emerald', 'label' => 'Placar Exato (20 pts)'],
                    ['color' => 'blue',    'label' => 'Placar do Vencedor (15 pts)'],
                    ['color' => 'sky',     'label' => 'Vencedor + Diff. Gols (12 pts)'],
                    ['color' => 'amber',   'label' => 'Vencedor + Placar Perdedor (10 pts)'],
                    ['color' => 'indigo',  'label' => 'Vencedor Certo / Empate Certo (8 pts)'],
                    ['color' => 'red',     'label' => 'Errado (0 pts)'],
                    ['color' => 'slate',   'label' => 'Aguardando'],
                ];
            @endphp
            @foreach($badges as $badge)
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-500 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1 rounded-full" role="listitem">
                    <span class="w-2 h-2 rounded-full bg-{{ $badge['color'] }}-500" aria-hidden="true"></span>
                    {{ $badge['label'] }}
                </span>
            @endforeach
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden animate-in stagger-2">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider">Fase</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider">Partida</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider">Seu Palpite</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider hidden sm:table-cell">Resultado</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-500 uppercase tracking-wider">Bolão</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @foreach($predictions as $prediction)
                        @php $result = $prediction->result(); @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3">
                                @if($prediction->match->stage === 'group')
                                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">Grupo {{ $prediction->match->group?->name }}</span>
                                @else
                                    <span class="text-xs font-semibold text-amber-700 dark:text-amber-400">
                                        {{ \App\Models\GameMatch::STAGE_LABELS[$prediction->match->stage] ?? $prediction->match->stage }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5 flex-wrap text-slate-700 dark:text-slate-300">
                                    <span aria-hidden="true">{{ $prediction->match->homeTeam->flag_emoji }}</span>
                                    <span class="font-medium text-sm">{{ $prediction->match->homeTeam->name }}</span>
                                    <span class="text-slate-400 dark:text-slate-600 text-xs">vs</span>
                                    <span class="font-medium text-sm">{{ $prediction->match->awayTeam->name }}</span>
                                    <span aria-hidden="true">{{ $prediction->match->awayTeam->flag_emoji }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-display font-bold text-slate-800 dark:text-slate-200 text-base tracking-wide">
                                    {{ $prediction->home_score }} × {{ $prediction->away_score }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center hidden sm:table-cell">
                                @if($prediction->match->home_score !== null)
                                    <span class="font-display font-bold text-slate-600 dark:text-slate-400 text-base tracking-wide">
                                        {{ $prediction->match->home_score }} × {{ $prediction->match->away_score }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-700 text-base">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($result === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-500 border border-slate-200 dark:border-slate-700">
                                        Aguardando
                                    </span>
                                @elseif($result === 'exact')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        ✓ Placar Exato +20
                                    </span>
                                @elseif($result === 'winner_score')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                                        ✓ Placar do Vencedor +15
                                    </span>
                                @elseif($result === 'goal_diff')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-500/20">
                                        ✓ Diferença de gols do Vencedor +12
                                    </span>
                                @elseif($result === 'loser_score')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        ✓ Placar do Perdedor +10
                                    </span>
                                @elseif($result === 'correct_winner')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                                        ✓ Vencedor +8
                                    </span>
                                @elseif($result === 'draw')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20">
                                        ~ Empate +8
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20">
                                        ✗ Errado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('bolao.show', $prediction->bolaoGroup) }}"
                                   class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                                    {{ $prediction->bolaoGroup->name }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
