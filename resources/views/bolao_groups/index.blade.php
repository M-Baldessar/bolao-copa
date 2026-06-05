@extends('layouts.app')

@section('title', 'Meus Bolões')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-8 animate-in">
        <div>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Meus Grupos de Bolão</h1>
            <p class="text-slate-500 text-sm mt-1">Gerencie seus grupos ou entre em novos</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bolao.join') }}"
               class="inline-flex items-center gap-2 bg-transparent border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-semibold px-4 py-2.5 rounded-xl transition-all text-sm focus-visible:ring-2 focus-visible:ring-emerald-500">
                🔍 Entrar em grupo
            </a>
            <a href="{{ route('bolao.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold px-4 py-2.5 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                ➕ Criar novo grupo
            </a>
        </div>
    </div>

    @if($myGroups->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center animate-in stagger-1">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                <span class="text-3xl leading-none">⚽</span>
            </div>
            <h2 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Você ainda não participa de nenhum grupo</h2>
            <p class="text-slate-500 text-sm mb-6">Crie um grupo para jogar com seus amigos ou entre em um já existente.</p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('bolao.join') }}"
                   class="bg-transparent border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 text-slate-700 dark:text-slate-300 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm focus-visible:ring-2 focus-visible:ring-emerald-500">
                    Entrar em grupo
                </a>
                <a href="{{ route('bolao.create') }}"
                   class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
                    Criar grupo
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($myGroups as $i => $group)
                <a href="{{ route('bolao.show', $group) }}"
                   class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-emerald-500/30 rounded-xl p-5 block transition-all hover:shadow-md dark:hover:shadow-black/20 group animate-in focus-visible:ring-2 focus-visible:ring-emerald-500"
                   style="animation-delay: {{ $i * 0.05 }}s">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide leading-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            {{ $group->name }}
                        </h3>
                        @if($group->owner_id === auth()->id())
                            <span class="text-xs bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-semibold px-2 py-0.5 rounded-full ml-2 whitespace-nowrap flex-shrink-0">Dono</span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Código</span>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-200 tracking-[0.2em] text-sm bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700">{{ $group->code }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $group->members_count }} {{ $group->members_count === 1 ? 'participante' : 'participantes' }}</span>
                        </div>
                        <div class="text-xs text-slate-400 dark:text-slate-600">Criado por {{ $group->owner->name }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
