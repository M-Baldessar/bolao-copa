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
    <meta property="og:image" content="@yield('og_image', asset('og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Bolão Copa 2026 — O bolão mais emocionante da Copa do Mundo FIFA 2026">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Bolão Copa 2026 — @yield('title', 'Entrar')">
    <meta name="twitter:description" content="@yield('meta_description', 'Participe do Bolão da Copa do Mundo 2026! Faça seus palpites, crie grupos com amigos.')">
    <meta name="twitter:image" content="@yield('og_image', asset('og-image.png'))">
    <meta name="twitter:image:alt" content="Bolão Copa 2026 — O bolão mais emocionante da Copa do Mundo FIFA 2026">

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
        /* Grade de fundo */
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
            background:
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(16,185,129,0.06) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 80% 30%, rgba(245,158,11,0.04) 0%, transparent 60%);
        }
        .dark .hero-glow {
            background:
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(16,185,129,0.09) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 80% 30%, rgba(245,158,11,0.06) 0%, transparent 60%);
        }

        /* Dot ao vivo pulsante */
        @keyframes live-ring {
            0%,100% { box-shadow: 0 0 0 0   rgba(16,185,129,0.8); }
            60%      { box-shadow: 0 0 0 5px rgba(16,185,129,0);   }
        }
        .live-dot { animation: live-ring 2s ease-in-out infinite; }

        /* Gradiente verde no texto */
        .g-text {
            background: linear-gradient(125deg, #34d399 0%, #10b981 60%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Float do ball */
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

    {{-- ===== BARRA DE URGÊNCIA ===== --}}
    <div class="relative z-20 bg-amber-500 dark:bg-amber-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4 text-center">
            <p class="text-xs sm:text-sm font-bold uppercase tracking-wide leading-snug">
                ⚡ Já começou a maior Copa de todos os tempos — 48 seleções, 104 jogos.
                <span class="hidden sm:inline">E aí, vai ficar de fora?</span>
            </p>
            <span class="sm:hidden text-xs font-bold uppercase tracking-wide">E aí, vai ficar de fora?</span>
            <a href="{{ route('register') }}"
               class="flex-shrink-0 bg-white text-amber-600 hover:bg-amber-50 transition-colors text-xs font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full">
                Entrar agora →
            </a>
        </div>
    </div>

    {{-- ===== NAVBAR ===== --}}
    <nav class="relative z-10 border-b border-slate-200/60 dark:border-slate-800/60 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md"
         aria-label="Navegação principal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('login') }}" class="flex items-center gap-2.5 group" aria-label="Bolão Copa 2026 — ir para o início">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center group-hover:bg-emerald-500/20 transition-all">
                    <span class="text-base leading-none" aria-hidden="true">⚽</span>
                </div>
                <span class="font-display font-bold text-xl text-slate-900 dark:text-white tracking-wide">
                    Bolão <span class="text-emerald-600 dark:text-emerald-400">Copa</span> 2026
                </span>
            </a>

            {{-- Tabs + Toggle --}}
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
                    <svg class="hidden dark:block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
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

            {{-- Selo ao vivo --}}
            <div class="inline-flex items-center gap-2.5 animate-in">
                <span class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/25 text-emerald-700 dark:text-emerald-400 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-widest">
                    <span class="live-dot w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                    Copa 2026 em andamento
                </span>
                <span class="text-slate-400 dark:text-slate-600 text-xs font-medium hidden sm:inline">EUA · México · Canadá</span>
            </div>

            {{-- Título principal --}}
            <div class="animate-in stagger-1">
                <h1 class="font-display font-bold leading-tight text-slate-900 dark:text-white"
                    style="font-size: clamp(2rem, 4.2vw, 3.4rem);">
                    DESAFIE SEUS AMIGOS.<br>
                    SEJA O <span class="g-text">CAMPEÃO</span><br>
                    E VIVA A GLÓRIA ETERNA.
                </h1>
                <p class="mt-5 text-slate-600 dark:text-slate-400 text-lg leading-relaxed max-w-md">
                    Monte um grupo, faça seus palpites nos 104 jogos da Copa 2026 e descubra de uma vez quem realmente entende de futebol.
                </p>
            </div>

            {{-- Feature cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 animate-in stagger-2">

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">👨‍👩‍👧‍👦</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Família no grupo</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Compartilhe o código de convite e prove, de uma vez por todas, quem na família entende de bola.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-blue-200 dark:hover:border-blue-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">🏢</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Bolão do trabalho</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Deixe o escritório inteiro disputando. A segunda-feira vai ser bem mais movimentada.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-amber-200 dark:hover:border-amber-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">🎯</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Cada detalhe pontua</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Acertou o placar exato? Pontuação máxima. Acertou só o vencedor? Também conta. Nunca é tarde para virar.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 flex items-start gap-3 hover:border-purple-200 dark:hover:border-purple-800/60 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg leading-none" aria-hidden="true">📊</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Ranking que não perdoa</p>
                        <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Atualizado a cada jogo. Acompanhe quem está na frente — e torça para o resto errar.</p>
                    </div>
                </div>

            </div>

            {{-- Tabela de pontuação --}}
            <div class="animate-in stagger-3 bg-white dark:bg-slate-900/60 backdrop-blur-sm border border-slate-200/80 dark:border-slate-800/80 rounded-xl overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-700/60">
                    <span class="text-base" aria-hidden="true">🎯</span>
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Quanto você pode marcar por jogo</p>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">🏅</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Placar exato</span>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-xs font-black text-emerald-600 dark:text-emerald-400">20 pts</span>
                            <span class="text-white bg-emerald-500 font-black px-1.5 py-0.5 rounded uppercase tracking-wide" style="font-size:9px">MAX</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">⚡</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Vencedor + placar do vencedor</span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex-shrink-0">15 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">📐</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Vencedor + diferença de gols</span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex-shrink-0">12 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">🎲</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Vencedor + placar do perdedor</span>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 flex-shrink-0">10 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">✅</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Só o vencedor certo &nbsp;/&nbsp; empate certo</span>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 flex-shrink-0">8 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">💀</span>
                        <span class="text-xs text-slate-500 dark:text-slate-500 flex-1">Errou feio</span>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-600 flex-shrink-0">0 pts</span>
                    </div>
                </div>
                <div class="border-t border-amber-200/60 dark:border-amber-500/20 bg-amber-500/5 dark:bg-amber-500/5 divide-y divide-amber-100/60 dark:divide-amber-500/10">
                    <div class="flex items-center gap-3 px-4 py-2">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">🏆</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Acertou o campeão da Copa</span>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-xs font-black text-amber-600 dark:text-amber-400">+100 pts</span>
                            <span class="text-white bg-amber-500 font-black px-1.5 py-0.5 rounded uppercase tracking-wide" style="font-size:9px">BÔNUS</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-4 py-2">
                        <span class="text-sm flex-shrink-0" aria-hidden="true">🥈</span>
                        <span class="text-xs text-slate-600 dark:text-slate-300 flex-1">Acertou o vice-campeão</span>
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400 flex-shrink-0">+50 pts</span>
                    </div>
                </div>
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
