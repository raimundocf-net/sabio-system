<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="my-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 inline-flex items-center gap-2">
                        <span class="icon-[mdi--clipboard-text-clock-outline] w-6 h-6 mr-2 rtl:mr-0 rtl:ml-2 inline-block text-indigo-600 dark:text-sky-500"></span>
                        {{ $pageTitle }}
                    </h2>
                    {{-- Link para voltar para a etapa de busca ou para o index --}}
                    <a href="{{ route('travel-requests.create.search-citizen') }}" wire:navigate
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        <span class="icon-[mdi--account-search-outline] w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1 inline-block"></span>
                        {{ __('Alterar Paciente / Nova Busca') }}
                    </a>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="p-4 sm:p-6 space-y-6">
                    {{-- Informações do Cidadão Selecionado (Apenas Exibição) --}}
                    @if($selectedCitizen)
                        <fieldset class="border dark:border-neutral-700 p-4 rounded-md">
                            <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('1. Paciente Selecionado')}}</legend>
                            <div class="mt-2 space-y-1 text-sm">
                                <p class="text-gray-800 dark:text-neutral-100"><strong>{{__('Nome:')}}</strong> {{ $selectedCitizen->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-neutral-300">CPF: {{ $selectedCitizen->cpf ?? __('N/D') }} | CNS: {{ $selectedCitizen->cns ?? __('N/D') }} | Nasc: {{ $selectedCitizen->date_of_birth ? \Carbon\Carbon::parse($selectedCitizen->date_of_birth)->format('d/m/Y') : __('N/D') }}</p>
                            </div>
                        </fieldset>
                    @else
                        <div class="p-4 border border-red-300 bg-red-50 dark:bg-red-800/30 dark:border-red-600 rounded-md text-red-700 dark:text-red-300">
                            {{ __('Nenhum cidadão selecionado. Por favor, volte e selecione um cidadão.') }}
                        </div>
                    @endif

                    {{-- Seções do Formulário de Viagem --}}
                    @if($selectedCitizen)
                        <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-4">
                            <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('2. Detalhes da Viagem')}}</legend>
                            <div class="mt-4 space-y-5">
                                @include('livewire.travel-requests._form-fields')
                            </div>
                        </fieldset>

                        <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-4">
                            <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('3. Acompanhante e Passageiros')}}</legend>
                            <div class="mt-4 space-y-5">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="needs_companion_form" wire:model.live="form.needs_companion" wire:change="$dispatch('updated-form-needs-companion', { value: $event.target.checked })" type="checkbox"
                                               class="focus:ring-indigo-500 dark:focus:ring-sky-500 h-4 w-4 text-indigo-600 dark:text-sky-500 border-gray-300 dark:border-neutral-600 rounded bg-white dark:bg-neutral-700 dark:checked:bg-sky-500">
                                    </div>
                                    <div class="ml-3 rtl:ml-0 rtl:mr-3 text-sm">
                                        <label for="needs_companion_form" class="font-medium text-gray-700 dark:text-neutral-300">{{__('Precisa de Acompanhante?')}}</label>
                                    </div>
                                </div>

                                @if($form['needs_companion'])
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pl-2 sm:pl-0">
                                        <div>
                                            <label for="companion_name_form" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Nome do Acompanhante')}} <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model.defer="form.companion_name" id="companion_name_form"
                                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.companion_name') border-red-500 dark:border-red-400 @enderror">
                                            @error('form.companion_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="companion_cpf_form" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('CPF do Acompanhante')}}</label>
                                            <input type="text" wire:model.defer="form.companion_cpf" id="companion_cpf_form" placeholder="000.000.000-00"
                                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.companion_cpf') border-red-500 dark:border-red-400 @enderror">
                                            @error('form.companion_cpf') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <label for="number_of_passengers_form" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Número Total de Passageiros')}} <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.defer="form.number_of_passengers" id="number_of_passengers_form" min="1"
                                           class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.number_of_passengers') border-red-500 dark:border-red-400 @enderror"
                                           @if(!$form['needs_companion']) readonly @endif >
                                    @error('form.number_of_passengers') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-4">
                            <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('4. Documentação e Observações')}}</legend>
                            <div class="mt-4 space-y-5">
                                @include('livewire.travel-requests._form-document-observations')
                            </div>
                        </fieldset>
                    @endif
                </div>

                @if($selectedCitizen)
                    <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-200 dark:border-neutral-700 rounded-b-lg">
                        <a href="{{ route('travel-requests.index') }}" wire:navigate
                           class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                            <span class="icon-[mdi--cancel] w-4 h-4 mr-1.5 rtl:mr-0 rtl:ml-1.5 inline-block align-middle"></span>
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                            <span wire:loading wire:target="save" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                            <span wire:loading.remove wire:target="save">
                                <span class="icon-[mdi--content-save-outline] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                                {{ __('Salvar Solicitação') }}
                            </span>
                            <span wire:loading wire:target="save">{{ __('Salvando...') }}</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
