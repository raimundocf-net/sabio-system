<x-layouts.app :title="$pageTitle ?? __('Dashboard')"> {{-- Usando a variável $pageTitle passada pelo controller --}}
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6 rounded-xl"> {{-- Adicionado padding e aumentado gap --}}

        {{-- TÍTULO DO DASHBOARD --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                {{ __('Painel de Controle') }}
            </h1>
            {{-- Você pode adicionar um botão de ação principal aqui se necessário --}}
        </div>


        {{-- RESUMO DE SOLICITAÇÕES DE RECEITAS --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-neutral-300 mb-4">Resumo de Solicitações de Receitas</h2>
            @php
                // Array para ajudar a gerar os cards. No mundo real, parte disso (cores/ícones) poderia vir do Enum ou ser helpers.
                // As contagens vêm do controller.
                $statusCardsInfo = [
                    ['label' => __('Solicitadas'), 'countVar' => 'solicitadasCount', 'icon' => 'icon-[mdi--email-plus-outline]', 'color' => 'blue', 'filter' => \App\Enums\PrescriptionStatus::REQUESTED->value],
                    ['label' => __('Em Análise Médica'), 'countVar' => 'emAnaliseCount', 'icon' => 'icon-[mdi--account-search-outline]', 'color' => 'yellow', 'filter' => \App\Enums\PrescriptionStatus::UNDER_DOCTOR_REVIEW->value],
                    ['label' => __('Rejeitadas (Correção)'), 'countVar' => 'rejeitadasCount', 'icon' => 'icon-[mdi--file-alert-outline]', 'color' => 'yellow', 'filter' => \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value],
                    ['label' => __('Aprovadas p/ Emissão'), 'countVar' => 'aprovadasCount', 'icon' => 'icon-[mdi--file-check-outline]', 'color' => 'cyan', 'filter' => \App\Enums\PrescriptionStatus::APPROVED_FOR_ISSUANCE->value],
                    ['label' => __('Prontas p/ Retirada'), 'countVar' => 'prontasRetiradaCount', 'icon' => 'icon-[mdi--package-variant-closed-check]', 'color' => 'purple', 'filter' => \App\Enums\PrescriptionStatus::READY_FOR_PICKUP->value],
                    ['label' => __('Rascunhos'), 'countVar' => 'rascunhosCount', 'icon' => 'icon-[mdi--file-edit-outline]', 'color' => 'gray', 'filter' => \App\Enums\PrescriptionStatus::DRAFT_REQUEST->value],
                ];

                // Cards de resumo de período
                 $summaryCardsInfo = [
                    ['label' => __('Total no Mês'), 'countVar' => 'totalPrescricoesMes', 'icon' => 'icon-[mdi--calendar-month-outline]', 'color' => 'slate', 'route' => route('prescriptions.index')], // Link para todas
                    ['label' => __('Entregues Hoje'), 'countVar' => 'entreguesHojeCount', 'icon' => 'icon-[mdi--calendar-check-outline]', 'color' => 'green', 'route' => route('prescriptions.index', ['status' => \App\Enums\PrescriptionStatus::DELIVERED->value])], // Link para entregues
                    ['label' => __('Canceladas Hoje'), 'countVar' => 'canceladasHojeCount', 'icon' => 'icon-[mdi--calendar-remove-outline]', 'color' => 'pink', 'route' => route('prescriptions.index', ['status' => \App\Enums\PrescriptionStatus::CANCELLED->value])], // Link para canceladas
                ];

                // Helper para classes de cor (você pode expandir isso ou usar as do Enum diretamente se tiver um método para cores base)
                function getCardColors($colorName) {
                    $colors = [
                        'blue' => ['border' => 'border-blue-500 dark:border-blue-400', 'bg_icon' => 'bg-blue-100 dark:bg-blue-800/30', 'text_icon' => 'text-blue-600 dark:text-blue-300', 'text_link' => 'text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200'],
                        'yellow' => ['border' => 'border-yellow-500 dark:border-yellow-400', 'bg_icon' => 'bg-yellow-100 dark:bg-yellow-800/30', 'text_icon' => 'text-yellow-600 dark:text-yellow-300', 'text_link' => 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200'],
                        'cyan' => ['border' => 'border-cyan-500 dark:border-cyan-400', 'bg_icon' => 'bg-cyan-100 dark:bg-cyan-800/30', 'text_icon' => 'text-cyan-600 dark:text-cyan-300', 'text_link' => 'text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-200'],
                        'purple' => ['border' => 'border-purple-500 dark:border-purple-400', 'bg_icon' => 'bg-purple-100 dark:bg-purple-800/30', 'text_icon' => 'text-purple-600 dark:text-purple-300', 'text_link' => 'text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-200'],
                        'green' => ['border' => 'border-green-500 dark:border-green-400', 'bg_icon' => 'bg-green-100 dark:bg-green-800/30', 'text_icon' => 'text-green-600 dark:text-green-300', 'text_link' => 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200'],
                        'pink' => ['border' => 'border-pink-500 dark:border-pink-400', 'bg_icon' => 'bg-pink-100 dark:bg-pink-800/30', 'text_icon' => 'text-pink-600 dark:text-pink-300', 'text_link' => 'text-pink-600 dark:text-pink-400 hover:text-pink-800 dark:hover:text-pink-200'],
                        'gray' => ['border' => 'border-gray-400 dark:border-neutral-500', 'bg_icon' => 'bg-gray-100 dark:bg-neutral-700', 'text_icon' => 'text-gray-500 dark:text-neutral-300', 'text_link' => 'text-gray-600 dark:text-neutral-400 hover:text-gray-800 dark:hover:text-neutral-200'],
                        'slate' => ['border' => 'border-slate-500 dark:border-slate-400', 'bg_icon' => 'bg-slate-100 dark:bg-slate-800/30', 'text_icon' => 'text-slate-600 dark:text-slate-300', 'text_link' => 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'],
                    ];
                    return $colors[$colorName] ?? $colors['gray'];
                }
            @endphp

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Cards de Resumo de Período --}}
                @foreach($summaryCardsInfo as $info)
                    @php $colors = getCardColors($info['color']); @endphp
                    <a href="{{ $info['route'] }}" wire:navigate
                       class="block bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg
                              border-l-4 {{ $colors['border'] }}
                              hover:shadow-2xl transition-shadow duration-300 ease-in-out">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 {{ $colors['bg_icon'] }} rounded-md p-3">
                                    <span class="{{ $info['icon'] }} w-6 h-6 {{ $colors['text_icon'] }}"></span>
                                </div>
                                <div class="ml-5 w-0 flex-1 rtl:ml-0 rtl:mr-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400 truncate" title="{{ $info['label'] }}">
                                            {{ $info['label'] }}
                                        </dt>
                                        <dd>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-neutral-100">
                                                {{ ${$info['countVar']} ?? 0 }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-5">
                {{-- Cards de Status --}}
                @foreach($statusCardsInfo as $info)
                    @php $colors = getCardColors($info['color']); @endphp
                    <a href="{{ route('prescriptions.index', ['status' => $info['filter']]) }}" wire:navigate
                       class="block bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg
                              border-l-4 {{ $colors['border'] }}
                              hover:shadow-2xl transition-shadow duration-300 ease-in-out">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 {{ $colors['bg_icon'] }} rounded-md p-3">
                                    <span class="{{ $info['icon'] }} w-6 h-6 {{ $colors['text_icon'] }}"></span>
                                </div>
                                <div class="ml-5 w-0 flex-1 rtl:ml-0 rtl:mr-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400 truncate" title="{{ $info['label'] }}">
                                            {{ $info['label'] }}
                                        </dt>
                                        <dd>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-neutral-100">
                                                {{ ${$info['countVar']} ?? 0 }}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-neutral-700/50 px-5 py-2.5">
                            <div class="text-xs">
                                <span class="font-medium {{ $colors['text_link'] }}">
                                    Ver detalhes &rarr;
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Os placeholders originais podem ser removidos ou substituídos por outros módulos do dashboard --}}
        {{-- <div class="grid auto-rows-min gap-4 md:grid-cols-3 mt-6">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mt-6">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}

    </div>
</x-layouts.app>
