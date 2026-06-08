@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Boas-vindas --}}
    <div class="relative overflow-hidden bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-7 mb-8 animate-in">
        {{-- Decorative glow --}}
        <div class="absolute -top-10 -right-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -bottom-8 right-20 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

        <div class="relative flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                <span class="text-3xl leading-none">⚽</span>
            </div>
            <div>
                <h1 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">
                    Bem-vindo, {{ auth()->user()->name }}!
                </h1>
                <p class="text-slate-500 mt-0.5 text-sm">FIFA World Cup 2026 — EUA, México e Canadá</p>
            </div>
        </div>
    </div>

    {{-- Topo simplificado: Total de partidas + Progresso + Ver Grupos --}}
    @php $progress = $totalMatches > 0 ? round(($predictedCount / $totalMatches) * 100) : 0; @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 animate-in stagger-1">

        {{-- Total de Partidas --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 text-center">
            <div class="font-display font-bold text-5xl text-slate-800 dark:text-slate-100 mb-1">{{ $totalMatches }}</div>
            <div class="text-slate-600 dark:text-slate-400 text-xs font-semibold uppercase tracking-widest mt-1">Total de Partidas</div>
            <div class="text-slate-400 dark:text-slate-600 text-xs mt-1">Fase de Grupos</div>
        </div>

        {{-- Progresso dos palpites --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 flex flex-col justify-center">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Progresso dos palpites</span>
                <span class="text-sm font-bold {{ $progress === 100 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progresso: {{ $progress }}%">
                <div class="h-2 rounded-full transition-all duration-700 {{ $progress === 100 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-xs mt-2 {{ $progress === 100 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-600' }}">
                {{ $progress === 100 ? 'Todos os palpites preenchidos!' : $remainingCount . ' partida(s) aguardando seu palpite' }}
            </p>
        </div>

        {{-- Ver Grupos --}}
        <a href="{{ route('groups.index') }}"
           class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-emerald-500/30 rounded-xl p-6 text-center transition-all flex flex-col items-center justify-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-500/15 transition-colors" aria-hidden="true">
                <span class="text-2xl leading-none">🏆</span>
            </div>
            <div>
                <div class="font-semibold text-slate-700 dark:text-slate-200 text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Ver Grupos</div>
                <div class="text-xs text-slate-500 mt-0.5">12 grupos · 48 seleções</div>
            </div>
        </a>

    </div>

    {{-- Ranking dos Bolões --}}
    <div class="mb-8 animate-in stagger-2">
        <h2 class="font-display font-bold text-lg text-slate-800 dark:text-slate-100 mb-4">Meus Bolões</h2>

        @if($groupRankings->isEmpty())
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-8 text-center">
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-3">Você ainda não entrou em nenhum bolão.</p>
                <a href="{{ route('bolao.join') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">
                    🔍 Entrar em um bolão
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($groupRankings as $gr)
                    @php
                        $ranking    = $gr['ranking'];
                        $myPosition = $gr['my_position'];
                        $myPoints   = $gr['my_points'];
                        $top5       = $ranking->take(5);
                        $userId     = auth()->id();
                        $userInTop5 = $top5->contains(fn($m) => $m['user']->id === $userId);
                    @endphp
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">

                        {{-- Cabeçalho do card --}}
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $gr['group']->name }}</h3>
                                @if($myPosition)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Você está em
                                        <span class="font-bold {{ $myPosition === 1 ? 'text-amber-500' : 'text-slate-700 dark:text-slate-200' }}">{{ $myPosition }}º lugar</span>
                                        com <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $myPoints }} pts</span>
                                    </p>
                                @endif
                            </div>
                            <a href="{{ route('bolao.show', $gr['group']) }}"
                               class="flex-shrink-0 text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline whitespace-nowrap">
                                Ver detalhes →
                            </a>
                        </div>

                        {{-- Ranking compacto --}}
                        <div class="divide-y divide-slate-50 dark:divide-slate-800/60">
                            @foreach($top5 as $i => $member)
                                @php $pos = $i + 1; $isMe = $member['user']->id === $userId; @endphp
                                <div class="flex items-center gap-3 px-5 py-2.5 {{ $isMe ? 'bg-emerald-50/60 dark:bg-emerald-500/5' : '' }}">
                                    <span class="w-6 text-center text-sm flex-shrink-0 {{ $isMe ? 'font-bold' : '' }}">
                                        @if($pos === 1) 🥇
                                        @elseif($pos === 2) 🥈
                                        @elseif($pos === 3) 🥉
                                        @else <span class="text-xs text-slate-400">{{ $pos }}</span>
                                        @endif
                                    </span>
                                    <div class="w-7 h-7 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                        @if($member['user']->isAvatarEmoji())
                                            <span class="text-sm leading-none">{{ $member['user']->avatarContent() }}</span>
                                        @else
                                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $member['user']->avatarContent() }}</span>
                                        @endif
                                    </div>
                                    <span class="flex-1 text-sm truncate {{ $isMe ? 'font-semibold text-emerald-700 dark:text-emerald-300' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ $member['user']->displayName() }}{{ $isMe ? ' (você)' : '' }}
                                    </span>
                                    <span class="text-sm font-bold tabular-nums {{ $pos === 1 ? 'text-amber-500' : 'text-slate-500 dark:text-slate-400' }}">
                                        {{ $member['points'] }}
                                    </span>
                                </div>
                            @endforeach

                            {{-- Usuário fora do top 5 --}}
                            @if(!$userInTop5 && $myPosition)
                                <div class="px-5 py-1 text-center text-xs text-slate-400">· · ·</div>
                                @php $myEntry = $ranking->firstWhere(fn($m) => $m['user']->id === $userId); @endphp
                                @if($myEntry)
                                <div class="flex items-center gap-3 px-5 py-2.5 bg-emerald-50/60 dark:bg-emerald-500/5">
                                    <span class="w-6 text-center text-xs text-slate-400 flex-shrink-0">{{ $myPosition }}</span>
                                    <div class="w-7 h-7 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                        @if($myEntry['user']->isAvatarEmoji())
                                            <span class="text-sm leading-none">{{ $myEntry['user']->avatarContent() }}</span>
                                        @else
                                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $myEntry['user']->avatarContent() }}</span>
                                        @endif
                                    </div>
                                    <span class="flex-1 text-sm font-semibold text-emerald-700 dark:text-emerald-300 truncate">
                                        {{ $myEntry['user']->displayName() }} (você)
                                    </span>
                                    <span class="text-sm font-bold tabular-nums text-slate-500 dark:text-slate-400">{{ $myEntry['points'] }}</span>
                                </div>
                                @endif
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Bônus de Campeão e Vice --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 mb-8 animate-in stagger-3">

        {{-- Título --}}
        <div class="flex items-center gap-2 mb-5">
            <span class="text-lg leading-none" aria-hidden="true">⭐</span>
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-slate-800 dark:text-slate-200 text-base">Bônus de Campeão e Vice</h2>
            </div>
            @if(!$champLocked)
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
                    Aberto
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2.5 py-1 rounded-full">
                    🔒 Encerrado
                </span>
            @endif
        </div>

        {{-- Cards de bônus --}}
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="flex items-center gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-500/20">
                <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">🏆</span>
                <div class="min-w-0">
                    <p class="font-bold text-amber-700 dark:text-amber-400 text-base leading-tight">100 pontos</p>
                    <p class="text-xs text-amber-600/70 dark:text-amber-500/70 mt-0.5">Acertar o campeão da Copa</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60">
                <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">🥈</span>
                <div class="min-w-0">
                    <p class="font-bold text-slate-700 dark:text-slate-300 text-base leading-tight">50 pontos</p>
                    <p class="text-xs text-slate-500 mt-0.5">Acertar o vice-campeão</p>
                </div>
            </div>
        </div>

        @if($myGroups->isEmpty())
            <p class="text-slate-500 text-sm text-center py-2">Entre em um bolão para fazer seus palpites de campeão.</p>
        @else
            {{-- Prazo --}}
            @if(!$champLocked && $champLockDate)
                <p class="text-xs text-slate-500 mb-4">
                    Prazo: até
                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $champLockDate->format('d/m/Y \à\s H:i') }}</span>
                    (início da primeira partida)
                </p>
            @endif

            <div class="space-y-2">
                @foreach($myGroups as $bolao)
                    @php $pick = $championPicks->get($bolao->id); @endphp
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-700/50 overflow-hidden">

                        {{-- Nome do bolão --}}
                        <div class="px-4 pt-3 pb-2">
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $bolao->name }}</p>
                        </div>

                        {{-- Linha: Campeão --}}
                        <div class="flex items-center gap-3 px-4 py-2 border-t border-slate-100 dark:border-slate-700/40">
                            <span class="text-base leading-none flex-shrink-0" aria-hidden="true">🏆</span>
                            <div class="flex-1 min-w-0">
                                @if($pick)
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $pick->team->flag_emoji }} {{ $pick->team->name }}</span>
                                        @if($pick->points() > 0)
                                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-1.5 py-0.5 rounded-md">+100 pts ✓</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Não escolhido</span>
                                @endif
                            </div>
                            @if(!$champLocked)
                                <button type="button"
                                        onclick="openChampionModal({{ $bolao->id }}, '{{ addslashes($bolao->name) }}', {{ $pick?->team_id ?? 'null' }}, {{ $pick?->runner_up_team_id ?? 'null' }})"
                                        class="flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-amber-500
                                               {{ $pick?->team_id ? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-500/10 hover:text-amber-700 dark:hover:text-amber-400 border border-slate-200 dark:border-slate-600' : 'bg-amber-500 hover:bg-amber-400 text-white shadow-sm shadow-amber-500/20' }}">
                                    {{ $pick?->team_id ? 'Alterar' : 'Escolher' }}
                                </button>
                            @elseif($pick?->team_id)
                                <span class="flex-shrink-0 text-xs text-slate-400 dark:text-slate-500">Bloqueado</span>
                            @endif
                        </div>

                        {{-- Linha: Vice --}}
                        <div class="flex items-center gap-3 px-4 py-2 border-t border-slate-100 dark:border-slate-700/40">
                            <span class="text-base leading-none flex-shrink-0" aria-hidden="true">🥈</span>
                            <div class="flex-1 min-w-0">
                                @if($pick?->runnerUp)
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $pick->runnerUp->flag_emoji }} {{ $pick->runnerUp->name }}</span>
                                        @if($pick->runnerUpPoints() > 0)
                                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-1.5 py-0.5 rounded-md">+50 pts ✓</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Não escolhido</span>
                                @endif
                            </div>
                            @if(!$champLocked)
                                <button type="button"
                                        onclick="openViceModal({{ $bolao->id }}, '{{ addslashes($bolao->name) }}', {{ $pick?->runner_up_team_id ?? 'null' }}, {{ $pick?->team_id ?? 'null' }})"
                                        class="flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-slate-500
                                               {{ $pick?->runner_up_team_id ? 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 border border-slate-200 dark:border-slate-600' : 'bg-slate-600 dark:bg-slate-700 hover:bg-slate-500 dark:hover:bg-slate-600 text-white shadow-sm' }}">
                                    {{ $pick?->runner_up_team_id ? 'Alterar' : 'Escolher' }}
                                </button>
                            @elseif($pick?->runner_up_team_id)
                                <span class="flex-shrink-0 text-xs text-slate-400 dark:text-slate-500">Bloqueado</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Grupos de Bolão --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 mb-8 animate-in stagger-3">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center" aria-hidden="true">
                <span class="text-base leading-none">👥</span>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800 dark:text-slate-200 text-base">Grupos de Bolão</h2>
                <p class="text-slate-500 text-xs">Jogue com seus amigos em grupos privados</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('bolao.create') }}"
               class="flex-1 bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold py-2.5 rounded-xl text-center transition-all text-sm shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                ➕ Criar novo grupo
            </a>
            <a href="{{ route('bolao.join') }}"
               class="flex-1 bg-transparent border border-slate-300 dark:border-slate-700 hover:border-emerald-500/40 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 font-semibold py-2.5 rounded-xl text-center transition-all text-sm focus-visible:ring-2 focus-visible:ring-emerald-500">
                🔍 Entrar em grupo existente
            </a>
        </div>
    </div>

    {{-- Regras do Bolão --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 mb-8 animate-in stagger-4">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center" aria-hidden="true">
                <span class="text-base leading-none">📋</span>
            </div>
            <h2 class="font-semibold text-slate-800 dark:text-slate-200 text-base">Regras do Bolão</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-3">Pontuação por palpite</p>
                <div class="space-y-2" role="list" aria-label="Tabela de pontuação">
                    @php
                        $rules = [
                            ['pts' => 20, 'label' => 'Placar exato',                  'desc' => '2×1 → palpite 2×1',           'color' => 'emerald'],
                            ['pts' => 15, 'label' => 'Placar do vencedor',             'desc' => '4×2 → palpite 4×0',           'color' => 'blue'],
                            ['pts' => 12, 'label' => 'Vencedor + diferença de gols',   'desc' => '4×2 → palpite 2×0 (diff 2)',  'color' => 'sky'],
                            ['pts' => 10, 'label' => 'Vencedor + placar do perdedor',  'desc' => '3×1 → palpite 2×1',           'color' => 'amber'],
                            ['pts' => 8,  'label' => 'Vencedor certo / Empate certo',  'desc' => 'acertou o resultado',          'color' => 'indigo'],
                            ['pts' => 0,  'label' => 'Palpite errado',                 'desc' => '',                            'color' => 'red'],
                        ];
                    @endphp
                    @foreach($rules as $rule)
                        <div class="flex items-center gap-3" role="listitem">
                            <span class="inline-flex items-center justify-center w-9 h-6 rounded-md text-xs font-bold
                                bg-{{ $rule['color'] }}-500/10 border border-{{ $rule['color'] }}-500/20 text-{{ $rule['color'] }}-600 dark:text-{{ $rule['color'] }}-400"
                                  aria-label="{{ $rule['pts'] }} pontos">
                                {{ $rule['pts'] }}
                            </span>
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $rule['label'] }}</span>
                            @if($rule['desc'])
                                <span class="text-xs text-slate-500 hidden sm:inline">{{ $rule['desc'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Prazo para palpites</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Os palpites ficam disponíveis até <span class="text-slate-800 dark:text-slate-200 font-medium">1 hora antes</span> do início de cada partida. Após esse prazo, o palpite é bloqueado automaticamente.
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Como funciona</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        Acesse um bolão e clique em <span class="text-slate-800 dark:text-slate-200 font-medium">"Fazer Palpites neste Bolão"</span> para registrar seus palpites. Cada bolão tem seu ranking independente.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Campeão --}}
    @if(!$champLocked && $myGroups->isNotEmpty())
    <div id="champion-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         role="dialog" aria-modal="true" aria-labelledby="champion-modal-title">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl shadow-black/30 w-full max-w-lg max-h-[90vh] flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
                <div>
                    <h2 id="champion-modal-title" class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">🏆 Escolher Campeão</h2>
                    <p id="champion-modal-group-name" class="text-slate-500 text-xs mt-0.5"></p>
                </div>
                <button type="button" onclick="closeChampionModal()" aria-label="Fechar"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus-visible:ring-2 focus-visible:ring-amber-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 p-6">
                <p class="text-xs text-slate-500 mb-4">Selecione a seleção que vai vencer a Copa. Se acertar, você ganha <span class="font-bold text-amber-600 dark:text-amber-400">100 pontos extras</span> no bolão.</p>
                <form method="POST" action="{{ route('champion.store') }}" id="champion-form">
                    @csrf
                    <input type="hidden" name="bolao_group_id" id="champion-bolao-id">
                    {{-- Vice atual preservado como hidden --}}
                    <input type="hidden" name="runner_up_team_id" id="champion-form-runner-up-id" value="">
                    <fieldset>
                        <legend class="sr-only">Selecione o campeão</legend>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($teams as $team)
                                <label class="cursor-pointer">
                                    <input type="radio" name="team_id" value="{{ $team->id }}" class="sr-only peer" aria-label="{{ $team->name }}">
                                    <div class="flex flex-col items-center gap-1.5 p-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                                peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-500/10
                                                hover:border-slate-300 dark:hover:border-slate-500
                                                peer-focus-visible:ring-2 peer-focus-visible:ring-amber-500
                                                transition-all select-none">
                                        <span class="text-2xl leading-none" aria-hidden="true">{{ $team->flag_emoji }}</span>
                                        <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-400 text-center leading-tight line-clamp-2">{{ $team->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    <button type="submit" class="w-full mt-5 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-amber-500/20 focus-visible:ring-2 focus-visible:ring-amber-500">
                        Confirmar Campeão
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Vice-campeão --}}
    <div id="vice-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         role="dialog" aria-modal="true" aria-labelledby="vice-modal-title">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl shadow-black/30 w-full max-w-lg max-h-[90vh] flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
                <div>
                    <h2 id="vice-modal-title" class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">🥈 Escolher Vice-campeão</h2>
                    <p id="vice-modal-group-name" class="text-slate-500 text-xs mt-0.5"></p>
                </div>
                <button type="button" onclick="closeViceModal()" aria-label="Fechar"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus-visible:ring-2 focus-visible:ring-slate-500">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 p-6">
                <p class="text-xs text-slate-500 mb-4">Selecione a seleção que vai chegar em segundo lugar. Se acertar, você ganha <span class="font-bold text-slate-700 dark:text-slate-300">50 pontos extras</span> no bolão.</p>
                <form method="POST" action="{{ route('champion.store') }}" id="vice-form">
                    @csrf
                    <input type="hidden" name="bolao_group_id" id="vice-bolao-id">
                    {{-- Campeão atual preservado como hidden --}}
                    <input type="hidden" name="team_id" id="vice-form-champion-id" value="">
                    <fieldset>
                        <legend class="sr-only">Selecione o vice-campeão</legend>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                            @foreach($teams as $team)
                                <label class="cursor-pointer">
                                    <input type="radio" name="runner_up_team_id" value="{{ $team->id }}" class="sr-only peer" aria-label="{{ $team->name }} vice">
                                    <div class="flex flex-col items-center gap-1.5 p-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                                                peer-checked:border-slate-500 peer-checked:bg-slate-100 dark:peer-checked:bg-slate-700/60
                                                hover:border-slate-300 dark:hover:border-slate-500
                                                peer-focus-visible:ring-2 peer-focus-visible:ring-slate-500
                                                transition-all select-none">
                                        <span class="text-2xl leading-none" aria-hidden="true">{{ $team->flag_emoji }}</span>
                                        <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-400 text-center leading-tight line-clamp-2">{{ $team->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    <button type="submit" class="w-full mt-5 bg-slate-700 dark:bg-slate-600 hover:bg-slate-600 dark:hover:bg-slate-500 text-white font-semibold py-3 rounded-xl transition-all shadow-lg focus-visible:ring-2 focus-visible:ring-slate-500">
                        Confirmar Vice-campeão
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openChampionModal(bolaoGroupId, bolaoGroupName, currentTeamId, currentRunnerUpTeamId) {
            document.getElementById('champion-bolao-id').value = bolaoGroupId;
            document.getElementById('champion-modal-group-name').textContent = 'Bolão: ' + bolaoGroupName;
            document.getElementById('champion-form-runner-up-id').value = currentRunnerUpTeamId || '';

            document.querySelectorAll('#champion-form input[name="team_id"]').forEach(function(r) { r.checked = false; });
            if (currentTeamId) {
                var r = document.querySelector('#champion-form input[name="team_id"][value="' + currentTeamId + '"]');
                if (r) r.checked = true;
            }
            var modal = document.getElementById('champion-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(function() {
                var first = document.querySelector('#champion-form input[name="team_id"]');
                if (first) first.focus();
            }, 50);
        }

        function closeChampionModal() {
            var modal = document.getElementById('champion-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function openViceModal(bolaoGroupId, bolaoGroupName, currentRunnerUpTeamId, currentTeamId) {
            document.getElementById('vice-bolao-id').value = bolaoGroupId;
            document.getElementById('vice-modal-group-name').textContent = 'Bolão: ' + bolaoGroupName;
            document.getElementById('vice-form-champion-id').value = currentTeamId || '';

            document.querySelectorAll('#vice-form input[name="runner_up_team_id"]').forEach(function(r) { r.checked = false; });
            if (currentRunnerUpTeamId) {
                var r = document.querySelector('#vice-form input[name="runner_up_team_id"][value="' + currentRunnerUpTeamId + '"]');
                if (r) r.checked = true;
            }
            var modal = document.getElementById('vice-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(function() {
                var first = document.querySelector('#vice-form input[name="runner_up_team_id"]');
                if (first) first.focus();
            }, 50);
        }

        function closeViceModal() {
            var modal = document.getElementById('vice-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.getElementById('champion-modal').addEventListener('click', function(e) { if (e.target === this) closeChampionModal(); });
        document.getElementById('vice-modal').addEventListener('click', function(e) { if (e.target === this) closeViceModal(); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') { closeChampionModal(); closeViceModal(); }
        });
    </script>
    @endif

    {{-- Painel Admin --}}
    @if(auth()->user()->is_admin)
    <div class="bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 rounded-xl p-6 animate-in stagger-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-lg bg-red-500/10 border border-red-500/20 flex items-center justify-center" aria-hidden="true">
                <span class="text-base leading-none">⚙</span>
            </div>
            <div>
                <h2 class="font-semibold text-red-700 dark:text-red-300 text-base">Painel Administrativo</h2>
                <p class="text-red-500/70 text-xs">Gerencie os resultados oficiais das partidas</p>
            </div>
        </div>
        <a href="{{ route('admin.results') }}"
           class="inline-flex items-center gap-2 bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-400 text-white font-semibold px-5 py-2.5 rounded-xl transition-all text-sm shadow-lg shadow-red-500/20 focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2">
            Inserir resultados dos jogos
        </a>
    </div>
    @endif
@endsection
