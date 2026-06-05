<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO básico --}}
    <title>Bolão Copa 2026 — @yield('title', 'Entrar')</title>
    <meta name="description" content="@yield('meta_description', 'Participe do Bolão da Copa do Mundo 2026! Faça seus palpites, crie grupos com amigos e concorra a prêmios. FIFA World Cup 2026 — EUA, México e Canadá.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bolão Copa 2026">
    <meta property="og:title" content="Bolão Copa 2026 — @yield('title', 'Entrar')">
    <meta property="og:description" content="@yield('meta_description', 'Participe do Bolão da Copa do Mundo 2026! Faça seus palpites, crie grupos com amigos.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="pt_BR">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Bolão Copa 2026 — @yield('title', 'Entrar')">
    <meta name="twitter:description" content="@yield('meta_description', 'Participe do Bolão da Copa do Mundo 2026! Faça seus palpites, crie grupos com amigos.')">

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

    <style>
        /* Grade de fundo — adaptada ao tema */
        .hero-grid {
            background-image:
                linear-gradient(rgba(16,185,129,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.08) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .dark .hero-grid {
            background-image:
                linear-gradient(rgba(16,185,129,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.05) 1px, transparent 1px);
        }
        .hero-glow {
            background: radial-gradient(ellipse 80% 60% at 20% 50%, rgba(16,185,129,0.06) 0%, transparent 70%),
                        radial-gradient(ellipse 40% 40% at 80% 30%, rgba(245,158,11,0.04) 0%, transparent 60%);
        }
        .dark .hero-glow {
            background: radial-gradient(ellipse 80% 60% at 20% 50%, rgba(16,185,129,0.08) 0%, transparent 70%),
                        radial-gradient(ellipse 40% 40% at 80% 30%, rgba(245,158,11,0.05) 0%, transparent 60%);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-2deg); }
            50% { transform: translateY(-8px) rotate(2deg); }
        }
        .float-ball { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased">

    {{-- Background layers --}}
    <div class="fixed inset-0 hero-grid pointer-events-none" aria-hidden="true"></div>
    <div class="fixed inset-0 hero-glow pointer-events-none" aria-hidden="true"></div>

    {{-- ===== NAVBAR ===== --}}
    <nav class="relative z-10 border-b border-slate-200/60 dark:border-slate-800/60 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md"
         aria-label="Navegação principal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('login') }}" class="flex items-center gap-2.5 group" aria-label="Bolão Copa 2026 — ir para o início">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center">
                    <span class="text-base leading-none" aria-hidden="true">⚽</span>
                </div>
                <span class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">
                    Bolão <span class="text-emerald-600 dark:text-emerald-400">Copa</span> 2026
                </span>
            </a>

            {{-- Tabs de navegação + Toggle --}}
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1">
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('register') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('register') ? 'aria-current=page' : '' }}>
                        Cadastre-se
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                              {{ request()->routeIs('login') ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60' }}"
                       {{ request()->routeIs('login') ? 'aria-current=page' : '' }}>
                        Entrar
                    </a>
                </div>

                {{-- Toggle de tema --}}
                <button type="button"
                        id="theme-toggle"
                        onclick="toggleTheme()"
                        aria-label="Mudar para tema escuro"
                        class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition-all focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-950">
                    {{-- Sol: visível no modo escuro --}}
                    <svg class="hidden dark:block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{-- Lua: visível no modo claro --}}
                    <svg class="block dark:hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- ===== CONTEÚDO PRINCIPAL ===== --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[calc(100vh-4rem)] flex flex-col lg:flex-row lg:items-center gap-12 lg:gap-20 py-12 lg:py-0">

        {{-- ===== LADO ESQUERDO: Hero ===== --}}
        <div class="flex-1 space-y-8 lg:py-20">

            {{-- Selo --}}
            <div class="inline-flex items-center gap-2 bg-amber-400/10 border border-amber-400/20 text-amber-700 dark:text-amber-300 text-xs font-semibold px-3.5 py-1.5 rounded-full uppercase tracking-wider animate-in">
                <span aria-hidden="true">🏆</span>
                <span>FIFA World Cup 2026 · EUA, México e Canadá</span>
            </div>

            {{-- Título principal --}}
            <div class="animate-in stagger-1">
                <h1 class="font-display font-bold leading-none text-slate-900 dark:text-white"
                    style="font-size: clamp(3rem, 7vw, 5.5rem);">
                    O BOLÃO<br>
                    MAIS <span class="text-emerald-600 dark:text-emerald-400">EMOCIONANTE</span><br>
                    DA COPA!
                </h1>
                <p class="mt-5 text-slate-600 dark:text-slate-400 text-lg leading-relaxed max-w-md">
                    Faça seus palpites, crie grupos com amigos, família ou colegas de trabalho e veja quem entende mais de futebol.
                </p>
            </div>

            {{-- Feature cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 animate-in stagger-2">

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">👨‍👩‍👧‍👦</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Jogue em família</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Grupo privado, compartilhe o código e desafie todo mundo no jantar de domingo.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">🏢</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Bolão do trabalho</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Deixe o escritório inteiro disputando o título de craque dos palpites.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">🎯</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Pontuação detalhada</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Placar exato, vencedor certo, empate... cada detalhe conta na hora de somar.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">📊</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Ranking em tempo real</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Acompanhe o placar do grupo a cada jogo e torça (ou sofra) com cada resultado.</p>
                    </div>
                </div>

            </div>

            {{-- Tags --}}
            <div class="flex flex-wrap gap-2 animate-in stagger-3">
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-500 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    Gratuito
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-500 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    Fácil de usar
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-500 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                    Da fase de grupos até a final
                </span>
            </div>

        </div>

        {{-- ===== LADO DIREITO: Formulário ===== --}}
        <div class="w-full lg:w-[420px] shrink-0 lg:py-20 animate-in stagger-2">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-200/60 dark:shadow-black/40 p-8">
                @yield('content')
            </div>
        </div>

    </div>

    {{-- ===== RODAPÉ ===== --}}
    <footer class="relative z-10 border-t border-slate-200/60 dark:border-slate-800/60 text-center py-5">
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
    </script>

</body>
</html>
