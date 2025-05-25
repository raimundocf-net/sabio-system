<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    {{-- Barra Superior Integrada: Título, Botão de Ação e Filtros --}}
    <div class="flex flex-col gap-4 mb-6 mt-4 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 lg:px-8">

        {{-- Título e Botão Novo (Mobile) --}}
        <div class="flex justify-between items-center w-full sm:w-auto">
            <h1 class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-neutral-100">
                <span class="icon-[mdi--clipboard-text-clock-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                {{ $pageTitle }}
            </h1>

            @can('create', \App\Models\TravelRequest::class)
                <div class="sm:hidden">
                    <a href="{{ route('travel-requests.create') }}"
                       wire:navigate
                       class="ml-2 inline-flex items-center justify-center gap-1 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                        <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                        {{ __('Nova') }}
                    </a>
                </div>
            @endcan
        </div>

        {{-- Filtros e Busca --}}
        <div class="w-full sm:flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 items-center">
            {{-- Itens por página --}}
            <div class="w-full">
                <label for="perPage" class="sr-only">{{__('Itens por página')}}</label>
                <select wire:model.live="perPage" id="perPage" title="{{__('Itens por página')}}" class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="10">10 {{__('por pág.')}}</option>
                    <option value="15">15 {{__('por pág.')}}</option>
                    <option value="25">25 {{__('por pág.')}}</option>
                    <option value="50">50 {{__('por pág.')}}</option>
                </select>
            </div>

            {{-- Filtro de Status --}}
            <div class="w-full">
                <label for="filterStatus" class="sr-only">{{__('Filtrar por status')}}</label>
                <select wire:model.live="filterStatus" id="filterStatus" title="{{__('Filtrar por status')}}"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="">{{__('Todo Status')}}</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro de Tipo de Procedimento --}}
            <div class="w-full">
                <label for="filterProcedureType" class="sr-only">{{__('Filtrar por tipo de procedimento')}}</label>
                <select wire:model.live="filterProcedureType" id="filterProcedureType" title="{{__('Filtrar por tipo de procedimento')}}"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="">{{__('Todo Tipo Proced.')}}</option>
                    @foreach($procedureTypeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro de Opção de Data --}}
            <div class="w-full">
                <label for="filterDateOption" class="sr-only">{{__('Filtrar por data de')}}</label>
                <select wire:model.live="filterDateOption" id="filterDateOption" title="{{__('Filtrar por data de')}}"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="">{{__('Filtrar Data Por...')}}</option>
                    @foreach($dateFilterOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Campo de Busca --}}
            <div class="w-full lg:col-span-2"> {{-- Ocupa mais espaço em telas maiores --}}
                <label for="searchTerm" class="sr-only">{{__('Termo de Busca')}}</label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm"
                       placeholder="{{__('Buscar por ID, Paciente, Destino...')}}"
                       class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500" />
            </div>

            {{-- Filtros de Data Início e Fim (aparecem se uma opção de data for selecionada) --}}
            @if($filterDateOption)
                <div class="w-full">
                    <label for="filterStartDate" class="sr-only">{{__('Data Início')}}</label>
                    <input type="date" wire:model.live="filterStartDate" id="filterStartDate" title="{{__('Data Início')}}"
                           class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                </div>
                <div class="w-full">
                    <label for="filterEndDate" class="sr-only">{{__('Data Fim')}}</label>
                    <input type="date" wire:model.live="filterEndDate" id="filterEndDate" title="{{__('Data Fim')}}"
                           class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                </div>
            @endif
        </div>

        {{-- Botão Novo (Desktop) --}}
        @can('create', \App\Models\TravelRequest::class)
            <div class="hidden sm:flex w-full sm:w-auto sm:justify-end mt-3 sm:mt-0">
                <a href="{{ route('travel-requests.create') }}"
                   wire:navigate
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                    <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                    {{ __('Nova Solicitação') }}
                </a>
            </div>
        @endcan
    </div>

    {{-- Mensagens de Sessão --}}
    @include('livewire.partials.session-messages')

    {{-- Tabela de Solicitações de Viagem --}}
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">#{{__('ID')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Paciente')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Destino')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Data Compromisso')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Tipo Proced.')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Status')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Solicitante')}}</th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Data Solic.')}}</th>
                    <th scope="col" class="relative px-3 py-3"><span class="sr-only">{{__('Ações')}}</span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($travelRequests as $request)
                    <tr wire:key="travel-request-row-{{ $request->id }}" class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors duration-150">
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100">{{ $request->id }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <div class="flex flex-col">
                                <span>{{ $request->citizen?->name ?? __('N/D') }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-400">
                                    CPF: {{ $request->citizen?->cpf ?? __('N/D') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            {{ $request->destination_city }} / {{ $request->destination_state }}
                            <div class="text-xs text-gray-500 dark:text-neutral-400 truncate max-w-xs" title="{{$request->destination_address}}">{{ Str::limit($request->destination_address, 30) }}</div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            {{ $request->appointment_datetime ? \Carbon\Carbon::parse($request->appointment_datetime)->format('d/m/Y H:i') : __('N/D') }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            {{ $request->procedure_type?->label() ?? ($request->procedure_type ?: __('N/D')) }}
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request->status?->badgeClasses() ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $request->status?->label() ?? ($request->status ?: __('N/D')) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">{{ $request->requester?->name ?? __('N/D') }}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : __('N/D')}}</td>
                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1 rtl:space-x-reverse">
                            @can('update', $request)
                                <a href="{{ route('travel-requests.edit', $request->id) }}"
                                   wire:navigate title="{{__('Editar Solicitação')}}"
                                   class="inline-flex items-center justify-center p-1.5 rounded-full text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-neutral-600 transition-colors">
                                    <span class="icon-[tabler--pencil] w-5 h-5"></span>
                                </a>
                            @endcan
                            {{-- O botão de "cancelar" é o modal --}}
                            @can('delete', $request) {{-- Usando 'delete' como permissão genérica para cancelar --}}
                            @if(!in_array($request->status, [\App\Enums\TravelRequestStatus::CANCELLED_BY_USER, \App\Enums\TravelRequestStatus::CANCELLED_BY_ADMIN, \App\Enums\TravelRequestStatus::SCHEDULED])) {{-- Exemplo de condição para não mostrar se já cancelado ou agendado --}}
                            <button wire:click="openCancelModal({{ $request->id }})"
                                    title="{{__('Cancelar Solicitação')}}"
                                    class="inline-flex items-center justify-center p-1.5 rounded-full text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-neutral-600 transition-colors">
                                <span class="icon-[mdi--cancel-bold] w-5 h-5"></span>
                            </button>
                            @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-16 text-center text-sm text-gray-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                <span class="icon-[mdi--alert-rhombus-outline] text-6xl text-gray-300 dark:text-neutral-600 mb-3"></span>
                                {{ __('Nenhuma solicitação de viagem encontrada.') }}
                                @if(empty($searchTerm) && empty($filterStatus) && empty($filterProcedureType) && empty($filterDateOption))
                                    @can('create', \App\Models\TravelRequest::class)
                                        <p class="mt-2 text-xs">{{__('Clique em "Nova Solicitação" para adicionar a primeira.')}}</p>
                                    @endcan
                                @else
                                    <p class="mt-2 text-xs">{{__('Tente ajustar seus filtros ou termo de busca.')}}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($travelRequests->hasPages())
            <div class="py-4 px-1">
                {{ $travelRequests->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    {{-- Modal de Cancelamento --}}
    @if($showCancelModal && $cancellingTravelRequest)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-cancel-request" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeCancelModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-cancel-request">{{ __('Confirmar Cancelamento da Solicitação') }} #{{$cancellingTravelRequest->id}}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-neutral-300">
                                        {{ __('Paciente:') }} <strong>{{ $cancellingTravelRequest->citizen?->name }}</strong><br>
                                        {{ __('Destino:') }} <strong>{{ $cancellingTravelRequest->destination_city }} - {{ $cancellingTravelRequest->destination_state }}</strong><br>
                                        {{ __('Data Compromisso:') }} <strong>{{ $cancellingTravelRequest->appointment_datetime ? \Carbon\Carbon::parse($cancellingTravelRequest->appointment_datetime)->format('d/m/Y H:i') : 'N/D' }}</strong>
                                    </p>
                                    <div class="mt-3">
                                        <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Motivo do Cancelamento')}} <span class="text-red-500">*</span></label>
                                        <textarea wire:model.lazy="cancellationReason" id="cancellationReason" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-red-500 dark:focus:border-red-400 focus:ring-1 focus:ring-red-500 dark:focus:ring-red-400 @error('cancellationReason') border-red-500 dark:border-red-400 @enderror"></textarea>
                                        @error('cancellationReason') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">
                                        {{ __('Esta ação não poderá ser desfeita facilmente. A solicitação será marcada como cancelada.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="cancelTravelRequest" type="button" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                            <span wire:loading.remove wire:target="cancelTravelRequest">{{ __('Confirmar Cancelamento') }}</span>
                            <svg wire:loading wire:target="cancelTravelRequest" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
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
