<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-neutral-100 inline-flex items-center gap-3">
                            {{-- Ícone para acompanhante: mdi:account-heart-outline ou mdi:account-multiple-plus-outline --}}
                            <span class="icon-[mdi--account-plus-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                            {{ $pageTitle }}
                            @if($isEditing && $companionInstance)
                                <span class="text-indigo-500 dark:text-sky-400 text-base truncate max-w-xs" title="{{ $companionInstance->full_name }}">
                                    - {{ Str::limit($companionInstance->full_name, 30) }}
                                </span>
                            @endif
                        </h1>
                        <a href="{{ route('companions.index') }}" wire:navigate
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 inline-flex items-center gap-1.5">
                            <span class="icon-[mdi--arrow-left] w-4 h-4"></span>
                            {{ __('Voltar para Lista') }}
                        </a>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Nome Completo --}}
                        <div>
                            <label for="full_name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Nome Completo')}} <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" wire:model.defer="full_name" id="full_name"
                                       class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('full_name') ring-red-500 dark:ring-red-400 @enderror">
                            </div>
                            @error('full_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- CPF e Documento de Identidade --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="cpf" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('CPF')}}</label>
                                <div class="mt-2">
                                    <input type="text" wire:model.defer="cpf" id="cpf" placeholder="000.000.000-00"
                                           x-data x-mask="999.999.999-99" {{-- AlpineJS Mask --}}
                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('cpf') ring-red-500 dark:ring-red-400 @enderror">
                                </div>
                                @error('cpf') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="identity_document" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Documento de Identidade (RG)')}}</label>
                                <div class="mt-2">
                                    <input type="text" wire:model.defer="identity_document" id="identity_document" placeholder="Ex: MG-12.345.678"
                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('identity_document') ring-red-500 dark:ring-red-400 @enderror">
                                </div>
                                @error('identity_document') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Telefone de Contato --}}
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Telefone de Contato')}}</label>
                            <div class="mt-2">
                                <input type="tel" wire:model.defer="contact_phone" id="contact_phone" placeholder="(DD) 9XXXX-XXXX"
                                       x-data x-mask="(99) 9999-9999"
                                       class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('contact_phone') ring-red-500 dark:ring-red-400 @enderror">
                            </div>
                            @error('contact_phone') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Observações --}}
                        <div>
                            <label for="notes" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Observações')}}</label>
                            <div class="mt-2">
                                <textarea wire:model.defer="notes" id="notes" rows="3" placeholder="{{__('Qualquer informação adicional relevante sobre o acompanhante...')}}"
                                          class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('notes') ring-red-500 dark:ring-red-400 @enderror"></textarea>
                            </div>
                            @error('notes') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-100 dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700 flex items-center justify-end gap-x-4">
                        <button type="button" wire:click="cancel"
                                class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70 transition-colors">
                            <span wire:loading.remove wire:target="save">
                                <span class="icon-[mdi--content-save{{ $isEditing ? '-edit' : '' }}-outline] w-5 h-5 mr-1.5"></span>
                                {{ $isEditing ? __('Atualizar Acompanhante') : __('Salvar Acompanhante') }}
                            </span>
                            <span wire:loading wire:target="save" class="inline-flex items-center">
                                <span class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5"></span>
                                {{ $isEditing ? __('Atualizando...') : __('Salvando...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
