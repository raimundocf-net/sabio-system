<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white dark:bg-neutral-800 shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 mb-1">{{ __('Para Solicitar uma Viagem, Busque por um Cidadão') }}</h2>
                <p class="text-sm text-gray-600 dark:text-neutral-300 mb-6">{{__('Preencha um ou mais campos abaixo para encontrar o cidadão.')}}</p>

                <form wire:submit.prevent="searchCitizen" class="space-y-4">
                    <div>
                        <label for="search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Nome, CPF ou CNS do Cidadão') }}</label>
                        <div class="mt-2">
                            <input type="text" wire:model.defer="search" id="search" placeholder="{{__('Digite Nome, CPF ou CNS')}}"
                                   class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('search') border-red-500 dark:border-red-500 @enderror">
                        </div>
                        @error('search') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="searchMother" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Nome da Mãe (Opcional)') }}</label>
                        <div class="mt-2">
                            <input type="text" wire:model.defer="searchMother" id="searchMother" placeholder="{{__('Digite o nome da mãe para refinar a busca')}}"
                                   class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('searchMother') border-red-500 dark:border-red-500 @enderror">
                        </div>
                        @error('searchMother') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        {{-- Botão Cancelar volta para o Index de Travel Requests ou Dashboard --}}
                        <a href="{{ route('travel-requests.index') }}" wire:navigate
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                            <span class="icon-[mdi--cancel] w-4 h-4 mr-2"></span>
                            {{ __('Cancelar') }}
                        </a>
                        <button type="button" wire:click="clearSearch"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                            <span class="icon-[mdi--eraser] w-4 h-4 mr-2"></span>
                            {{ __('Limpar Busca') }}
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-50">
                            <span wire:loading.remove class="icon-[mdi--account-search-outline] w-5 h-5 mr-2"></span>
                            <svg wire:loading wire:target="searchCitizen" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading>{{__('Buscando...')}}</span>
                            <span wire:loading.remove>{{ __('Buscar Cidadão') }}</span>
                        </button>
                    </div>
                </form>

                @include('livewire.partials.session-messages')

                @if ($results && $results->isNotEmpty())
                    <div class="border-t dark:border-neutral-700 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-neutral-100">{{ __('Cidadãos Encontrados:') }} ({{ $results->count() }})</h3>
                        {{-- Usar uma tabela para resultados, para consistência --}}
                        <div class="mt-3 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-700">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-neutral-100 sm:pl-0">{{__('Nome')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-100">{{__('CPF')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-100">{{__('CNS')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-100">{{__('Mãe')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-neutral-100">{{__('Nascimento')}}</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0"><span class="sr-only">Selecionar</span></th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-800">
                                        @foreach ($results as $citizen)
                                            <tr wire:key="citizen-{{$citizen->id}}">
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-neutral-100 sm:pl-0">{{ $citizen->name }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-neutral-300">{{ $citizen->cpf ?: '-'}}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-neutral-300">{{ $citizen->cns ?: '-' }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-neutral-300">{{ $citizen->name_mother ?: '-' }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-neutral-300">{{ $citizen->date_of_birth ? \Carbon\Carbon::parse($citizen->date_of_birth)->format('d/m/Y') : '-' }}</td>
                                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                    {{-- ROTA ALTERADA para o formulário de solicitação de viagem --}}
                                                    <a href="{{ route('travel-requests.create.form', ['citizen' => $citizen->id]) }}" wire:navigate
                                                       class="inline-flex items-center justify-center shrink-0 w-full sm:w-auto px-3 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition"
                                                       title="{{__('Solicitar viagem para este cidadão')}}">
                                                        <span class="icon-[mdi--car-arrow-right] w-4 h-4 mr-1.5"></span>
                                                        {{__('Selecionar e Continuar')}}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($results && $results->isEmpty() && (!empty($search) || !empty($searchMother)))
                    <div class="border-t dark:border-neutral-700 pt-6 mt-6">
                        <p class="text-center text-orange-600 dark:text-orange-400">{{ __('Nenhum cidadão encontrado com os critérios fornecidos.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
