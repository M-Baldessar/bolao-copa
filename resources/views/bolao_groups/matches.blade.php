@extends('layouts.app')

@section('title', 'Palpites — ' . $bolaoGroup->name)

@section('content')
    <div class="flex items-center justify-between mb-6 animate-in">
        <div>
            <a href="{{ route('bolao.show', $bolaoGroup) }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                {{ $bolaoGroup->name }}
            </a>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide mt-2">Fazer Palpites</h1>
            <p class="text-slate-500 mt-0.5 text-sm">Seus palpites neste bolão são independentes dos outros</p>
        </div>
    </div>

    {{-- Filtro por grupo / fase --}}
    <div class="flex flex-wrap gap-1.5 mb-6 animate-in stagger-1" role="group" aria-label="Filtrar partidas">
        <a href="{{ route('bolao.matches', $bolaoGroup) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                  {{ !$groupFilter && !$stageFilter ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
           {{ !$groupFilter && !$stageFilter ? 'aria-current=true' : '' }}>
            Todos
        </a>
        @foreach($groups as $group)
            <a href="{{ route('bolao.matches', [$bolaoGroup, 'group' => $group->name]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ $groupFilter === $group->name ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 hover:text-slate-800 dark:hover:text-slate-200' }}"
               {{ $groupFilter === $group->name ? 'aria-current=true' : '' }}>
                Grupo {{ $group->name }}
            </a>
        @endforeach
        @foreach($knockoutStages as $value => $label)
            <a href="{{ route('bolao.matches', [$bolaoGroup, 'stage' => $value]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                      {{ $stageFilter === $value ? 'bg-amber-600 dark:bg-amber-500 text-white' : 'bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/80 hover:border-amber-300 dark:hover:border-amber-500/30 hover:text-amber-700 dark:hover:text-amber-300' }}"
               {{ $stageFilter === $value ? 'aria-current=true' : '' }}>
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Lista de partidas --}}
    <div class="space-y-2" role="list" aria-label="Partidas para palpite">
        @forelse($matches as $i => $match)
            @php
                $prediction = $match->predictions->first();
                $locked = $match->match_date && now()->gte($match->match_date->copy()->subHour());
                $finished = $match->home_score !== null;
            @endphp

            <div class="bg-white dark:bg-slate-900 border {{ $prediction ? 'border-emerald-500/20' : 'border-slate-200 dark:border-slate-800' }} rounded-xl p-4 transition-all hover:border-slate-300 dark:hover:border-slate-700 animate-in"
                 style="animation-delay: {{ min($i * 0.03, 0.35) }}s"
                 role="listitem">

                {{-- Header info --}}
                <div class="flex items-center gap-2 mb-4">
                    @if($match->stage === 'group')
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                            Grupo {{ $match->group?->name }}
                        </span>
                    @else
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-2.5 py-0.5 rounded-full">
                            {{ \App\Models\GameMatch::STAGE_LABELS[$match->stage] ?? $match->stage }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 dark:text-slate-600">#{{ $match->match_number }}</span>
                    @if($match->match_date)
                        <time class="text-xs text-slate-500" datetime="{{ $match->match_date->toDateTimeString() }}">
                            {{ $match->match_date->format('d/m H:i') }}
                        </time>
                    @endif

                    <div class="ml-auto">
                        @if($finished)
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full border border-slate-200 dark:border-slate-700">Encerrado</span>
                        @elseif($locked)
                            <span class="text-xs font-semibold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 px-2.5 py-0.5 rounded-full">🔒 Bloqueado</span>
                        @elseif($prediction)
                            <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-full">✓ Salvo</span>
                        @endif
                    </div>
                </div>

                {{-- Formulário de palpite --}}
                <form method="POST"
                      action="{{ route('bolao.predict', [$bolaoGroup, $match]) }}"
                      class="flex flex-wrap items-center gap-3">
                    @csrf

                    {{-- Time da casa --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0 justify-end">
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate">{{ $match->homeTeam->name }}</span>
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->homeTeam->flag_emoji }}</span>
                    </div>

                    {{-- Inputs de placar --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        @if($locked && !$prediction)
                            <div class="w-12 h-12 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-xl font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">—</div>
                            <span class="text-slate-400 dark:text-slate-700 font-bold text-sm" aria-hidden="true">×</span>
                            <div class="w-12 h-12 flex items-center justify-center border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 dark:text-slate-600 text-xl font-bold bg-slate-50 dark:bg-slate-800/50" aria-hidden="true">—</div>
                        @else
                            <label for="home_score_{{ $match->id }}" class="sr-only">Gols {{ $match->homeTeam->name }}</label>
                            <input type="number" id="home_score_{{ $match->id }}" name="home_score" min="0" max="20"
                                   value="{{ $prediction?->home_score ?? '' }}"
                                   placeholder="0" required
                                   @if($locked || $finished) disabled @endif
                                   class="w-12 h-12 text-center border-2 rounded-xl text-lg font-bold text-slate-800 dark:text-slate-100 transition-all
                                          focus:outline-none
                                          {{ $prediction ? 'border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/60 hover:border-slate-400 dark:hover:border-slate-600' }}
                                          {{ $locked || $finished ? 'opacity-40 cursor-not-allowed' : '' }}">
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-sm" aria-hidden="true">×</span>
                            <label for="away_score_{{ $match->id }}" class="sr-only">Gols {{ $match->awayTeam->name }}</label>
                            <input type="number" id="away_score_{{ $match->id }}" name="away_score" min="0" max="20"
                                   value="{{ $prediction?->away_score ?? '' }}"
                                   placeholder="0" required
                                   @if($locked || $finished) disabled @endif
                                   class="w-12 h-12 text-center border-2 rounded-xl text-lg font-bold text-slate-800 dark:text-slate-100 transition-all
                                          focus:outline-none
                                          {{ $prediction ? 'border-emerald-500/40 bg-emerald-50 dark:bg-emerald-500/5' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/60 hover:border-slate-400 dark:hover:border-slate-600' }}
                                          {{ $locked || $finished ? 'opacity-40 cursor-not-allowed' : '' }}">
                        @endif
                    </div>

                    {{-- Time visitante --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                        <span class="text-2xl leading-none flex-shrink-0" aria-hidden="true">{{ $match->awayTeam->flag_emoji }}</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-sm truncate">{{ $match->awayTeam->name }}</span>
                    </div>

                    {{-- Botão salvar --}}
                    @if($locked || $finished)
                        <span class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700 cursor-not-allowed"
                              aria-disabled="true">
                            {{ $finished ? 'Encerrado' : '🔒 Bloqueado' }}
                        </span>
                    @else
                        <button type="submit"
                                class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-semibold transition-all focus-visible:ring-2 focus-visible:ring-emerald-500
                                       {{ $prediction
                                           ? 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-emerald-500/30 hover:text-emerald-600 dark:hover:text-emerald-400'
                                           : 'bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white shadow-md shadow-emerald-500/15' }}">
                            {{ $prediction ? 'Atualizar' : 'Salvar' }}
                        </button>
                    @endif
                </form>

                {{-- Status pós-jogo --}}
                @if($locked && !$finished)
                    <div class="mt-3 pt-3 border-t border-orange-100 dark:border-orange-500/10 text-xs text-orange-700 dark:text-orange-500/70 flex items-center gap-1.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Partida em breve — palpites encerrados. Aguardando resultado oficial.
                    </div>
                @endif

                @if($finished)
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-500">
                            Resultado:
                            <span class="font-display font-bold text-slate-700 dark:text-slate-300 text-sm tracking-wide ml-1">
                                {{ $match->home_score }} × {{ $match->away_score }}
                            </span>
                        </span>
                        @if($prediction)
                            @php $pts = $prediction->points(); @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $pts > 0 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/20' }}">
                                {{ $pts > 0 ? '+' . $pts . ' pts' : '0 pts' }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center">
                <p class="text-slate-500">Nenhuma partida encontrada.</p>
            </div>
        @endforelse
    </div>
@endsection
