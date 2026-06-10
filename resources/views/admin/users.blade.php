@extends('layouts.app')

@section('title', 'Usuários — Admin')

@section('content')

<div class="space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-slate-900 dark:text-white">Usuários</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Acompanhe o crescimento dos cadastros</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            Ao vivo · <span id="last-update">agora</span>
        </div>
    </div>

    {{-- Cards de métricas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex flex-col gap-1">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Total</span>
            <span id="stat-total" class="text-3xl font-display font-bold text-slate-900 dark:text-white">{{ $total }}</span>
            <span class="text-xs text-slate-400">cadastros</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex flex-col gap-1">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">24h</span>
            <span id="stat-24h" class="text-3xl font-display font-bold text-slate-900 dark:text-white">{{ $last_24h }}</span>
            <span class="text-xs text-slate-400">últimas 24 horas</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex flex-col gap-1">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">7 dias</span>
            <span id="stat-7d" class="text-3xl font-display font-bold text-slate-900 dark:text-white">{{ $last_7d }}</span>
            <span class="text-xs text-slate-400">últimos 7 dias</span>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-4 flex flex-col gap-1">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">30 dias</span>
            <span id="stat-30d" class="text-3xl font-display font-bold text-slate-900 dark:text-white">{{ $last_30d }}</span>
            <span class="text-xs text-slate-400">últimos 30 dias</span>
        </div>

    </div>

    {{-- Tabela paginada --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800 dark:text-slate-100 text-sm">
                Todos os usuários
                <span class="ml-2 text-xs font-normal text-slate-400">{{ $users->total() }} no total</span>
            </h2>
            <span class="text-xs text-slate-400">
                Página {{ $users->currentPage() }} de {{ $users->lastPage() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3 text-left">#</th>
                        <th class="px-5 py-3 text-left">Usuário</th>
                        <th class="px-5 py-3 text-left hidden sm:table-cell">E-mail</th>
                        <th class="px-5 py-3 text-center">Palpites</th>
                        <th class="px-5 py-3 text-right hidden sm:table-cell">Cadastro</th>
                        <th class="px-5 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-slate-50 dark:border-slate-800/60 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-5 py-3 text-slate-400 text-xs tabular-nums">{{ $user->id }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center flex-shrink-0">
                                    @if($user->isAvatarEmoji())
                                        <span class="text-sm leading-none">{{ $user->avatarContent() }}</span>
                                    @else
                                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $user->avatarContent() }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex items-center gap-1.5">
                                    <span class="font-medium text-slate-800 dark:text-slate-100 truncate">{{ $user->displayName() }}</span>
                                    @if($user->is_admin)
                                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 leading-none whitespace-nowrap">Admin</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400 hidden sm:table-cell truncate max-w-[200px]">
                            {{ $user->email }}
                        </td>
                        <td class="px-5 py-3 text-center whitespace-nowrap tabular-nums">
                            @php $count = (int) $user->group_predictions_count; @endphp
                            <span class="{{ $count === $totalGroupMatches ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : ($count > 0 ? 'text-slate-700 dark:text-slate-300' : 'text-slate-400 dark:text-slate-600') }} text-sm">
                                {{ $count }}/{{ $totalGroupMatches }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right text-slate-500 dark:text-slate-400 whitespace-nowrap text-xs tabular-nums hidden sm:table-cell">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if(!$user->is_admin && $user->id !== auth()->id())
                            <button type="button"
                                    data-url="{{ route('admin.users.destroy', $user) }}"
                                    data-name="{{ $user->displayName() }}"
                                    onclick="confirmDelete(this)"
                                    class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                Deletar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
            <span class="text-xs text-slate-400">
                Exibindo {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }}
            </span>
            <div class="flex items-center gap-1">
                @if($users->onFirstPage())
                    <span class="px-3 py-1.5 rounded-lg text-xs text-slate-300 dark:text-slate-600 border border-slate-200 dark:border-slate-800 cursor-not-allowed">← Anterior</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        ← Anterior
                    </a>
                @endif

                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Próxima →
                    </a>
                @else
                    <span class="px-3 py-1.5 rounded-lg text-xs text-slate-300 dark:text-slate-600 border border-slate-200 dark:border-slate-800 cursor-not-allowed">Próxima →</span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>

{{-- Modal de confirmação de delete --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl p-6 w-full max-w-sm">
        <h3 class="font-display font-bold text-slate-900 dark:text-white text-lg">Deletar usuário?</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Tem certeza que deseja remover <strong id="delete-modal-name" class="text-slate-800 dark:text-slate-200"></strong>?
            Esta ação não pode ser desfeita.
        </p>
        <form id="delete-modal-form" method="POST" class="flex gap-3 mt-6 justify-end">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                Cancelar
            </button>
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition"
                    style="background-color:#ef4444;color:#fff;">
                Deletar
            </button>
        </form>
    </div>
</div>

<script>
function confirmDelete(btn) {
    document.getElementById('delete-modal-name').textContent = btn.dataset.name;
    document.getElementById('delete-modal-form').action = btn.dataset.url;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}

(function () {
    var statsUrl = '{{ route('admin.users.stats') }}';

    function pad(n) { return String(n).padStart(2, '0'); }

    function poll() {
        fetch(statsUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (data) {
                if (!data) return;
                document.getElementById('stat-total').textContent = data.total;
                document.getElementById('stat-24h').textContent   = data.last_24h;
                document.getElementById('stat-7d').textContent    = data.last_7d;
                document.getElementById('stat-30d').textContent   = data.last_30d;
                var d = new Date();
                document.getElementById('last-update').textContent =
                    'atualizado às ' + pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
            })
            .catch(function () {});
    }

    setInterval(poll, 10000);
}());
</script>

@endsection
