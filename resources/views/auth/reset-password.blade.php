@extends('layouts.guest')

@section('title', 'Redefinir senha')
@section('meta_description', 'Redefina sua senha no Bolão Copa 2026.')

@section('content')
    <div class="mb-7">
        <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">Redefinir senha</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Crie uma nova senha para sua conta.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $email) }}"
                   autocomplete="email" autofocus
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('email') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nova senha</label>
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

        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1.5">Confirmar nova senha</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   autocomplete="new-password"
                   placeholder="Repita a nova senha"
                   class="w-full bg-white dark:bg-slate-800/60 border rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                          focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all
                          {{ $errors->has('password_confirmation') ? 'border-red-400 bg-red-50 dark:border-red-500/50 dark:bg-red-500/5' : 'border-slate-300 dark:border-slate-700/80 hover:border-slate-400 dark:hover:border-slate-600' }}">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 active:bg-emerald-800 dark:active:bg-emerald-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
            Redefinir senha
        </button>
    </form>
@endsection
