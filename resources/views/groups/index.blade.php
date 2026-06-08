@extends('layouts.app')

@section('title', 'Grupos')

@section('content')
    <div class="flex items-center justify-between mb-8 animate-in">
        <div>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Grupos da Copa 2026</h1>
            <p class="text-slate-500 mt-1 text-sm">12 grupos · 48 seleções · Fase de Grupos</p>
        </div>
        <a href="{{ route('matches.index') }}"
           class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
            Fazer Palpites
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($groups as $i => $group)
            @php $hasStandings = $group->teams->sum('played') > 0; @endphp
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl overflow-hidden transition-all hover:shadow-md dark:hover:shadow-black/20 animate-in"
                 style="animation-delay: {{ ($i % 8) * 0.04 }}s">

                {{-- Header do grupo --}}
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white tracking-wide">Grupo {{ $group->name }}</h3>
                    <span class="text-xs text-slate-500 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-mono">
                        {{ $group->teams->count() }} times
                    </span>
                </div>

                @if($hasStandings)
                    {{-- Tabela de classificação --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <th class="text-left px-3 py-2 text-slate-400 dark:text-slate-600 font-medium w-6">#</th>
                                    <th class="text-left px-2 py-2 text-slate-400 dark:text-slate-600 font-medium">Seleção</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Jogos">J</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Vitórias">V</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Empates">E</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Derrotas">D</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Saldo de gols">SG</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium font-bold" title="Pontos">Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->teams->sortBy('position') as $team)
                                    <tr class="border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors {{ $loop->index < 2 ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}">
                                        <td class="px-3 py-2 text-slate-500 dark:text-slate-500 font-mono font-bold">{{ $team->position }}</td>
                                        <td class="px-2 py-2">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-base leading-none">{{ $team->flag_emoji }}</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-200 truncate">{{ $team->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->played }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->won }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->drawn }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->lost }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->goals_for - $team->goals_against }}</td>
                                        <td class="text-center px-2 py-2 font-bold text-slate-900 dark:text-white">{{ $team->points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Lista simples antes dos jogos começarem --}}
                    <ul class="p-3 space-y-1">
                        @foreach($group->teams as $team)
                            <li class="flex items-center gap-3 py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                                <span class="text-xl leading-none flex-shrink-0">{{ $team->flag_emoji }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-slate-700 dark:text-slate-200 text-sm truncate group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                        {{ $team->name }}
                                    </div>
                                </div>
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded font-bold tracking-wide">
                                    {{ $team->code }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
@endsection
