<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GooCRM | Gestão Corporativa Inteligente</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-dot-grid {
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="antialiased bg-white text-gray-900 selection:bg-gray-900 selection:text-white">

    {{-- Header Minimalista --}}
    <header class="fixed w-full z-50 border-b border-gray-100 bg-white/80 backdrop-blur-md">
        <nav class="container mx-auto px-8 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10">
                    <img src="logo.png" alt="GooCRM Logo" srcset="logo.png"
                        class="w-full h-full object-cover rounded-lg">
                </div>
                <span class="text-xl font-black tracking-tighter text-gray-900">GooCRM</span>
            </div>

            <div class="flex items-center gap-8">
                <a href="/login"
                    class="text-xs font-bold text-gray-600 hover:text-gray-900 transition-colors">Entrar</a>
                <a href="/register"
                    class="bg-blue-900 text-white px-6 py-2.5 rounded-lg font-bold text-xs hover:bg-blue-950 transition-all shadow-xl shadow-gray-900/10">
                    Começar agora
                </a>
            </div>
        </nav>
    </header>

    <main class="bg-dot-grid">
        {{-- Hero Section --}}
        <section class="relative pt-48 pb-32 lg:pt-64 lg:pb-48 px-8">
            <div class="container mx-auto">
                <div class="max-w-5xl">
                    <div
                        class="inline-flex items-center gap-3 py-1.5 px-4 rounded-full border border-gray-200 bg-white mb-10">
                        <span class="text-[10px] font-black text-blue-900 tracking-tight">v1.0.4</span>
                        <span class="w-px h-3 bg-gray-200"></span>
                        <span class="text-[10px] font-bold text-gray-500 tracking-tight">
                            Versão básica para pequenas empresas.
                        </span>
                    </div>

                    <h1 class="text-7xl lg:text-9xl font-black text-gray-900 leading-[0.85] tracking-tighter mb-12">
                        Simplicidade <br>
                        é o novo <br>
                        <span class="text-blue-900 font-escrita px-1 uppercase">padrão.</span>
                    </h1>

                    <p class="pt-5 text-lg lg:text-xl text-gray-500 mb-12 leading-relaxed max-w-xl font-medium">
                        Um ERP de projetos e faturamento que é tão simples quanto poderoso. Gestão financeira, controle
                        de projetos e relacionamento com clientes em uma plataforma leve, rápida e fácil de usar. Dê
                        adeus à complexidade e olá à eficiência.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="/register"
                            class="bg-blue-900 text-white px-10 py-4 rounded-xl font-bold text-sm hover:bg-blue-950 transition-all">
                            Criar minha conta
                        </a>
                        <a href="/login"
                            class="border border-blue-900 bg-white text-blue-900 px-10 py-4 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                            Acessar sua conta
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grid de Funcionalidades (Clean Style) --}}
        <section id="features" class="py-32 border-t border-gray-100 bg-white/50">
            <div class="container mx-auto px-8">
                <div
                    class="grid lg:grid-cols-4 gap-px bg-gray-100 border border-gray-100 rounded-2xl overflow-hidden shadow-2xl shadow-gray-200/50">

                    <div class="bg-white p-12 hover:bg-gray-50 transition-colors">
                        <span class="text-blue-900 font-black text-base mb-8 block font-escrita">01</span>
                        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight">Arquitetura UUID</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-medium">Segurança e rastreabilidade total.
                            Cada registro em seu ERP possui identidade única e inviolável.</p>
                    </div>

                    <div class="bg-white p-12 hover:bg-gray-50 transition-colors">
                        <span class="text-blue-900 font-black text-base mb-8 block font-escrita">02</span>
                        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight">Financeiro BRL</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-medium">Processamento nativo de moeda
                            brasileira. Precisão decimal absoluta em faturas e orçamentos.</p>
                    </div>

                    <div class="bg-white p-12 hover:bg-gray-50 transition-colors">
                        <span class="text-blue-900 font-black text-base mb-8 block font-escrita">03</span>
                        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight">Analytics Limpo</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-medium">Dashboards que mostram o que
                            importa: valores quitados, inadimplência e projeção de caixa.</p>
                    </div>

                    <div class="bg-white p-12 hover:bg-gray-50 transition-colors">
                        <span class="text-blue-900 font-black text-base mb-8 block font-escrita">04</span>
                        <h3 class="text-gray-900 font-bold text-lg mb-4 tracking-tight">Fluxo Ágil</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-medium">Gestão de projetos baseada em
                            entregáveis técnicos. Elimine burocracia e acelere entregas.</p>
                    </div>

                </div>
            </div>
        </section>

        {{-- Call to Action Final --}}
        <section class="py-40 bg-white">
            <div class="max-w-4xl mx-auto px-8 text-center">
                <h2 class="text-5xl lg:text-7xl font-black text-gray-900 tracking-tighter mb-10">Escalabilidade sem
                    caos.</h2>
                <div class="flex justify-center">
                    <a href="/register"
                        class="bg-blue-900 text-white px-14 py-5 rounded-2xl font-black text-sm hover:bg-blue-950 transition-all shadow-2xl shadow-gray-900/20">
                        Começar agora — Grátis
                    </a>
                </div>
                <div class="mt-5">
                    <span>
                        Conta gratuita, sem cartão de crédito, suporte dedicado e acesso a todas as funcionalidades.
                        Teste o poder de um ERP simples e eficiente, projetado para crescer com seu negócio. Sem
                        pegadinhas, apenas resultados.
                    </span>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-gradient-to-l from-slate-950 via-slate-800 to-slate-900 border-t border-gray-100 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-center items-center gap-16">
                <div class="max-w-xs">
                    <div class="flex justify-center items-center gap-3 mb-6">
                        <div class="w-10 h-10">
                            <img src="logo.png" alt="GooCRM Logo" srcset="logo.png"
                                class="w-full h-full object-cover rounded-lg">
                        </div>
                        <span class="text-lg font-black tracking-tighter text-gray-50">ERP<span
                                class="text-blue-800">Simples</span></span>
                    </div>
                    <p class="flex text-center text-xs font-bold text-gray-400 tracking-tight leading-loose">
                        &copy; {{ date('Y') }} GOOCRM ERP SIMPLES. <br>
                        ESTRUTURA DE DADOS SIMPLES, EFICIENTE E OTIMIZADA DE ALTA PERFORMANCE.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>

</html>