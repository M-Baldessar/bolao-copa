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
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl overflow-hidden transition-all hover:shadow-md dark:hover:shadow-black/20 animate-in"
                 style="animation-delay: {{ ($i % 8) * 0.04 }}s">
                {{-- Header do grupo --}}
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white tracking-wide">Grupo {{ $group->name }}</h3>
                    <span class="text-xs text-slate-500 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-mono">
                        {{ $group->teams->count() }} times
                    </span>
                </div>
                {{-- Times --}}
                <ul class="p-3 space-y-1">
                    @foreach($group->teams as $j => $team)
                        <li class="flex items-center gap-3 py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                            <span class="text-xl leading-none flex-shrink-0" aria-hidden="true">{{ $team->flag_emoji }}</span>
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
            </div>
        @endforeach
    </div>
@endsection
