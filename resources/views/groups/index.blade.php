@extends('layouts.app')

@section('title', 'Grupos')

@section('content')
    <div class="flex items-center justify-between mb-6 animate-in">
        <div>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide">Copa 2026</h1>
            <p class="text-slate-500 mt-1 text-sm" id="phase-subtitle">Fase Eliminatória · Mata-mata</p>
        </div>
        <a href="{{ route('bolao.index') }}"
           class="bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-emerald-500/15 focus-visible:ring-2 focus-visible:ring-emerald-500">
            Fazer Palpites
        </a>
    </div>

    {{-- Tabs de fase --}}
    <div class="flex gap-2 mb-6 animate-in stagger-1" role="tablist" aria-label="Selecionar fase">
        <button id="tab-knockout" role="tab" aria-selected="true" aria-controls="section-knockout"
                onclick="showPhase('knockout')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                       bg-amber-500 text-white shadow-md shadow-amber-500/20">
            🏆 Fase Eliminatória
        </button>
        <button id="tab-groups" role="tab" aria-selected="false" aria-controls="section-groups"
                onclick="showPhase('groups')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                       bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200">
            📊 Fase de Grupos
        </button>
    </div>

    {{-- Seção: Fase de Grupos (oculta por padrão) --}}
    <div id="section-groups" role="tabpanel" aria-labelledby="tab-groups" class="hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($groups as $i => $group)
            @php $hasStandings = $group->teams->sum('played') > 0; @endphp
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 rounded-xl overflow-hidden transition-all hover:shadow-md dark:hover:shadow-black/20 animate-in"
                 style="animation-delay: {{ ($i % 8) * 0.04 }}s">

                {{-- Header do grupo --}}
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white tracking-wide">Grupo {{ $group->name }}</h3>
                    {{-- <span class="text-xs text-slate-500 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md font-mono">
                        {{ $group->teams->count() }} times
                    </span> --}}
                </div>

                @if($hasStandings)
                    {{-- Tabela de classificação --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <th class="text-left px-3 py-2 text-slate-400 dark:text-slate-600 font-medium w-6">#</th>
                                    <th class="text-left px-2 py-2 text-slate-400 dark:text-slate-600 font-medium">Seleção</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Jogos">J</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Vitórias">V</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Empates">E</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Derrotas">D</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium" title="Saldo de gols">SG</th>
                                    <th class="text-center px-2 py-2 text-slate-400 dark:text-slate-600 font-medium font-bold" title="Pontos">Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->teams->sortBy('position') as $team)
                                    <tr class="border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors {{ $loop->index < 2 ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}">
                                        <td class="px-3 py-2 text-slate-500 dark:text-slate-500 font-mono font-bold">{{ $team->position }}</td>
                                        <td class="px-2 py-2">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-base leading-none">{{ $team->flag_emoji }}</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-200 truncate">{{ $team->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->played }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->won }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->drawn }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->lost }}</td>
                                        <td class="text-center px-2 py-2 text-slate-600 dark:text-slate-400">{{ $team->goals_for - $team->goals_against }}</td>
                                        <td class="text-center px-2 py-2 font-bold text-slate-900 dark:text-white">{{ $team->points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Lista simples antes dos jogos começarem --}}
                    <ul class="p-3 space-y-1">
                        @foreach($group->teams as $team)
                            <li class="flex items-center gap-3 py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors group">
                                <span class="text-xl leading-none flex-shrink-0">{{ $team->flag_emoji }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-slate-700 dark:text-slate-200 text-sm truncate group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                        {{ $team->name }}
                                    </div>
                                </div>
                                <span class="text-xs font-mono text-slate-500 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded font-bold tracking-wide">
                                    {{ $team->code }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
    </div>{{-- /section-groups --}}

    {{-- ===== SEÇÃO: FASE ELIMINATÓRIA ===== --}}
    <div id="section-knockout" role="tabpanel" aria-labelledby="tab-knockout">
    <div class="animate-in" style="animation-delay:.1s">
        <div class="mb-5">
            <h2 class="font-display font-bold text-2xl text-slate-900 dark:text-white tracking-wide">Chaveamento · Fase Eliminatória</h2>
            <p class="text-slate-500 mt-1 text-sm">16-avos · Oitavas · Quartas · Semifinal · Final</p>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 overflow-x-auto">
            {{-- Container principal: altura fixa = 8 × 64px = 512px --}}
            <div class="inline-flex items-stretch" style="height:512px; min-width:max-content">

                {{-- ===== LADO ESQUERDO ===== --}}

                {{-- R32 esquerda (8 partidas, slot h-16=64px cada) --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 8; $i++)
                        @php $match = $bracket['left']['r32'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:64px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

                {{-- Conector R32→R16 esquerda --}}
                <div class="flex flex-col" style="width:18px">
                    @for($i = 0; $i < 4; $i++)
                    <div style="height:64px;border-right:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:64px;border-right:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                    @endfor
                </div>

                {{-- R16 esquerda (4 partidas, slot h-32=128px cada) --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 4; $i++)
                        @php $match = $bracket['left']['r16'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:128px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

                {{-- Conector R16→QF esquerda --}}
                <div class="flex flex-col" style="width:18px">
                    @for($i = 0; $i < 2; $i++)
                    <div style="height:128px;border-right:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:128px;border-right:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                    @endfor
                </div>

                {{-- QF esquerda (2 partidas, slot h-64=256px cada) --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 2; $i++)
                        @php $match = $bracket['left']['qf'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:256px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

                {{-- Conector QF→SF esquerda --}}
                <div class="flex flex-col" style="width:18px">
                    <div style="height:256px;border-right:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:256px;border-right:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                </div>

                {{-- SF esquerda (1 partida, slot 512px) --}}
                <div class="flex flex-col" style="width:148px">
                    @php $match = $bracket['left']['sf'][0] ?? null; @endphp
                    <div class="flex items-center" style="height:512px">
                        @include('groups._bracket_card', ['match' => $match])
                    </div>
                </div>

                {{-- Conector SF→Final esquerda (linha horizontal no meio) --}}
                <div class="flex flex-col" style="width:18px">
                    <div style="height:256px;border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:256px"></div>
                </div>

                {{-- ===== CENTRO: FINAL + 3º LUGAR ===== --}}
                <div class="flex flex-col items-center justify-center gap-6 px-3" style="width:164px">
                    {{-- Troféu + label Final --}}
                    <div class="flex flex-col items-center gap-2 w-full">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-lg">🏆</span>
                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Final</span>
                        </div>
                        @include('groups._bracket_card', ['match' => $bracket['final']])
                    </div>
                    {{-- 3º Lugar --}}
                    <div class="flex flex-col items-center gap-2 w-full">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="text-base">🥉</span>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">3º Lugar</span>
                        </div>
                        @include('groups._bracket_card', ['match' => $bracket['thirdPlace']])
                    </div>
                </div>

                {{-- Conector Final→SF direita --}}
                <div class="flex flex-col" style="width:18px">
                    <div style="height:256px;border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:256px"></div>
                </div>

                {{-- ===== LADO DIREITO (espelhado) ===== --}}

                {{-- SF direita --}}
                <div class="flex flex-col" style="width:148px">
                    @php $match = $bracket['right']['sf'][0] ?? null; @endphp
                    <div class="flex items-center" style="height:512px">
                        @include('groups._bracket_card', ['match' => $match])
                    </div>
                </div>

                {{-- Conector SF→QF direita --}}
                <div class="flex flex-col" style="width:18px">
                    <div style="height:256px;border-left:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:256px;border-left:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                </div>

                {{-- QF direita --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 2; $i++)
                        @php $match = $bracket['right']['qf'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:256px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

                {{-- Conector QF→R16 direita --}}
                <div class="flex flex-col" style="width:18px">
                    @for($i = 0; $i < 2; $i++)
                    <div style="height:128px;border-left:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:128px;border-left:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                    @endfor
                </div>

                {{-- R16 direita --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 4; $i++)
                        @php $match = $bracket['right']['r16'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:128px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

                {{-- Conector R16→R32 direita --}}
                <div class="flex flex-col" style="width:18px">
                    @for($i = 0; $i < 4; $i++)
                    <div style="height:64px;border-left:2px solid rgba(100,116,139,.35);border-bottom:2px solid rgba(100,116,139,.35)"></div>
                    <div style="height:64px;border-left:2px solid rgba(100,116,139,.35);border-top:2px solid rgba(100,116,139,.35)"></div>
                    @endfor
                </div>

                {{-- R32 direita --}}
                <div class="flex flex-col" style="width:148px">
                    @for($i = 0; $i < 8; $i++)
                        @php $match = $bracket['right']['r32'][$i] ?? null; @endphp
                        <div class="flex items-center" style="height:64px">
                            @include('groups._bracket_card', ['match' => $match])
                        </div>
                    @endfor
                </div>

            </div>{{-- /inline-flex --}}
        </div>
    </div>
    </div>{{-- /section-knockout --}}

    <script>
    var TAB_ACTIVE   = 'bg-amber-500 text-white shadow-md shadow-amber-500/20';
    var TAB_INACTIVE = 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200';

    function showPhase(phase) {
        var isKnockout = (phase === 'knockout');

        // Sections
        document.getElementById('section-groups').classList.toggle('hidden', isKnockout);
        document.getElementById('section-knockout').classList.toggle('hidden', !isKnockout);

        // Tabs
        var tabKnockout = document.getElementById('tab-knockout');
        var tabGroups   = document.getElementById('tab-groups');

        tabKnockout.className = 'px-4 py-2 rounded-xl text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 ' + (isKnockout ? TAB_ACTIVE : TAB_INACTIVE);
        tabGroups.className   = 'px-4 py-2 rounded-xl text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 ' + (!isKnockout ? TAB_ACTIVE.replace('amber', 'emerald') : TAB_INACTIVE);

        tabKnockout.setAttribute('aria-selected', isKnockout ? 'true' : 'false');
        tabGroups.setAttribute('aria-selected',   !isKnockout ? 'true' : 'false');

        // Subtítulo
        document.getElementById('phase-subtitle').textContent = isKnockout
            ? 'Fase Eliminatória · Mata-mata'
            : '12 grupos · 48 seleções · Fase de Grupos';
    }

    // Padrão: mata-mata já está visível, fase de grupos oculta
    document.getElementById('section-groups').classList.add('hidden');
    </script>
@endsection

