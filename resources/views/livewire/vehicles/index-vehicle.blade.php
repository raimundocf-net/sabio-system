<div>
    {{-- Título da Página para o Slot do Layout --}}
    <x-slot:title>
        {{ $pageTitle ?? __('Gerenciar Veículos') }}
    </x-slot:title>

    {{-- Barra Superior Integrada: Título, Botão de Ação e Filtros --}}
    <div class="flex flex-col gap-4 mb-6 mt-4 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 lg:px-8">

        {{-- Título e Botão Novo (Mobile) --}}
        <div class="flex justify-between items-center w-full sm:w-auto">
            <h1 class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-neutral-100">
                {{-- Ícone para Veículos: ph--car-fill ou mdi:car-cog ou lucide:car --}}
                <span class="icon-[ph--car-fill] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                {{ $pageTitle ?? __('Veículos') }}
            </h1>

            @can('create', \App\Models\Vehicle::class)
                <div class="sm:hidden">
                    <a href="{{ route('vehicles.create') }}"
                       wire:navigate
                       class="ml-2 inline-flex items-center justify-center gap-1 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                        <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                        {{ __('Novo') }}
                    </a>
                </div>
            @endcan
        </div>

        {{-- Filtros e Busca --}}
        <div class="w-full sm:flex-1 flex flex-col sm:flex-row flex-wrap gap-2 justify-start sm:justify-center items-center">
            <div class="w-full sm:w-auto">
                <select wire:model.live="perPage" title="{{__('Itens por página')}}" class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="5">5 {{__('por pág.')}}</option>
                    <option value="10">10 {{__('por pág.')}}</option>
                    <option value="15">15 {{__('por pág.')}}</option>
                    <option value="25">25 {{__('por pág.')}}</option>
                    <option value="50">50 {{__('por pág.')}}</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <select wire:model.live="filterVehicleType"
                        title="{{__('Filtrar por tipo')}}"
                        class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="">{{__('Todo Tipo')}}</option>
                    {{-- As opções serão carregadas do componente Livewire --}}
                    @if(isset($vehicleTypeOptions))
                        @foreach($vehicleTypeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <select wire:model.live="filterAvailabilityStatus"
                        title="{{__('Filtrar por disponibilidade')}}"
                        class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="">{{__('Toda Disponibilidade')}}</option>
                    {{-- As opções serão carregadas do componente Livewire --}}
                    @if(isset($availabilityStatusOptions))
                        @foreach($availabilityStatusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="{{__('Buscar por Placa, Marca, Modelo...')}}"
                       class="block w-full sm:min-w-64 rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500" />
            </div>
        </div>

        {{-- Botão Novo (Desktop) --}}
        @can('create', \App\Models\Vehicle::class)
            <div class="hidden sm:flex w-full sm:w-auto sm:justify-end">
                <a href="{{ route('vehicles.create') }}"
                   wire:navigate
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                    {{-- Ícone para Adicionar Veículo: mdi:car-plus ou mdi:plus-box-outline --}}
                    <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                    {{ __('Novo Veículo') }}
                </a>
            </div>
        @endcan
    </div>

    {{-- Mensagens de Sessão (Sucesso, Erro) --}}
    @include('livewire.partials.session-messages')

    {{-- Tabela de Veículos --}}
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Placa')}}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Marca/Modelo')}}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Tipo')}}</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider" title="{{__('Capacidade de Passageiros')}}">{{__('Cap.')}}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Status')}}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('KM Atual')}}</th>
                    <th scope="col" class="relative px-4 py-3"><span class="sr-only">{{__('Ações')}}</span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($vehicles as $vehicle)
                    <tr wire:key="vehicle-row-{{ $vehicle->id }}" class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors duration-150">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100">{{ $vehicle->plate_number }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <div class="flex flex-col">
                                <span>{{ $vehicle->brand }} / {{ $vehicle->model }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-400">{{ $vehicle->year_of_manufacture }} {{ $vehicle->model_year ? '/ '.$vehicle->model_year : '' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            {{ $vehicle->type?->label() ?? ($vehicle->type ?: 'N/D') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-700 dark:text-neutral-300">{{ $vehicle->passenger_capacity }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $vehicle->availability_status?->badgeClasses() ?? '' }}">
                                {{ $vehicle->availability_status?->label() ?? ($vehicle->availability_status ?: 'N/D') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">{{ $vehicle->current_mileage ? number_format($vehicle->current_mileage, 0, '', '.') : 'N/D' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1 rtl:space-x-reverse">
                            @can('update', $vehicle)
                                <a href="{{ route('vehicles.edit', $vehicle->id) }}"
                                   wire:navigate title="{{__('Editar Veículo')}}"
                                   class="inline-flex items-center justify-center p-1.5 rounded-full text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-neutral-600 transition-colors">
                                    <span class="icon-[tabler--pencil] w-5 h-5"></span>
                                </a>
                            @endcan
                            @can('delete', $vehicle)
                                <button wire:click="openDeleteModal({{ $vehicle->id }})"
                                        title="{{__('Excluir Veículo')}}"
                                        class="inline-flex items-center justify-center p-1.5 rounded-full text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-neutral-600 transition-colors">
                                    <span class="icon-[tabler--trash] w-5 h-5"></span>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center text-sm text-gray-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                {{-- Ícone para "nenhum veículo": ph:car-profile-duotone ou mdi:car-off --}}
                                <span class="icon-[ph--car-profile-duotone] text-6xl text-gray-300 dark:text-neutral-600 mb-3"></span>
                                {{ __('Nenhum veículo encontrado.') }}
                                @if(empty($searchTerm) && empty($filterVehicleType) && empty($filterAvailabilityStatus))
                                    @can('create', \App\Models\Vehicle::class)
                                        <p class="mt-2 text-xs">{{__('Clique em "Novo Veículo" para adicionar o primeiro.')}}</p>
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
        @if ($vehicles->hasPages())
            <div class="py-4 px-1">
                {{ $vehicles->links(data: ['scrollTo' => false]) }} {{-- Adicionado data: ['scrollTo' => false] para evitar o scroll to top padrão do Livewire --}}
            </div>
        @endif
    </div>

    {{-- Modal de Exclusão --}}
    @if($showDeleteModal && $deletingVehicle)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-delete-vehicle" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeDeleteModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-delete-vehicle">{{ __('Confirmar Exclusão de Veículo') }}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-neutral-300">
                                        {{ __('Você tem certeza que deseja excluir o veículo de placa') }} <strong>{{ $deletingVehicle->plate_number }} ({{ $deletingVehicle->brand }} {{ $deletingVehicle->model }})</strong>?
                                        {{ __('Esta ação não poderá ser desfeita.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="deleteVehicle" type="button" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                            <span wire:loading.remove wire:target="deleteVehicle">{{ __('Excluir Veículo') }}</span>
                            <svg wire:loading wire:target="deleteVehicle" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <button wire:click="closeDeleteModal" type="button" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                            {{ __('Cancelar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
