{{-- Dentro da sua view: resources/views/livewire/units/manage-unit.blade.php --}}
<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                {{ $pageTitle }}
            </h1>
        </div>

        {{-- Card do Formulário com melhor padding e estrutura --}}
        <div class="bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <form wire:submit.prevent="save">
                {{-- Corpo do Card/Formulário com padding e espaçamento entre os campos --}}
                <div class="p-6 space-y-6">
                    {{-- Campo Nome --}}
                    <div>
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Nome da Unidade') }}</label>
                        <div class="mt-2">
                            <input type="text" wire:model.blur="name" id="name"
                                   class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('name') ring-red-500 dark:ring-red-500 @enderror"
                                   required>
                        </div>
                        @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo Município --}}
                    <div>
                        <label for="municipality" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Município') }}</label>
                        <div class="mt-2">
                            <input type="text" wire:model.blur="municipality" id="municipality"
                                   class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('municipality') ring-red-500 dark:ring-red-500 @enderror"
                                   required>
                        </div>
                        @error('municipality') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo CNES --}}
                    <div>
                        <label for="cnes" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('CNES') }}</label>
                        <div class="mt-2">
                            <input type="text" wire:model.blur="cnes" id="cnes"
                                   class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('cnes') ring-red-500 dark:ring-red-500 @enderror"
                                   required>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">{{ __('Cadastro Nacional de Estabelecimentos de Saúde (geralmente 7 dígitos).') }}</p>
                        @error('cnes') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Rodapé do Card/Formulário com Ações --}}
                <div class="flex items-center justify-end space-x-3 bg-gray-50 dark:bg-neutral-800/50 px-6 py-4 border-t border-gray-200 dark:border-neutral-700 sm:rounded-b-lg">
                    <button type="button" wire:click="cancel"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                        <span class="icon-[mdi--cancel] w-4 h-4 mr-2"></span>
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-xs font-semibold text-white uppercase tracking-widest shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-25">
                        <span class="icon-[mdi--content-save] w-4 h-4 mr-2"></span>
                        {{ $isEditing ? __('Salvar Alterações') : __('Criar Unidade') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
