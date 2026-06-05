@extends('layouts.app')

@section('title', 'Entrar em grupo')

@section('content')
<div class="max-w-lg mx-auto">

    <div class="mb-6 animate-in">
        <a href="{{ route('bolao.index') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
        <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide mt-3">Entrar em um grupo</h1>
        <p class="text-slate-500 text-sm mt-1">Insira o código do grupo compartilhado por um amigo.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 mb-5 animate-in stagger-1">
        <form action="{{ route('bolao.search') }}" method="GET">
            <div>
                <label for="code" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    Código do grupo
                </label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ request('code') }}"
                        placeholder="Ex: AB12CD"
                        maxlength="8"
                        class="flex-1 bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 uppercase tracking-[0.2em] font-mono font-bold text-slate-800 dark:text-slate-100 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-600
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                               {{ $errors->has('code') ? 'border-red-400 dark:border-red-500/50' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}"
                    >
                    <button type="submit"
                        class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold px-5 py-3 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                        Buscar
                    </button>
                </div>
                @error('code')
                    <p class="text-red-600 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>

    @isset($group)
        @if($group)
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 animate-in stagger-2">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center" aria-hidden="true">
                        <span class="text-xl leading-none">👥</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Grupo encontrado</p>
                        <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">{{ $group->name }}</h2>
                    </div>
                </div>

                <div class="space-y-2 mb-5 bg-slate-50 dark:bg-slate-800/40 rounded-xl p-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Código</span>
                        <span class="font-mono font-bold text-slate-700 dark:text-slate-200 tracking-[0.2em]">{{ $group->code }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Criado por</span>
                        <span class="text-slate-700 dark:text-slate-200 font-medium">{{ $group->owner->name }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">Participantes</span>
                        <span class="text-slate-700 dark:text-slate-200 font-medium">{{ $group->members_count }}</span>
                    </div>
                </div>

                @php
                    $alreadyMember = $group->members->contains(auth()->id());
                @endphp

                @if($alreadyMember)
                    <div class="bg-blue-50 dark:bg-blue-500/5 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 rounded-xl p-3 text-sm mb-4 flex items-center gap-2"
                         role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Você já faz parte deste grupo.
                    </div>
                    <a href="{{ route('bolao.show', $group) }}"
                       class="block text-center bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                        Ver grupo
                    </a>
                @else
                    <form action="{{ route('bolao.enter') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bolao_group_id" value="{{ $group->id }}">
                        <button type="submit"
                            class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                            Entrar no grupo
                        </button>
                    </form>
                @endif
            </div>
        @else
            <div class="bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 rounded-xl p-4 text-sm flex items-center gap-3 animate-in stagger-2"
                 role="alert">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                Nenhum grupo encontrado com esse código. Verifique e tente novamente.
            </div>
        @endif
    @endisset

</div>
@endsection
