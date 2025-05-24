<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-neutral-800 shadow-xl sm:rounded-lg">
            <div class="p-6 border-b dark:border-neutral-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100">{{ $pageTitle }}</h2>
                    <a href="{{ route('prescriptions.request.search') }}" wire:navigate class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        {{__('Alterar Cidadão')}}
                    </a>
                </div>
                <div class="mt-2 text-sm text-gray-600 dark:text-neutral-300">
                    <p><strong>{{__('CPF:')}}</strong> {{ $citizen->cpf ?: 'N/A' }}</p>
                    <p><strong>{{__('CNS:')}}</strong> {{ $citizen->cns ?: 'N/A' }}</p>
                    <p><strong>{{__('Nascimento:')}}</strong> {{ $citizen->date_of_birth ? \Carbon\Carbon::createFromFormat('d/m/Y', $citizen->date_of_birth)->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>

            <form wire:submit.prevent="submitPrescriptionRequest">
                <div class="p-6 space-y-6">
                    {{-- Detalhes da Solicitação --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="unit_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Unidade de Saúde Solicitante') }} <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select wire:model.blur="unit_id" id="unit_id" class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('unit_id') border-red-500 dark:border-red-500 @enderror" required>
                                    <option value="">{{ __('Selecione uma unidade') }}</option>
                                    @foreach($unitsList as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('unit_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="doctor_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Atribuir ao Médico (Opcional)') }}</label>
                            <div class="mt-2">
                                <select wire:model.blur="doctor_id" id="doctor_id" class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('doctor_id') border-red-500 dark:border-red-500 @enderror">
                                    <option value="">{{ __('Nenhum médico específico') }}</option>
                                    @foreach($doctorsList as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('doctor_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Medicamentos --}}
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 border-t dark:border-neutral-700 pt-4">{{__('Medicamentos/Itens da Receita')}}</h3>
                        @error('medications') <p class="mb-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                        @foreach($medications as $index => $medication)
                            <div wire:key="medication-{{ $index }}" class="p-4 border dark:border-neutral-700 rounded-md space-y-3 relative">
                                <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">{{__('Item')}} {{ $index + 1 }}</p>
                                <div>
                                    <label for="medications.{{ $index }}.name" class="block text-xs font-medium text-gray-700 dark:text-neutral-300">{{__('Nome do Medicamento/Item')}} <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.blur="medications.{{ $index }}.name" id="medications.{{ $index }}.name"
                                           class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('medications.'.$index.'.name') border-red-500 dark:border-red-500 @enderror">
                                    @error('medications.'.$index.'.name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="medications.{{ $index }}.dosage" class="block text-xs font-medium text-gray-700 dark:text-neutral-300">{{__('Dosagem/Forma')}} <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.blur="medications.{{ $index }}.dosage" id="medications.{{ $index }}.dosage" placeholder="Ex: 500mg Comprimido"
                                               class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('medications.'.$index.'.dosage') border-red-500 dark:border-red-500 @enderror">
                                        @error('medications.'.$index.'.dosage') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="medications.{{ $index }}.quantity" class="block text-xs font-medium text-gray-700 dark:text-neutral-300">{{__('Quantidade')}} <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.blur="medications.{{ $index }}.quantity" id="medications.{{ $index }}.quantity" placeholder="Ex: 1 caixa, 30 unidades"
                                               class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('medications.'.$index.'.quantity') border-red-500 dark:border-red-500 @enderror">
                                        @error('medications.'.$index.'.quantity') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div>
                                    <label for="medications.{{ $index }}.instructions" class="block text-xs font-medium text-gray-700 dark:text-neutral-300">{{__('Instruções de Uso (Posologia)')}}</label>
                                    <textarea wire:model.blur="medications.{{ $index }}.instructions" id="medications.{{ $index }}.instructions" rows="2" placeholder="Ex: Tomar 1 comprimido a cada 12 horas por 7 dias"
                                              class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('medications.'.$index.'.instructions') border-red-500 dark:border-red-500 @enderror"></textarea>
                                    @error('medications.'.$index.'.instructions') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                @if(count($medications) > 1)
                                    <button type="button" wire:click="removeMedicationItem({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-700/50" title="{{__('Remover Item')}}">
                                        <span class="icon-[mdi--trash-can-outline] w-5 h-5"></span>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                        <div class="flex justify-start">
                            <button type="button" wire:click="addMedicationItem"
                                    class="inline-flex items-center rounded-md border border-dashed border-gray-400 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-neutral-200 shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-1 dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--plus] w-4 h-4 mr-1.5"></span>
                                {{__('Adicionar Medicamento/Item')}}
                            </button>
                        </div>
                    </div>

                    {{-- Alternativa: Campo de texto único para detalhes da receita --}}
                    {{-- <div>
                        <label for="prescription_details_text" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Detalhes da Receita (Medicamentos, Dosagens, Instruções)') }}  <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <textarea wire:model.blur="prescription_details_text" id="prescription_details_text" rows="6" class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm @error('prescription_details_text') border-red-500 dark:border-red-500 @enderror" required></textarea>
                        </div>
                        @error('prescription_details_text') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div> --}}

                </div>

                <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-900/10 dark:border-neutral-100/10">
                    <a href="{{ route('prescriptions.index') }}" wire:navigate
                       class="rounded-md bg-white dark:bg-neutral-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex justify-center rounded-md bg-blue-600 dark:bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-sky-500">
                        <span wire:loading.remove class="icon-[mdi--send-check-outline] w-5 h-5 mr-1.5 -ml-0.5"></span>
                        <svg wire:loading class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading>{{__('Enviando...')}}</span>
                        <span wire:loading.remove>{{ __('Enviar Solicitação') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
