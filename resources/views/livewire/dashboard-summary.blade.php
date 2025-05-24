<div>
    {{-- O título da aba/janela será definido pelo layoutData no método render do componente --}}
    {{-- <x-slot:title> {{ $pageTitle }} {{ $pageSubtitle }} </x-slot:title> --}}

    <div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6">

        {{-- CABEÇALHO DO DASHBOARD --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                    {{ $pageTitle }}
                </h1>
                @if($pageSubtitle)
                    <p class="text-sm text-gray-600 dark:text-neutral-400">{{ $pageSubtitle }}</p>
                @endif
            </div>

            {{-- Seletor de Unidade para MANAGER --}}
            @if(Auth::user()->hasRole('manager') && $unitsForFilter->isNotEmpty())
                <div class="w-full sm:w-auto">
                    <label for="selectedUnitId" class="sr-only">{{ __('Filtrar por Unidade') }}</label>
                    <select wire:model.live="selectedUnitId" id="selectedUnitId"
                            class="block w-full sm:w-72 rounded-md border-gray-300 dark:border-neutral-600 py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm">
                        <option value="all">{{ __('Todas as Unidades (Visão Geral)') }}</option>
                        @foreach($unitsForFilter as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>


        {{-- RESUMO DE SOLICITAÇÕES DE RECEITAS --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-neutral-300 mb-4">Resumo de Solicitações de Receitas</h2>
            @php
                $statusCardsInfo = [
                    // Usando as propriedades do componente Livewire para contagem
                    ['label' => __('Solicitadas'), 'count' => $solicitadasCount, 'icon' => 'icon-[mdi--email-plus-outline]', 'color' => 'blue', 'filter' => \App\Enums\PrescriptionStatus::REQUESTED->value],
                    ['label' => __('Em Análise Médica'), 'count' => $emAnaliseCount, 'icon' => 'icon-[mdi--account-search-outline]', 'color' => 'yellow', 'filter' => \App\Enums\PrescriptionStatus::UNDER_DOCTOR_REVIEW->value],
                    ['label' => __('Rejeitadas (Correção)'), 'count' => $rejeitadasCount, 'icon' => 'icon-[mdi--file-alert-outline]', 'color' => 'yellow', 'filter' => \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value],
                    ['label' => __('Aprovadas p/ Emissão'), 'count' => $aprovadasCount, 'icon' => 'icon-[mdi--file-check-outline]', 'color' => 'cyan', 'filter' => \App\Enums\PrescriptionStatus::APPROVED_FOR_ISSUANCE->value],
                    ['label' => __('Prontas p/ Retirada'), 'count' => $prontasRetiradaCount, 'icon' => 'icon-[mdi--package-variant-closed-check]', 'color' => 'purple', 'filter' => \App\Enums\PrescriptionStatus::READY_FOR_PICKUP->value],
                ];

                $summaryCardsInfo = [
                    ['label' => __('Total no Mês'), 'count' => $totalPrescricoesMes, 'icon' => 'icon-[mdi--calendar-month-outline]', 'color' => 'slate', 'route' => route('prescriptions.index')],
                    ['label' => __('Entregues Hoje'), 'count' => $entreguesHojeCount, 'icon' => 'icon-[mdi--calendar-check-outline]', 'color' => 'green', 'route' => route('prescriptions.index', ['status' => \App\Enums\PrescriptionStatus::DELIVERED->value])],
                    ['label' => __('Canceladas Hoje'), 'count' => $canceladasHojeCount, 'icon' => 'icon-[mdi--calendar-remove-outline]', 'color' => 'pink', 'route' => route('prescriptions.index', ['status' => \App\Enums\PrescriptionStatus::CANCELLED->value])],
                ];

                // Função helper de cores (pode ser movida para um helper Blade ou para o componente)
                if (!function_exists('getCardDashboardColors')) { // Evita redeclarar a função
                    function getCardDashboardColors($colorName) {
                        $colors = [
                            'blue' => ['border' => 'border-blue-500 dark:border-blue-400', 'bg_icon' => 'bg-blue-100 dark:bg-blue-900/30', 'text_icon' => 'text-blue-600 dark:text-blue-400', 'text_link' => 'text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200'],
                            'yellow' => ['border' => 'border-yellow-500 dark:border-yellow-400', 'bg_icon' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text_icon' => 'text-yellow-600 dark:text-yellow-400', 'text_link' => 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200'],
                            'cyan' => ['border' => 'border-cyan-500 dark:border-cyan-400', 'bg_icon' => 'bg-cyan-100 dark:bg-cyan-900/30', 'text_icon' => 'text-cyan-600 dark:text-cyan-400', 'text_link' => 'text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-200'],
                            'purple' => ['border' => 'border-purple-500 dark:border-purple-400', 'bg_icon' => 'bg-purple-100 dark:bg-purple-900/30', 'text_icon' => 'text-purple-600 dark:text-purple-400', 'text_link' => 'text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-200'],
                            'green' => ['border' => 'border-green-500 dark:border-green-400', 'bg_icon' => 'bg-green-100 dark:bg-green-900/30', 'text_icon' => 'text-green-600 dark:text-green-400', 'text_link' => 'text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200'],
                            'pink' => ['border' => 'border-pink-500 dark:border-pink-400', 'bg_icon' => 'bg-pink-100 dark:bg-pink-900/30', 'text_icon' => 'text-pink-600 dark:text-pink-400', 'text_link' => 'text-pink-600 dark:text-pink-400 hover:text-pink-800 dark:hover:text-pink-200'],
                            'gray' => ['border' => 'border-gray-400 dark:border-neutral-500', 'bg_icon' => 'bg-gray-100 dark:bg-neutral-700', 'text_icon' => 'text-gray-500 dark:text-neutral-300', 'text_link' => 'text-gray-600 dark:text-neutral-400 hover:text-gray-800 dark:hover:text-neutral-200'],
                            'slate' => ['border' => 'border-slate-500 dark:border-slate-400', 'bg_icon' => 'bg-slate-100 dark:bg-slate-800/30', 'text_icon' => 'text-slate-600 dark:text-slate-300', 'text_link' => 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200'],
                        ];
                        return $colors[$colorName] ?? $colors['gray'];
                    }
                }
            @endphp

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($summaryCardsInfo as $info)
                    @php $colors = getCardDashboardColors($info['color']); @endphp
                    <a href="{{ $info['route'] }}" wire:navigate
                       class="block bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg
                              border-l-4 {{ $colors['border'] }}
                              hover:shadow-xl transition-shadow duration-300 ease-in-out"> {{-- Sombra um pouco mais sutil --}}
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
                                                {{ $info['count'] }} {{-- Usando a propriedade diretamente --}}
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 mt-5">
                @foreach($statusCardsInfo as $info)
                    @php $colors = getCardDashboardColors($info['color']); @endphp
                    <a href="{{ route('prescriptions.index', ['status' => $info['filter']]) }}" wire:navigate
                       class="block bg-white dark:bg-neutral-800 overflow-hidden shadow-lg rounded-lg
                              border-l-4 {{ $colors['border'] }}
                              hover:shadow-xl transition-shadow duration-300 ease-in-out"> {{-- Sombra um pouco mais sutil --}}
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
                                                {{ $info['count'] }} {{-- Usando a propriedade diretamente --}}
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
    </div>
</div>
