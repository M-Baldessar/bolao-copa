@php
    $isProjected = isset($match->projected) && $match->projected;
    $hasScore    = $match && ! $isProjected && ! is_null($match->home_score) && ! is_null($match->away_score);
    $homeWins    = $hasScore && $match->home_score > $match->away_score;
    $awayWins    = $hasScore && $match->away_score > $match->home_score;

    if ($isProjected) {
        $borderCls = 'border-amber-300/60 dark:border-amber-600/40';
        $bgCls     = 'bg-amber-50/60 dark:bg-amber-900/10';
    } elseif ($match) {
        $borderCls = 'border-slate-200 dark:border-slate-700';
        $bgCls     = 'bg-white dark:bg-slate-800';
    } else {
        $borderCls = 'border-slate-200/50 dark:border-slate-700/40';
        $bgCls     = 'bg-slate-50 dark:bg-slate-800/40';
    }
@endphp
<div class="rounded-lg overflow-hidden border text-xs shadow-sm {{ $borderCls }} {{ $bgCls }}" style="width:140px">
    {{-- Time da casa --}}
    <div class="flex items-center gap-1 px-2 py-1.5 {{ $homeWins ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
        <span class="text-sm leading-none flex-shrink-0">{{ $match?->homeTeam?->flag_emoji ?? '🏳️' }}</span>
        <span class="flex-1 font-medium truncate leading-tight
            {{ $isProjected && $match->homeTeam ? 'text-amber-700 dark:text-amber-400' : 'text-slate-700 dark:text-slate-200' }}"
              style="min-width:0">
            {{ $match?->homeTeam?->name ?? 'A definir' }}
        </span>
        @if($hasScore)
            <span class="font-bold ml-1 {{ $homeWins ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500' }}">
                {{ $match->home_score }}
            </span>
        @endif
    </div>
    {{-- Time visitante --}}
    <div class="flex items-center gap-1 px-2 py-1.5 border-t border-slate-100 dark:border-slate-700/50 {{ $awayWins ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
        <span class="text-sm leading-none flex-shrink-0">{{ $match?->awayTeam?->flag_emoji ?? '🏳️' }}</span>
        <span class="flex-1 font-medium truncate leading-tight
            {{ $isProjected && $match->awayTeam ? 'text-amber-700 dark:text-amber-400' : 'text-slate-700 dark:text-slate-200' }}"
              style="min-width:0">
            {{ $match?->awayTeam?->name ?? 'A definir' }}
        </span>
        @if($hasScore)
            <span class="font-bold ml-1 {{ $awayWins ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500' }}">
                {{ $match->away_score }}
            </span>
        @endif
    </div>
</div>
