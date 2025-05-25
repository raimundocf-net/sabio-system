<div>
    <x-slot:title>
        {{ $pageTitle }} {{-- Deverá ser "Editar Local de Embarque" --}}
    </x-slot:title>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-100 inline-flex items-center gap-3">
                            <span class="icon-[mdi--map-marker-edit-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                            {{ $pageTitle }}: <span class="text-indigo-500 dark:text-sky-400">{{ $name }}</span>
                        </h1>
                        <a href="{{ route('boarding-locations.index') }}" wire:navigate
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 inline-flex items-center gap-1.5">
                            <span class="icon-[mdi--arrow-left] w-4 h-4"></span>
                            {{ __('Voltar para Lista') }}
                        </a>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 sm:p-8 space-y-6">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Nome do Local')}} <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" wire:model.defer="name" id="edit_name"
                                       class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('name') ring-red-500 dark:ring-red-400 @enderror">
                            </div>
                            @error('name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="edit_address" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Endereço/Ponto de Referência (Opcional)')}}</label>
                            <div class="mt-2">
                                <textarea wire:model.defer="address" id="edit_address" rows="3"
                                          class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('address') ring-red-500 dark:ring-red-400 @enderror"></textarea>
                            </div>
                            @error('address') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="edit_is_active" wire:model.defer="is_active" type="checkbox"
                                       class="h-4 w-4 rounded border-gray-300 dark:border-neutral-500 text-indigo-600 dark:text-sky-500 focus:ring-indigo-600 dark:focus:ring-sky-500 bg-white dark:bg-neutral-700 dark:checked:bg-sky-500 dark:checked:border-sky-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="edit_is_active" class="font-medium text-gray-900 dark:text-neutral-200">{{__('Ativo?')}}</label>
                                <p class="text-xs text-gray-500 dark:text-neutral-400">{{__('Locais inativos não aparecerão para seleção.')}}</p>
                            </div>
                        </div>
                        @error('is_active') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-6 py-4 bg-gray-100 dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700 flex items-center justify-end gap-x-4">
                        <a href="{{ route('boarding-locations.index') }}" wire:navigate
                           class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70 transition-colors">
                            <span wire:loading.remove wire:target="save">
                                <span class="icon-[mdi--content-save-edit] w-5 h-5 mr-1.5"></span>
                                {{ __('Atualizar Local') }}
                            </span>
                            <span wire:loading wire:target="save" class="inline-flex items-center">
                                <span class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5"></span>
                                {{ __('Atualizando...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
