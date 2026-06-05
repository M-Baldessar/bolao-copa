@extends('layouts.guest')

@section('title', 'Criar conta')
@section('meta_description', 'Crie sua conta no Bolão Copa 2026 gratuitamente. Faça palpites nos jogos da Copa do Mundo, forme grupos com amigos e veja quem acerta mais.')

@section('content')
    <div class="mb-7">
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">Criar conta</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">É gratuito e rápido!</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
        @csrf

        {{-- Nome --}}
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nome</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   autocomplete="name" autofocus
                   placeholder="Seu nome completo"
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('name') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('name')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   placeholder="seu@email.com"
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
                   autocomplete="new-password"
                   placeholder="Mínimo 8 caracteres"
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('password') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmar senha --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Confirmar senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   autocomplete="new-password"
                   placeholder="Repita a senha"
                   class="w-full bg-white dark:bg-slate-800/60 border border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600 rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
        </div>

        <button type="submit"
                class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 active:bg-emerald-800 dark:active:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 mt-1 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
            Criar conta
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-sm text-slate-500 dark:text-slate-500">
            Já tem conta?
            <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">Entrar</a>
        </p>
    </div>
@endsection
