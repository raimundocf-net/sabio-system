<div>
    {{-- Título da Página para o Slot do Layout --}}
    <x-slot:title>
        {{ $pageTitle ?? ($isEditing ? __('Editar Veículo') : __('Novo Veículo')) }}
    </x-slot:title>

    <div class="my-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            {{-- Cabeçalho do Card --}}
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 inline-flex items-center gap-2">
                        {{-- Ícone para formulário de veículo: ph:car-profile-fill ou mdi:car-edit --}}
                        <span class="icon-[ph--car-profile-fill] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                        {{ $pageTitle }}
                    </h2>
                    <a href="{{ route('vehicles.index') }}" wire:navigate
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        {{-- Ícone para voltar: mdi:arrow-left ou icon-park-outline:back --}}
                        <span class="icon-[icon-park-outline--back] w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1 inline-block"></span>
                        {{ __('Voltar para Lista de Veículos') }}
                    </a>
                </div>
            </div>

            {{-- Formulário --}}
            <form wire:submit.prevent="save">
                <div class="p-4 sm:p-6 space-y-5">
                    {{-- Linha 1: Placa, Marca, Modelo --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Placa')}} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.plate_number" id="plate_number" placeholder="AAA0A00 ou ABC1234"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.plate_number') border-red-500 dark:border-red-400 @enderror">
                            @error('form.plate_number') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Marca')}} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.brand" id="brand" placeholder="{{__('Ex: Fiat')}}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.brand') border-red-500 dark:border-red-400 @enderror">
                            @error('form.brand') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Modelo')}} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.model" id="model" placeholder="{{__('Ex: Cronos, Sprinter')}}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.model') border-red-500 dark:border-red-400 @enderror">
                            @error('form.model') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Linha 2: Ano Fabricação, Ano Modelo, Capacidade Passageiros --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="year_of_manufacture" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Ano Fabric.')}} <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.defer="form.year_of_manufacture" id="year_of_manufacture" placeholder="AAAA" min="1900" max="{{ date('Y') + 1 }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.year_of_manufacture') border-red-500 dark:border-red-400 @enderror">
                            @error('form.year_of_manufacture') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="model_year" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Ano Modelo')}}</label>
                            <input type="number" wire:model.defer="form.model_year" id="model_year" placeholder="AAAA" min="1900" max="{{ date('Y') + 2 }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.model_year') border-red-500 dark:border-red-400 @enderror">
                            @error('form.model_year') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="passenger_capacity" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Capacidade Passageiros')}} <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.defer="form.passenger_capacity" id="passenger_capacity" placeholder="{{__('Ex: 5')}}" min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.passenger_capacity') border-red-500 dark:border-red-400 @enderror">
                            @error('form.passenger_capacity') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Linha 3: RENAVAM, Chassi, Cor --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="renavam" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">RENAVAM <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.renavam" id="renavam" placeholder="{{__('9 ou 11 dígitos')}}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.renavam') border-red-500 dark:border-red-400 @enderror">
                            @error('form.renavam') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="chassis" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Chassi')}} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.chassis" id="chassis" placeholder="{{__('17 caracteres')}}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.chassis') border-red-500 dark:border-red-400 @enderror">
                            @error('form.chassis') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Cor')}}</label>
                            <input type="text" wire:model.defer="form.color" id="color" placeholder="{{__('Ex: Prata')}}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.color') border-red-500 dark:border-red-400 @enderror">
                            @error('form.color') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Linha 4: Tipo Veículo, Status Disponibilidade --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Tipo do Veículo')}} <span class="text-red-500">*</span></label>
                            <select wire:model.defer="form.type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.type') border-red-500 dark:border-red-400 @enderror">
                                <option value="">{{__('Selecione o Tipo...')}}</option>
                                @foreach($vehicleTypeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('form.type') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="availability_status" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Status de Disponibilidade')}} <span class="text-red-500">*</span></label>
                            <select wire:model.defer="form.availability_status" id="availability_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.availability_status') border-red-500 dark:border-red-400 @enderror">
                                <option value="">{{__('Selecione o Status...')}}</option>
                                @foreach($availabilityStatusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('form.availability_status') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Linha 5: Data Aquisição, KM Atual, Data Última Inspeção --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Data de Aquisição')}}</label>
                            <input type="date" wire:model.defer="form.acquisition_date" id="acquisition_date"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.acquisition_date') border-red-500 dark:border-red-400 @enderror">
                            @error('form.acquisition_date') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="current_mileage" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('KM Atual')}}</label>
                            <input type="number" wire:model.defer="form.current_mileage" id="current_mileage" placeholder="{{__('Ex: 120500')}}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.current_mileage') border-red-500 dark:border-red-400 @enderror">
                            @error('form.current_mileage') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_inspection_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Última Revisão')}}</label>
                            <input type="date" wire:model.defer="form.last_inspection_date" id="last_inspection_date"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.last_inspection_date') border-red-500 dark:border-red-400 @enderror">
                            @error('form.last_inspection_date') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Linha 6: Adaptado PNE (Checkbox) --}}
                    <div class="pt-2">
                        <label for="is_pwd_accessible" class="flex items-center">
                            <input type="checkbox" wire:model.defer="form.is_pwd_accessible" id="is_pwd_accessible"
                                   class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-neutral-500 rounded focus:ring-indigo-500 dark:focus:ring-sky-500 bg-white dark:bg-neutral-700 dark:checked:bg-sky-500 dark:checked:border-sky-500">
                            <span class="ml-2 rtl:ml-0 rtl:mr-2 text-sm text-gray-700 dark:text-neutral-300">{{__('Veículo adaptado para PNE (Pessoa com Necessidades Especiais)')}}</span>
                        </label>
                        @error('form.is_pwd_accessible') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Observações --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Observações')}}</label>
                        <div class="mt-2">
                            <textarea wire:model.defer="form.notes" id="notes" rows="3" placeholder="{{__('Detalhes adicionais, histórico de problemas, etc...')}}"
                                      class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.notes') border-red-500 dark:border-red-400 @enderror"></textarea>
                        </div>
                        @error('form.notes') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-200 dark:border-neutral-700">
                    <a href="{{ route('vehicles.index') }}" wire:navigate
                       class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                        {{ __('Cancelar') }}
                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                        <span wire:loading wire:target="save" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                        <span wire:loading.remove wire:target="save">
                            {{-- Ícone de salvar: mdi:content-save ou heroicons:check-circle --}}
                            <span class="icon-[mdi--content-save] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                            {{ $isEditing ? __('Atualizar Veículo') : __('Salvar Veículo') }}
                        </span>
                        <span wire:loading wire:target="save">
                            {{ $isEditing ? __('Atualizando...') : __('Salvando...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
