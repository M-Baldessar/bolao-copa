@extends('layouts.app')

@section('title', 'Ranking Geral — Admin')

@section('content')

<div class="space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-display font-bold text-slate-900 dark:text-white">Ranking Geral</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Pontuação por usuário × grupo · {{ $totalMatches }} {{ $totalMatches === 1 ? 'partida encerrada' : 'partidas encerradas' }}
            </p>
        </div>
        <span class="text-xs text-slate-400 dark:text-slate-600">
            {{ $ranking->count() }} {{ $ranking->count() === 1 ? 'entrada' : 'entradas' }} no total
        </span>
    </div>

    {{-- Pódio (top 3) --}}
    @if($ranking->count() >= 1)
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        @foreach($ranking->take(3) as $i => $row)
        @php
            $medals     = ['🥇', '🥈', '🥉'];
            $bgColors   = [
                'bg-amber-50 dark:bg-amber-500/10 border-amber-300 dark:border-amber-500/30',
                'bg-slate-50 dark:bg-slate-800/60 border-slate-300 dark:border-slate-700',
                'bg-orange-50 dark:bg-orange-500/10 border-orange-300 dark:border-orange-500/20',
            ];
            $ptColors   = ['text-amber-600 dark:text-amber-400', 'text-slate-500 dark:text-slate-400', 'text-orange-600 dark:text-orange-400'];
        @endphp
        <div class="border rounded-xl p-4 flex items-center gap-3 {{ $bgColors[$i] }}">
            <span class="text-2xl leading-none flex-shrink-0">{{ $medals[$i] }}</span>
            <div class="min-w-0 flex-1">
                <div class="font-semibold text-slate-800 dark:text-slate-100 truncate text-sm">
                    {{ $row['user']->displayName() }}
                </div>
                <div class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                    {{ $row['group']->name }}
                </div>
                <div class="text-xs {{ $ptColors[$i] }} mt-0.5">
                    {{ $row['correct_count'] }} acertos · {{ $row['exact_count'] }} exatos
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <div class="text-xl font-display font-bold {{ $ptColors[$i] }}">{{ $row['points'] }}</div>
                <div class="text-xs text-slate-400">pts</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabela completa --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100 text-sm">Classificação completa</h2>
        </div>

        @if($ranking->isEmpty())
        <div class="px-5 py-10 text-center text-slate-400 dark:text-slate-600 text-sm">
            Nenhum grupo encontrado ou nenhuma partida encerrada ainda.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="px-4 py-3 text-center w-10">Pos.</th>
                        <th class="px-4 py-3 text-left">Usuário</th>
                        <th class="px-4 py-3 text-left">Grupo</th>
                        <th class="px-4 py-3 text-center">Pontos</th>
                        <th class="px-4 py-3 text-center hidden sm:table-cell">Acertos</th>
                        <th class="px-4 py-3 text-center hidden md:table-cell">Exatos</th>
                        <th class="px-4 py-3 text-center hidden md:table-cell">% Acerto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranking as $i => $row)
                    @php
                        $pos = $i + 1;
                        $pct = $row['finished_count'] > 0
                            ? round(($row['correct_count'] / $row['finished_count']) * 100)
                            : 0;

                        $rowBg = match(true) {
                            $pos === 1 => 'bg-amber-50/50 dark:bg-amber-500/5',
                            $pos === 2 => '',
                            $pos === 3 => 'bg-orange-50/50 dark:bg-orange-500/5',
                            default    => '',
                        };

                        $medal = match(true) {
                            $pos === 1 => '🥇',
                            $pos === 2 => '🥈',
                            $pos === 3 => '🥉',
                            default    => null,
                        };
                    @endphp
                    <tr class="border-b border-slate-50 dark:border-slate-800/60 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors {{ $rowBg }}">

                        {{-- Posição --}}
                        <td class="px-4 py-3 text-center">
                            @if($medal)
                                <span class="text-base leading-none">{{ $medal }}</span>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-600 tabular-nums font-medium">{{ $pos }}º</span>
                            @endif
                        </td>

                        {{-- Usuário --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center flex-shrink-0">
                                    @if($row['user']->isAvatarEmoji())
                                        <span class="text-sm leading-none">{{ $row['user']->avatarContent() }}</span>
                                    @else
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $row['user']->avatarContent() }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="font-medium text-slate-800 dark:text-slate-100 truncate block text-sm">
                                        {{ $row['user']->displayName() }}
                                    </span>
                                    <span class="text-xs text-slate-400 truncate block hidden sm:block">
                                        {{ $row['user']->email }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Grupo --}}
                        <td class="px-4 py-3">
                            <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 truncate max-w-[140px]">
                                {{ $row['group']->name }}
                            </span>
                        </td>

                        {{-- Pontos --}}
                        <td class="px-4 py-3 text-center">
                            <span class="text-base font-display font-bold tabular-nums
                                {{ $pos === 1 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-200' }}">
                                {{ $row['points'] }}
                            </span>
                        </td>

                        {{-- Acertos --}}
                        <td class="px-4 py-3 text-center tabular-nums text-xs hidden sm:table-cell">
                            @if($row['finished_count'] > 0)
                                <span class="{{ $row['correct_count'] > 0 ? 'text-emerald-600 dark:text-emerald-400 font-medium' : 'text-slate-400' }}">
                                    {{ $row['correct_count'] }}/{{ $row['finished_count'] }}
                                </span>
                            @else
                                <span class="text-slate-300 dark:text-slate-700">—</span>
                            @endif
                        </td>

                        {{-- Exatos --}}
                        <td class="px-4 py-3 text-center hidden md:table-cell">
                            @if($row['exact_count'] > 0)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300">
                                    ★ {{ $row['exact_count'] }}
                                </span>
                            @else
                                <span class="text-slate-300 dark:text-slate-700">—</span>
                            @endif
                        </td>

                        {{-- % Acerto --}}
                        <td class="px-4 py-3 text-center hidden md:table-cell">
                            @if($row['finished_count'] > 0)
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="w-14 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                        <div class="h-full rounded-full {{ $pct >= 60 ? 'bg-emerald-500' : ($pct >= 30 ? 'bg-amber-400' : 'bg-slate-300 dark:bg-slate-600') }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 tabular-nums w-7 text-right">{{ $pct }}%</span>
                                </div>
                            @else
                                <span class="text-slate-300 dark:text-slate-700">—</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Legenda --}}
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-slate-400 dark:text-slate-600">
            <span class="font-semibold text-slate-500 dark:text-slate-500">Pontuação:</span>
            <span>Placar exato <strong class="text-slate-600 dark:text-slate-400">20 pts</strong></span>
            <span>Gol do vencedor <strong class="text-slate-600 dark:text-slate-400">15 pts</strong></span>
            <span>Saldo de gols <strong class="text-slate-600 dark:text-slate-400">12 pts</strong></span>
            <span>Gol do perdedor <strong class="text-slate-600 dark:text-slate-400">10 pts</strong></span>
            <span>Empate / vencedor <strong class="text-slate-600 dark:text-slate-400">8 pts</strong></span>
        </div>
    </div>

</div>

@endsection
