@extends('layouts.app')

@section('title', 'Acompanhar Palpites — ' . $bolaoGroup->name)

@section('content')
    <div class="flex items-center justify-between mb-6 animate-in">
        <div>
            <a href="{{ route('bolao.show', $bolaoGroup) }}" class="inline-flex items-center gap-1.5 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 text-sm transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                {{ $bolaoGroup->name }}
            </a>
            <h1 class="font-display font-bold text-3xl text-slate-900 dark:text-white tracking-wide mt-2">Acompanhar Palpites</h1>
            <p class="text-slate-500 mt-0.5 text-sm">Palpites dos participantes — visíveis após o início de cada partida</p>
        </div>
    </div>

    @if($startedMatches->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-16 text-center animate-in">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                <span class="text-3xl leading-none">⏳</span>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-base mb-1">Nenhuma partida iniciada ainda.</p>
            <p class="text-slate-500 text-sm">Os palpites ficam visíveis a partir do momento em que cada jogo começa.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($startedMatches as $i => $match)
                @php
                    $finished = $match->home_score !== null;
                    $matchPreds = $predictions->get($match->id, collect());
                @endphp

                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden animate-in"
                     style="animation-delay: {{ min($i * 0.04, 0.4) }}s">

                    {{-- Header da partida --}}
                    <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center gap-3">
                        {{-- Badge fase --}}
                        @if($match->stage === 'group')
                            <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 rounded-full">
                                Grupo {{ $match->group?->name }}
                            </span>
                        @else
                            <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-2.5 py-0.5 rounded-full">
                                {{ \App\Models\GameMatch::STAGE_LABELS[$match->stage] ?? $match->stage }}
                            </span>
                        @endif

                        {{-- Times e placar --}}
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <span class="text-lg leading-none" aria-hidden="true">{{ $match->homeTeam->flag_emoji }}</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm truncate">{{ $match->homeTeam->name }}</span>

                            @if($finished)
                                <span class="font-display font-bold text-slate-900 dark:text-white text-base tracking-wider mx-1">
                                    {{ $match->home_score }} × {{ $match->away_score }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-600 text-xs mx-1 font-bold">vs</span>
                            @endif

                            <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm truncate">{{ $match->awayTeam->name }}</span>
                            <span class="text-lg leading-none" aria-hidden="true">{{ $match->awayTeam->flag_emoji }}</span>
                        </div>

                        {{-- Data e status --}}
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($match->match_date)
                                <time class="text-xs text-slate-400" datetime="{{ $match->match_date->toDateTimeString() }}">
                                    {{ $match->match_date->format('d/m H:i') }}
                                </time>
                            @endif
                            @if($finished)
                                <span class="text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-full">Encerrado</span>
                            @else
                                <span class="text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2.5 py-0.5 rounded-full">Em andamento</span>
                            @endif
                        </div>
                    </div>

                    {{-- Palpites dos membros --}}
                    <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @foreach($members as $member)
                            @php $pred = $matchPreds->get($member->id); @endphp
                            <div class="flex items-center gap-3 px-5 py-3 {{ $member->id === auth()->id() ? 'bg-emerald-50/60 dark:bg-emerald-500/5' : '' }}">

                                {{-- Avatar --}}
                                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden bg-slate-200 dark:bg-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400"
                                     aria-hidden="true">
                                    @if($member->avatar_url)
                                        <img src="{{ $member->avatar_url }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        {{ mb_strtoupper(mb_substr($member->displayName(), 0, 1)) }}
                                    @endif
                                </div>

                                {{-- Nome --}}
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 flex-1 min-w-0 truncate">
                                    {{ $member->displayName() }}
                                    @if($member->id === auth()->id())
                                        <span class="text-xs text-blue-500 dark:text-blue-400 ml-1">você</span>
                                    @endif
                                    @if($member->id === $bolaoGroup->owner_id)
                                        <span class="text-xs text-emerald-500 dark:text-emerald-400 ml-1">dono</span>
                                    @endif
                                </span>

                                {{-- Palpite --}}
                                @if($pred)
                                    <span class="font-display font-bold text-slate-800 dark:text-slate-200 text-base tracking-wider flex-shrink-0">
                                        {{ $pred->home_score }} × {{ $pred->away_score }}
                                    </span>

                                    {{-- Pontuação (só exibe se a partida terminou) --}}
                                    @if($finished)
                                        @php $pts = $pred->points(); $res = $pred->result(); @endphp
                                        @php
                                            $badgeClass = match($res) {
                                                'exact'          => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                                                'winner_score'   => 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
                                                'goal_diff'      => 'bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400 border-sky-200 dark:border-sky-500/20',
                                                'loser_score'    => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                                'correct_winner' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20',
                                                'draw'           => 'bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-500/20',
                                                default          => 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border flex-shrink-0 {{ $badgeClass }}">
                                            {{ $pts > 0 ? '+' . $pts : '0' }} pts
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-600 italic flex-shrink-0">Sem palpite</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
