@extends('layouts.guest')

@section('title', 'Entrar')
@section('meta_description', 'Entre no Bolão Copa 2026 e faça seus palpites para a Copa do Mundo. Crie grupos com amigos e acompanhe o ranking em tempo real.')

@section('content')
    <div class="mb-7">
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">Entrar na sua conta</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Bem-vindo de volta!</p>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email" autofocus
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Senha --}}
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Senha</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password"
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('password') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lembrar-me + Esqueci senha --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-emerald-500 focus:ring-emerald-500/40">
                <span class="text-sm text-slate-600 dark:text-slate-400">Lembrar de mim</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                Esqueci minha senha
            </a>
        </div>

        <button type="submit"
                class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 active:bg-emerald-800 dark:active:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
            Entrar
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-500">
            Não tem conta?
            <a href="{{ route('register') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">Cadastre-se grátis</a>
        </p>
    </div>
@endsection
