@extends('layouts.app')

@section('title', 'Criar grupo')

@section('content')
<div class="max-w-lg mx-auto">

    <div class="mb-6 animate-in">
        <a href="{{ route('bolao.index') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
        <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide mt-3">Criar novo grupo</h1>
        <p class="text-slate-500 text-sm mt-1">Crie um grupo e compartilhe o código com seus amigos.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 animate-in stagger-1">
        <form action="{{ route('bolao.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                    Nome do grupo
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Ex: Família Silva, Turma do Trabalho..."
                    maxlength="60"
                    class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-slate-800 dark:text-slate-100 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                           {{ $errors->has('name') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}"
                >
                @error('name')
                    <p class="text-red-600 dark:text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-500/15 rounded-xl p-4 mb-5 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 mb-0.5">Como funciona?</p>
                    <p class="text-xs text-emerald-700/70 dark:text-emerald-500/70 leading-relaxed">
                        Após criar o grupo, você receberá um código único de 6 letras. Compartilhe esse código com seus amigos para que eles possam entrar no seu grupo.
                    </p>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/15 text-sm focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                Criar grupo
            </button>
        </form>
    </div>

</div>
@endsection
