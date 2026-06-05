@extends('layouts.app')

@section('title', $bolaoGroup->name)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="mb-6 animate-in">
        <a href="{{ route('bolao.index') }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Meus grupos
        </a>
    </div>

    {{-- Header do grupo --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 mb-5 animate-in stagger-1">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">{{ $bolaoGroup->name }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-slate-500">Código de convite:</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white tracking-[0.2em] text-base bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700">{{ $bolaoGroup->code }}</span>
                </div>
            </div>
            <div class="flex gap-2 mt-1">
                @if($bolaoGroup->owner_id === auth()->id())
                    <form action="{{ route('bolao.destroy', $bolaoGroup) }}" method="POST"
                          onsubmit="return confirm('Tem certeza que deseja deletar este grupo?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs text-red-600 dark:text-red-400 border border-red-300 dark:border-red-500/20 bg-red-50 dark:bg-red-500/5 hover:bg-red-100 dark:hover:bg-red-500/10 px-3 py-1.5 rounded-lg transition-all focus-visible:ring-2 focus-visible:ring-red-500">
                            Deletar grupo
                        </button>
                    </form>
                @else
                    <form action="{{ route('bolao.leave', $bolaoGroup) }}" method="POST"
                          onsubmit="return confirm('Tem certeza que deseja sair deste grupo?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-all focus-visible:ring-2 focus-visible:ring-emerald-500">
                            Sair do grupo
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- CTA palpites --}}
        <div class="mt-5 pt-5 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-2">
            <a href="{{ route('bolao.matches', $bolaoGroup) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                <span aria-hidden="true">⚽</span> Fazer Palpites neste Bolão
            </a>
            <a href="{{ route('bolao.watch', $bolaoGroup) }}"
               class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm border border-slate-200 dark:border-slate-700 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                <span aria-hidden="true">👁</span> Acompanhar Palpites
            </a>
        </div>
    </div>

    {{-- Convite --}}
    <div class="bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-500/15 rounded-xl p-4 mb-5 flex items-start gap-3 animate-in stagger-2">
        <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 flex items-center justify-center flex-shrink-0" aria-hidden="true">
            <span class="text-lg leading-none">📨</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Compartilhe o código com seus amigos</p>
            <p class="text-sm text-amber-700/70 dark:text-amber-500/70 mt-0.5">
                Para entrar neste grupo, eles devem acessar <strong class="text-amber-700 dark:text-amber-400/80">"Entrar em grupo"</strong> e inserir o código
                <span class="font-mono font-bold text-amber-800 dark:text-amber-300 tracking-widest">{{ $bolaoGroup->code }}</span>
            </p>
        </div>
    </div>

    {{-- Ranking --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden animate-in stagger-3">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">Ranking</h2>
                <p class="text-xs text-slate-500 mt-0.5">Exato 20 · Placar venc. 15 · Diff. gols 12 · Placar perd. 10 · Vencedor/Empate 8 · Campeão 100 · Vice 50</p>
            </div>
            <div class="text-3xl" aria-hidden="true">🏆</div>
        </div>

        @if($members->isEmpty())
            <div class="p-8 text-center text-slate-500">Nenhum membro ainda.</div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60" role="list" aria-label="Ranking dos participantes">
                @foreach($members as $i => $item)
                    <div class="flex items-center gap-4 px-6 py-4 {{ $item['user']->id === auth()->id() ? 'bg-emerald-50 dark:bg-emerald-500/5' : 'hover:bg-slate-50 dark:hover:bg-slate-800/30' }} transition-colors"
                         role="listitem">
                        {{-- Posição --}}
                        <div class="w-8 text-center flex-shrink-0" aria-label="Posição {{ $i + 1 }}">
                            @if($i === 0)
                                <span class="text-2xl leading-none" role="img" aria-label="1º lugar">🥇</span>
                            @elseif($i === 1)
                                <span class="text-2xl leading-none" role="img" aria-label="2º lugar">🥈</span>
                            @elseif($i === 2)
                                <span class="text-2xl leading-none" role="img" aria-label="3º lugar">🥉</span>
                            @else
                                <span class="font-display font-bold text-lg text-slate-400 dark:text-slate-600">{{ $i + 1 }}</span>
                            @endif
                        </div>

                        {{-- Avatar + Nome --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-8 h-8 rounded-full {{ $item['user']->id === auth()->id() ? 'bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-300 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700' }} flex items-center justify-center flex-shrink-0"
                                 aria-hidden="true">
                                @if($item['user']->isAvatarEmoji())
                                    <span class="text-base leading-none">{{ $item['user']->avatarContent() }}</span>
                                @else
                                    <span class="text-xs font-bold {{ $item['user']->id === auth()->id() ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 dark:text-slate-400' }}">
                                        {{ $item['user']->avatarContent() }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-slate-700 dark:text-slate-200 text-sm truncate">
                                    {{ $item['user']->displayName() }}
                                    @if($item['user']->id === auth()->id())
                                        <span class="sr-only">(você)</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($bolaoGroup->owner_id === $item['user']->id)
                                        <span class="text-xs text-emerald-600 dark:text-emerald-500/70">dono</span>
                                    @endif
                                    @if($item['user']->id === auth()->id())
                                        <span class="text-xs text-blue-600 dark:text-blue-400/70" aria-hidden="true">você</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Escolha do campeão / vice --}}
                        @if(isset($item['championPick']) && $item['championPick'])
                            <div class="hidden sm:flex flex-col gap-0.5 text-xs text-slate-500 flex-shrink-0">
                                <div class="flex items-center gap-1" title="Campeão: {{ $item['championPick']->team->name }}">
                                    <span aria-hidden="true">{{ $item['championPick']->team->flag_emoji }}</span>
                                    @if($item['championPick']->points() > 0)
                                        <span class="font-bold text-amber-600 dark:text-amber-400">+100</span>
                                    @endif
                                </div>
                                @if($item['championPick']->runnerUp)
                                    <div class="flex items-center gap-1" title="Vice: {{ $item['championPick']->runnerUp->name }}">
                                        <span aria-hidden="true">{{ $item['championPick']->runnerUp->flag_emoji }}</span>
                                        @if($item['championPick']->runnerUpPoints() > 0)
                                            <span class="font-bold text-slate-500 dark:text-slate-400">+50</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Pontos --}}
                        <div class="text-right flex-shrink-0">
                            <span class="font-display font-bold text-xl {{ $i === 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-300' }}"
                                  aria-label="{{ $item['points'] }} pontos">
                                {{ $item['points'] }}
                            </span>
                            <span class="text-xs text-slate-400 ml-1" aria-hidden="true">pts</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
