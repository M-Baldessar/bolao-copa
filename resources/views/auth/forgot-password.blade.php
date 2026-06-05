@extends('layouts.guest')

@section('title', 'Esqueci minha senha')
@section('meta_description', 'Recupere o acesso à sua conta no Bolão Copa 2026.')

@section('content')
    <div class="mb-7">
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">Recuperar senha</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
            Informe seu e-mail e enviaremos um link para redefinir sua senha.
        </p>
    </div>

    @if(session('status'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 flex items-start gap-3"
             role="alert" aria-live="polite">
            <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-sm text-emerald-700 dark:text-emerald-300">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email" autofocus
                   placeholder="seu@email.com"
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 active:bg-emerald-800 dark:active:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
            Enviar link de redefinição
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-500">
            Lembrou a senha?
            <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">Entrar</a>
        </p>
    </div>
@endsection
