<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6"> {{-- Adicionado padding geral à página --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                {{ $pageTitle }}
            </h1>
            {{-- Botão para criar nova solicitação --}}
            @can('create', App\Models\Prescription::class) {{-- Verificação de permissão --}}
            <a href="{{ route('prescriptions.request.search') }}" wire:navigate
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-sky-500 dark:hover:bg-sky-400 dark:active:bg-sky-600 dark:focus:border-sky-600 dark:focus:ring-sky-300">
                <span class="icon-[mdi--text-box-plus-outline] w-5 h-5 mr-2"></span>
                {{ __('Solicitar Nova Receita') }}
            </a>
            @endcan
        </div>

        @include('livewire.partials.session-messages') {{-- Para exibir session()->flash() --}}

        {{-- Filtros --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <div>
                <label for="searchTermList" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Buscar por Paciente, CPF, Solicitante...')}}</label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTermList" placeholder="{{__('Digite para buscar...')}}"
                       class="mt-1 block w-full rounded-md
                      border border-gray-300 dark:border-neutral-500  {{-- ADICIONADO 'border', AJUSTADO dark border --}}
                      py-2 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700
                      focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm">
                {{-- REMOVIDO shadow-sm --}}
            </div>
            <div>
                <label for="filterStatus" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Filtrar por Status')}}</label>
                <select wire:model.live="filterStatus" id="filterStatus"
                        class="mt-1 block w-full rounded-md
                       border border-gray-300 dark:border-neutral-500  {{-- ADICIONADO 'border', AJUSTADO dark border --}}
                       py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700
                       focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm">
                    {{-- REMOVIDO shadow-sm --}}
                    <option value="">{{__('Todos os Status')}}</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Grid de Cards --}}
        {{-- Grid de Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4"> {{-- Reduzido o gap para 4 para um visual mais compacto --}}
            @forelse($prescriptions as $prescription)
                <div wire:key="prescription-card-{{$prescription->id}}"
                     class="relative flex flex-col bg-white dark:bg-neutral-800 shadow-md hover:shadow-lg border border-gray-200 dark:border-neutral-700 overflow-hidden transition-shadow duration-300 ease-in-out">
                    {{-- Cantos quadrados (removido rounded-xl), sombra mais sutil --}}

                    <div class="p-4 flex flex-col flex-grow"> {{-- Padding interno do card reduzido para p-4 --}}
                        <div class="mb-2"> {{-- Espaçamento abaixo do nome/CPF reduzido --}}
                            <h3 class="text-md font-semibold text-indigo-700 dark:text-indigo-400 truncate" title="{{ $prescription->citizen?->name ?? $prescription->citizen?->name }}">
                                {{-- Consistência no nome do cidadão --}}
                                {{ $prescription->citizen?->name ?? $prescription->citizen?->name ?: __('Cidadão não informado') }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400">
                                CPF: {{ $prescription->citizen?->cpf ?: 'N/A' }}
                            </p>
                        </div>

                        {{-- Detalhes da Prescrição --}}
                        <div class="text-xs text-gray-700 dark:text-neutral-300 mb-3">
                            <p class=" text-gray-800 dark:text-neutral-100">{{__('Pedido da ACS:')}}: {{ $prescription->requester?->name ?: '—' }}</p>
                            <p class="py-4  text-gray-600 dark:text-neutral-400 break-words font-medium text-center">
                                {{ $prescription->prescription_details ? \Illuminate\Support\Str::limit($prescription->prescription_details, 120, '...') : __('Nenhum detalhe fornecido') }}
                            </p>
                        </div>

                        {{-- Informações Adicionais --}}
                        <div class="mt-auto text-xs text-gray-500 dark:text-neutral-400 space-y-0.5"> {{-- Espaçamento entre linhas reduzido --}}
                            <p><span class="font-medium">{{__('Solicitante:')}}</span> {{ $prescription->requester?->name ?: '—' }}</p>
                            <p><span class="font-medium">{{__('Data:')}}</span> {{ $prescription->created_at->format('d/m/y H:i') }}</p>
                            @if($prescription->doctor)
                                <p><span class="font-medium">{{__('Médico:')}}</span> {{ $prescription->doctor?->name }}</p>
                            @endif
                            @if($prescription->unit)
                                <p><span class="font-medium">{{__('Unidade:')}}</span> {{ $prescription->unit?->name }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Rodapé com Status e Ações --}}
                    <div class="flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-neutral-700/70 border-t dark:border-neutral-600"> {{-- Padding do rodapé reduzido --}}
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-md {{ $prescription->status->badgeClasses() }}"> {{-- Badge com cantos arredondados (rounded-md) em vez de full --}}
                            {{ $prescription->status->label() }}
                        </span>

                        <div class="flex space-x-1">
                            @can('view', $prescription) {{-- ou 'update', dependendo da sua policy --}}
                            <a href="{{ route('prescriptions.edit', $prescription->id) }}"
                               wire:navigate
                               class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-600 transition"
                               title="{{__('Detalhes / Editar')}}">
                                <span class="icon-[mdi--eye-outline] w-5 h-5 text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300"></span> {{-- Ícone colorido --}}
                            </a>
                            @endcan

                            @can('cancel', $prescription)
                                <button wire:click="openCancelModal({{ $prescription->id }})"
                                        class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-600 transition"
                                        title="{{__('Cancelar Receita')}}">
                                    <span class="icon-[mdi--cancel-bold] w-5 h-5 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"></span> {{-- Ícone colorido --}}
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <span class="icon-[mdi--text-box-search-outline] w-20 h-20 text-gray-300 dark:text-neutral-600 mx-auto"></span>
                    <h3 class="mt-4 text-xl font-semibold text-gray-600 dark:text-neutral-300">{{ __('Nenhuma solicitação de receita encontrada.') }}</h3>
                    @if(empty($searchTerm) && empty($filterStatus))
                        <p class="mt-2 text-sm text-gray-400 dark:text-neutral-500">
                            {{__('Clique em "Solicitar Nova Receita" para começar ou ajuste os filtros.')}}
                        </p>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($prescriptions->hasPages())
            <div class="pt-6 mt-6 border-t dark:border-neutral-700"> {{-- Ajustado padding/margin e borda --}}
                {{ $prescriptions->links() }} {{-- Se você publicou as views de paginação do Tailwind, ótimo --}}
            </div>
        @endif

        {{-- Modal de Cancelamento --}}
        @if($showCancelModal && $cancellingPrescription)
            <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-cancel" role="dialog" aria-modal="true"> {{-- Aumentado z-index --}}
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div wire:click="closeCancelModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-cancel">{{ __('Cancelar Solicitação de Receita') }}</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 dark:text-neutral-300 mb-1">
                                            {{ __('Tem certeza que deseja cancelar a solicitação para') }} <strong>{{ $cancellingPrescription->citizen?->name ?? $cancellingPrescription->citizen?->name }}</strong>? {{-- Consistência do nome --}}
                                        </p>
                                        <div>
                                            <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Motivo do Cancelamento')}}<span class="text-red-500">*</span></label>
                                            <textarea wire:model.defer="cancellationReason" {{-- ALTERADO para .defer --}}
                                            id="cancellationReason" rows="3"
                                                      class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('cancellationReason') border-red-500 dark:border-red-500 @enderror"></textarea>
                                            @error('cancellationReason') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Botões do Modal (estrutura mantida, ok) --}}
                        <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button wire:click="cancelPrescription" type="button" wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                                <span wire:loading.remove>{{ __('Confirmar Cancelamento') }}</span>
                                <span wire:loading>{{ __('Cancelando...') }}</span>
                            </button>
                            <button wire:click="closeCancelModal" type="button" wire:loading.attr="disabled"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                                {{ __('Manter Solicitação') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <style> /* Estilos do scrollbar mantidos, ok */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</div>
