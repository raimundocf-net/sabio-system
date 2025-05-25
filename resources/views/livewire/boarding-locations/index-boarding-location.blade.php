<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="flex flex-col gap-4 mb-6 mt-4 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center w-full sm:w-auto">
            <h1 class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-neutral-100">
                <span class="icon-[mdi--map-marker-multiple-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                {{ $pageTitle }}
            </h1>
            <div class="sm:hidden">
                <a href="{{ route('boarding-locations.create') }}" wire:navigate
                   class="ml-2 inline-flex items-center justify-center gap-1 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm">
                    <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span> {{ __('Novo') }}
                </a>
            </div>
        </div>

        <div class="w-full sm:flex-1 flex flex-col sm:flex-row flex-wrap gap-2 justify-start sm:justify-end items-center">
            <div class="w-full sm:w-auto">
                <select wire:model.live="perPage" title="{{__('Itens por página')}}" class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="10">10 {{__('por pág.')}}</option>
                    <option value="25">25 {{__('por pág.')}}</option>
                    <option value="50">50 {{__('por pág.')}}</option>
                </select>
            </div>
            <div class="w-full sm:w-auto flex-grow sm:flex-grow-0 sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="{{__('Buscar por Nome ou Endereço...')}}"
                       class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500" />
            </div>
            <div class="hidden sm:flex">
                <a href="{{ route('boarding-locations.create') }}" wire:navigate
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm">
                    <span class="icon-[mdi--map-marker-plus] w-5 h-5"></span> {{ __('Novo Local') }}
                </a>
            </div>
        </div>
    </div>

    @include('livewire.partials.session-messages')

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Nome')}}</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Endereço')}}</th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">{{__('Status')}}</th>
                    <th scope="col" class="relative px-4 py-3"><span class="sr-only">{{__('Ações')}}</span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($locations as $location)
                    <tr wire:key="bl-row-{{ $location->id }}" class="hover:bg-gray-50 dark:hover:bg-neutral-700/30">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100">{{ $location->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-neutral-300">{{ Str::limit($location->address, 70) ?: 'N/D' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                            @if($location->is_active)
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200">
                                    {{__('Ativo')}}
                                </span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200">
                                    {{__('Inativo')}}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            <a href="{{ route('boarding-locations.edit', $location->id) }}" wire:navigate title="{{__('Editar Local')}}"
                               class="inline-flex items-center justify-center p-1.5 rounded-full text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-neutral-600">
                                <span class="icon-[tabler--pencil] w-5 h-5"></span>
                            </a>
                            @if($location->is_active) {{-- Só mostra "desativar" se estiver ativo --}}
                            <button wire:click="openDeleteModal({{ $location->id }})" title="{{__('Desativar Local')}}"
                                    class="inline-flex items-center justify-center p-1.5 rounded-full text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-neutral-600">
                                <span class="icon-[mdi--cancel-bold] w-5 h-5"></span>
                            </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-16 text-center text-sm text-gray-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                <span class="icon-[mdi--map-marker-off-outline] text-6xl text-gray-300 dark:text-neutral-600 mb-3"></span>
                                {{ __('Nenhum local de embarque cadastrado.') }}
                                <a href="{{ route('boarding-locations.create') }}" wire:navigate class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 font-medium">
                                    {{ __('Cadastrar Novo Local de Embarque') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($locations->hasPages())
            <div class="py-4 px-1">
                {{ $locations->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    {{-- Modal de Desativação --}}
    @if($showDeleteModal && $deletingLocation)
        <div class="fixed inset-0 z-[100] flex items-end justify-center px-4 py-6 pointer-events-none sm:items-center sm:p-6" aria-labelledby="modal-title-delete-bl" role="dialog" aria-modal="true">
            <div wire:click="closeDeleteModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity pointer-events-auto" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pointer-events-auto">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-delete-bl">{{ __('Confirmar Desativação') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 dark:text-neutral-300">
                                    {{ __('Você tem certeza que deseja desativar o local de embarque') }} <strong>"{{ $deletingLocation->name }}"</strong>?
                                    {{ __('Ele não poderá ser selecionado para novas solicitações, mas permanecerá no histórico.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="deleteLocation" type="button" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 disabled:opacity-50">
                        <span wire:loading.remove wire:target="deleteLocation">{{ __('Desativar') }}</span>
                        <svg wire:loading wire:target="deleteLocation" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="closeDeleteModal" type="button" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50">
                        {{ __('Cancelar') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
