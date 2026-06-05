<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Páginas autenticadas não devem ser indexadas --}}
    <title>Bolão Copa 2026 — @yield('title', 'Dashboard')</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bolão Copa 2026">
    <meta property="og:title" content="Bolão Copa 2026 — @yield('title', 'Dashboard')">
    <meta property="og:description" content="Faça seus palpites para a Copa do Mundo 2026 e dispute com seus amigos!">
    <meta property="og:locale" content="pt_BR">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    {{-- Tema: aplica preferência salva ANTES de pintar a página (evita flash) --}}
    <script>
        (function () {
            var saved = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased">

    {{-- Skip link para acessibilidade via teclado (WCAG 2.4.1) --}}
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-emerald-600 focus:text-white focus:rounded-lg focus:text-sm focus:font-medium focus:shadow-lg">
        Pular para o conteúdo principal
    </a>

    {{-- Navegação --}}
    <nav class="sticky top-0 z-50 bg-white/95 dark:bg-slate-950/95 backdrop-blur-md border-b border-slate-200/70 dark:border-slate-800/70"
         aria-label="Navegação principal">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group" aria-label="Bolão Copa 2026 — ir para o dashboard">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center group-hover:bg-emerald-500/20 transition">
                        <span class="text-base leading-none" aria-hidden="true">⚽</span>
                    </div>
                    <span class="font-display font-bold text-xl text-slate-900 dark:text-white hidden sm:inline tracking-wide">
                        Bolão <span class="text-emerald-600 dark:text-emerald-400">Copa</span> 2026
                    </span>
                </a>

                {{-- Links centrais --}}
                <div class="hidden md:flex items-center gap-0.5" role="list">
                    <a href="{{ route('dashboard') }}"
                       role="listitem"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}>
                        Dashboard
                    </a>
                    <a href="{{ route('groups.index') }}"
                       role="listitem"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('groups.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('groups.*') ? 'aria-current=page' : '' }}>
                        Grupos
                    </a>
                    <a href="{{ route('matches.index') }}"
                       role="listitem"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('matches.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('matches.*') ? 'aria-current=page' : '' }}>
                        Partidas
                    </a>
                    <a href="{{ route('predictions.index') }}"
                       role="listitem"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('predictions.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('predictions.*') ? 'aria-current=page' : '' }}>
                        Meus Palpites
                    </a>
                    <a href="{{ route('bolao.index') }}"
                       role="listitem"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('bolao.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('bolao.*') ? 'aria-current=page' : '' }}>
                        Meu Bolão
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.results') }}"
                       role="listitem"
                       class="ml-2 px-3.5 py-2 rounded-lg text-sm font-medium transition-all
                              bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 hover:bg-red-500/20 hover:text-red-700 dark:hover:text-red-300">
                        <span aria-hidden="true">⚙</span> Admin
                    </a>
                    @endif
                </div>

                {{-- Usuário + Toggle + Logout --}}
                <div class="flex items-center gap-2">

                    {{-- Profile dropdown --}}
                    <div class="hidden sm:block relative" id="profile-menu">
                        <button type="button"
                                onclick="toggleProfileMenu()"
                                aria-expanded="false"
                                aria-controls="profile-dropdown"
                                aria-haspopup="dialog"
                                aria-label="Abrir menu do perfil"
                                data-full-name="{{ auth()->user()->name }}"
                                class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-all group focus-visible:ring-2 focus-visible:ring-emerald-500">
                            {{-- Avatar --}}
                            <div id="nav-avatar"
                                 class="w-7 h-7 rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center flex-shrink-0 group-hover:border-emerald-500/50 transition-colors"
                                 aria-hidden="true">
                                @if(auth()->user()->isAvatarEmoji())
                                    <span class="text-sm leading-none">{{ auth()->user()->avatarContent() }}</span>
                                @else
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ auth()->user()->avatarContent() }}</span>
                                @endif
                            </div>
                            <span class="text-slate-600 dark:text-slate-400 text-sm group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                {{ auth()->user()->displayName() }}
                            </span>
                            <svg id="profile-chevron"
                                 class="w-3 h-3 text-slate-400 dark:text-slate-600 transition-transform duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown panel --}}
                        <div id="profile-dropdown"
                             class="hidden absolute right-0 top-[calc(100%+8px)] w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl shadow-black/10 dark:shadow-black/40 overflow-hidden z-50"
                             role="dialog"
                             aria-label="Editar perfil"
                             aria-modal="false">

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf @method('PUT')

                                {{-- Header: avatar preview + info --}}
                                <div class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 p-4 flex items-center gap-3">
                                    <div id="dd-avatar"
                                         class="w-12 h-12 rounded-full bg-emerald-500/10 border-2 border-emerald-500/30 flex items-center justify-center flex-shrink-0"
                                         aria-hidden="true">
                                        @if(auth()->user()->isAvatarEmoji())
                                            <span id="dd-avatar-content" class="text-2xl leading-none">{{ auth()->user()->avatarContent() }}</span>
                                        @else
                                            <span id="dd-avatar-content" class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ auth()->user()->avatarContent() }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div id="dd-name-preview" class="font-semibold text-slate-900 dark:text-white text-sm truncate">
                                            {{ auth()->user()->displayName() }}
                                        </div>
                                        <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>

                                {{-- Nickname --}}
                                <div class="p-4 border-b border-slate-100 dark:border-slate-800/60">
                                    <label for="dd-nickname" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">
                                        Apelido
                                    </label>
                                    <input type="text"
                                           name="nickname"
                                           id="dd-nickname"
                                           value="{{ auth()->user()->nickname }}"
                                           maxlength="30"
                                           placeholder="{{ auth()->user()->name }}"
                                           autocomplete="off"
                                           class="w-full text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                                    <p class="text-xs text-slate-400 dark:text-slate-600 mt-1.5">
                                        Deixe em branco para usar o nome completo
                                    </p>
                                </div>

                                {{-- Avatar selector --}}
                                <div class="p-4 border-b border-slate-100 dark:border-slate-800/60">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                            Seleção favorita
                                        </label>
                                        <button type="button"
                                                onclick="clearAvatarEmoji()"
                                                class="text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 underline underline-offset-2 transition focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">
                                            Usar inicial
                                        </button>
                                    </div>
                                    <input type="hidden" name="avatar_emoji" id="dd-avatar-emoji" value="{{ auth()->user()->avatar_emoji }}">
                                    <div class="grid grid-cols-8 gap-1 max-h-28 overflow-y-auto" style="scrollbar-width: thin;">
                                        @foreach($navTeams ?? collect() as $team)
                                            <button type="button"
                                                    onclick="selectAvatarEmoji('{{ $team->flag_emoji }}')"
                                                    title="{{ $team->name }}"
                                                    data-emoji="{{ $team->flag_emoji }}"
                                                    class="team-emoji-btn w-8 h-8 rounded-lg flex items-center justify-center text-lg hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 {{ auth()->user()->avatar_emoji === $team->flag_emoji ? 'team-emoji-selected' : '' }}">
                                                {{ $team->flag_emoji }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Save --}}
                                <div class="p-4">
                                    <button type="submit"
                                            class="w-full bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white font-semibold text-sm py-2.5 rounded-xl transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900">
                                        Salvar perfil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Toggle de tema --}}
                    <button type="button"
                            id="theme-toggle"
                            onclick="toggleTheme()"
                            aria-label="Mudar para tema escuro"
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">
                        {{-- Sol: visível no modo escuro (clique para ir ao claro) --}}
                        <svg class="hidden dark:block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{-- Lua: visível no modo claro (clique para ir ao escuro) --}}
                        <svg class="block dark:hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-slate-600 dark:text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 text-sm px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition-all focus-visible:ring-2 focus-visible:ring-emerald-500">
                            Sair
                        </button>
                    </form>
                </div>
            </div>

            {{-- Menu mobile --}}
            <div class="flex md:hidden gap-1 pb-3 overflow-x-auto" style="-ms-overflow-style:none;scrollbar-width:none;"
                 role="list" aria-label="Navegação mobile">
                <a href="{{ route('dashboard') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition
                          {{ request()->routeIs('dashboard') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                   {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}>
                    Dashboard
                </a>
                <a href="{{ route('groups.index') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition
                          {{ request()->routeIs('groups.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                   {{ request()->routeIs('groups.*') ? 'aria-current=page' : '' }}>
                    Grupos
                </a>
                <a href="{{ route('matches.index') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition
                          {{ request()->routeIs('matches.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                   {{ request()->routeIs('matches.*') ? 'aria-current=page' : '' }}>
                    Partidas
                </a>
                <a href="{{ route('predictions.index') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition
                          {{ request()->routeIs('predictions.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                   {{ request()->routeIs('predictions.*') ? 'aria-current=page' : '' }}>
                    Palpites
                </a>
                <a href="{{ route('bolao.index') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition
                          {{ request()->routeIs('bolao.*') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                   {{ request()->routeIs('bolao.*') ? 'aria-current=page' : '' }}>
                    Bolão
                </a>
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.results') }}"
                   role="listitem"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400">
                    ⚙ Admin
                </a>
                @endif
            </div>
        </div>
    </nav>

    {{-- Conteúdo principal --}}
    <main id="main-content" class="max-w-7xl mx-auto px-4 py-8">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 rounded-xl flex items-center gap-3 animate-in"
                 role="alert" aria-live="polite">
                <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-300 rounded-xl flex items-center gap-3 animate-in"
                 role="alert" aria-live="polite">
                <div class="w-5 h-5 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                    <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200/60 dark:border-slate-800/60 mt-16 py-6 text-center">
        <p class="text-slate-500 dark:text-slate-600 text-xs">© {{ date('Y') }} Bolão Copa 2026 · FIFA World Cup 2026</p>
    </footer>

    <script>
        function toggleTheme() {
            var html = document.documentElement;
            html.classList.add('theme-transitioning');
            html.classList.toggle('dark');
            var isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            var btn = document.getElementById('theme-toggle');
            if (btn) {
                btn.setAttribute('aria-label', isDark ? 'Mudar para tema claro' : 'Mudar para tema escuro');
            }
            setTimeout(function () { html.classList.remove('theme-transitioning'); }, 300);
        }

        // Sincroniza o aria-label com o estado atual ao carregar
        (function () {
            var isDark = document.documentElement.classList.contains('dark');
            var btn = document.getElementById('theme-toggle');
            if (btn) {
                btn.setAttribute('aria-label', isDark ? 'Mudar para tema claro' : 'Mudar para tema escuro');
            }
        })();

        // ── Profile dropdown ──────────────────────────────────────
        var _profileOpen = false;

        function toggleProfileMenu() {
            _profileOpen ? closeProfileMenu() : openProfileMenu();
        }

        function openProfileMenu() {
            _profileOpen = true;
            var dd  = document.getElementById('profile-dropdown');
            var btn = document.querySelector('#profile-menu > button');
            var chv = document.getElementById('profile-chevron');
            if (!dd) return;
            dd.classList.remove('hidden');
            dd.classList.add('dropdown-enter');
            if (btn) btn.setAttribute('aria-expanded', 'true');
            if (chv) chv.style.transform = 'rotate(180deg)';
        }

        function closeProfileMenu() {
            _profileOpen = false;
            var dd  = document.getElementById('profile-dropdown');
            var btn = document.querySelector('#profile-menu > button');
            var chv = document.getElementById('profile-chevron');
            if (!dd) return;
            dd.classList.add('hidden');
            dd.classList.remove('dropdown-enter');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            if (chv) chv.style.transform = '';
        }

        // Fechar ao clicar fora
        document.addEventListener('click', function (e) {
            var menu = document.getElementById('profile-menu');
            if (menu && !menu.contains(e.target)) closeProfileMenu();
        });

        // Fechar com Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeProfileMenu();
        });

        // Seleciona emoji de seleção favorita
        function selectAvatarEmoji(emoji) {
            document.getElementById('dd-avatar-emoji').value = emoji;

            // Atualiza preview grande
            var content = document.getElementById('dd-avatar-content');
            if (content) {
                content.textContent = emoji;
                content.className = 'text-2xl leading-none';
            }

            // Atualiza avatar pequeno na navbar
            var navSpan = document.querySelector('#nav-avatar span');
            if (navSpan) {
                navSpan.textContent = emoji;
                navSpan.className = 'text-sm leading-none';
            }

            // Atualiza destaque no grid
            document.querySelectorAll('.team-emoji-btn').forEach(function (btn) {
                btn.classList.toggle('team-emoji-selected', btn.dataset.emoji === emoji);
            });
        }

        // Remove emoji — volta para a inicial do apelido/nome
        function clearAvatarEmoji() {
            document.getElementById('dd-avatar-emoji').value = '';

            var nickname = document.getElementById('dd-nickname').value.trim();
            var fullName = document.querySelector('#profile-menu > button').dataset.fullName || '';
            var initial  = (nickname || fullName).charAt(0).toUpperCase() || '?';

            // Atualiza preview grande
            var content = document.getElementById('dd-avatar-content');
            if (content) {
                content.textContent = initial;
                content.className = 'text-lg font-bold text-emerald-600 dark:text-emerald-400';
            }

            // Atualiza avatar pequeno na navbar
            var navSpan = document.querySelector('#nav-avatar span');
            if (navSpan) {
                navSpan.textContent = initial;
                navSpan.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400';
            }

            // Remove destaque de todos
            document.querySelectorAll('.team-emoji-btn').forEach(function (btn) {
                btn.classList.remove('team-emoji-selected');
            });
        }

        // Atualiza preview do nome enquanto digita o apelido
        document.addEventListener('DOMContentLoaded', function () {
            var nicknameInput = document.getElementById('dd-nickname');
            if (!nicknameInput) return;

            var fullName = document.querySelector('#profile-menu > button').dataset.fullName || '';

            nicknameInput.addEventListener('input', function () {
                var val      = this.value.trim();
                var display  = val || fullName;

                // Atualiza preview de nome
                var nameEl = document.getElementById('dd-name-preview');
                if (nameEl) nameEl.textContent = display;

                // Atualiza inicial no avatar se não há emoji
                var emojiVal = document.getElementById('dd-avatar-emoji').value;
                if (!emojiVal) {
                    var initial = display.charAt(0).toUpperCase() || '?';
                    var content = document.getElementById('dd-avatar-content');
                    if (content) content.textContent = initial;

                    var navSpan = document.querySelector('#nav-avatar span');
                    if (navSpan) navSpan.textContent = initial;
                }

                // Atualiza nome na navbar
                var navName = document.querySelector('#profile-menu > button > span');
                if (navName) navName.textContent = display;
            });
        });
    </script>

</body>
</html>
