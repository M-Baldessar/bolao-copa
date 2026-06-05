@extends('layouts.app')

@section('title', 'Criar Partida Eliminatória')

@section('content')
    <div class="flex items-center justify-between mb-8 animate-in">
        <div>
            <a href="{{ route('admin.results') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar para resultados
            </a>
            <div class="flex items-center gap-3 mt-3">
                <span class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-2.5 py-1 rounded-full uppercase tracking-wider">Admin</span>
                <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Nova Partida Eliminatória</h1>
            </div>
            <p class="text-slate-500 mt-1 text-sm">Crie uma partida das fases eliminatórias da Copa 2026</p>
        </div>
    </div>

    <div class="max-w-lg animate-in stagger-1">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center" aria-hidden="true">
                    <span class="text-base leading-none">🏆</span>
                </div>
                <h2 class="font-semibold text-slate-800 dark:text-slate-200">Dados da Partida</h2>
            </div>

            <form method="POST" action="{{ route('admin.knockout.store') }}" class="p-6 space-y-5">
                @csrf

                {{-- Fase --}}
                <div>
                    <label for="stage" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Fase</label>
                    <select id="stage" name="stage"
                            class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-200 transition-all
                                   focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50
                                   {{ $errors->has('stage') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
                        <option value="" class="bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400">Selecione a fase...</option>
                        @foreach($stages as $value => $label)
                            <option value="{{ $value }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200" {{ old('stage') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('stage')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Times --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="home_team_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Time da Casa</label>
                        <select id="home_team_id" name="home_team_id"
                                class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-200 transition-all
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50
                                       {{ $errors->has('home_team_id') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
                            <option value="" class="bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400">Selecione...</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->flag_emoji }} {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('home_team_id')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="away_team_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Time Visitante</label>
                        <select id="away_team_id" name="away_team_id"
                                class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-200 transition-all
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50
                                       {{ $errors->has('away_team_id') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
                            <option value="" class="bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400">Selecione...</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" class="bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                                    {{ $team->flag_emoji }} {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('away_team_id')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Data --}}
                <div>
                    <label for="match_date" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Data e Hora
                        <span class="text-slate-400 dark:text-slate-600 font-normal normal-case ml-1">(opcional)</span>
                    </label>
                    <input type="datetime-local" id="match_date" name="match_date"
                           value="{{ old('match_date') }}"
                           class="w-full bg-white dark:bg-slate-800/60 border border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600 rounded-xl px-4 py-3 text-sm text-slate-700 dark:text-slate-300 transition-all
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50">
                </div>

                <button type="submit"
                        class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/15 text-sm focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                    Criar Partida
                </button>
            </form>
        </div>
    </div>
@endsection
