{{-- Linha: Motivo e Tipo de Procedimento --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label for="form_reason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Motivo/Propósito da Viagem')}} <span class="text-red-500">*</span></label>
        <textarea wire:model.defer="form.reason" id="form_reason" rows="3" placeholder="{{__('Ex: Consulta com especialista, Exame de alta complexidade...')}}"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.reason') border-red-500 dark:border-red-400 @enderror"></textarea>
        @error('form.reason') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="form_procedure_type" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Tipo de Procedimento')}} <span class="text-red-500">*</span></label>
        <select wire:model.defer="form.procedure_type" id="form_procedure_type"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.procedure_type') border-red-500 dark:border-red-400 @enderror">
            <option value="">{{__('Selecione...')}}</option>
            @foreach($procedureTypeOptions as $value => $label) {{-- Supondo que $procedureTypeOptions está disponível na view que inclui este parcial --}}
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        @error('form.procedure_type') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Linha: Endereço, Cidade e Estado de Destino --}}
<div class="grid grid-cols-1 sm:grid-cols-5 gap-5">
    <div class="sm:col-span-3">
        <label for="form_destination_address" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Endereço de Destino')}} <span class="text-red-500">*</span></label>
        <input type="text" wire:model.defer="form.destination_address" id="form_destination_address" placeholder="{{__('Rua, Número, Bairro, Complemento...')}}"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.destination_address') border-red-500 dark:border-red-400 @enderror">
        @error('form.destination_address') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="form_destination_city" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Cidade Destino')}} <span class="text-red-500">*</span></label>
        <input type="text" wire:model.defer="form.destination_city" id="form_destination_city"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.destination_city') border-red-500 dark:border-red-400 @enderror">
        @error('form.destination_city') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="form_destination_state" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('UF Destino')}} <span class="text-red-500">*</span></label>
        <select wire:model.defer="form.destination_state" id="form_destination_state"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.destination_state') border-red-500 dark:border-red-400 @enderror">
            <option value="">{{__('UF')}}</option>
            @foreach($stateOptions as $value => $label) {{-- Supondo que $stateOptions está disponível --}}
            <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        @error('form.destination_state') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Linha: Local Embarque, Data/Hora Compromisso --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label for="form_departure_location" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Local de Embarque')}} <span class="text-red-500">*</span></label>
        <input type="text" wire:model.defer="form.departure_location" id="form_departure_location" placeholder="{{__('Ex: PSF Centro, Residência do Paciente')}}"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.departure_location') border-red-500 dark:border-red-400 @enderror">
        @error('form.departure_location') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="form_appointment_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Data/Hora do Compromisso')}} <span class="text-red-500">*</span></label>
        <input type="datetime-local" wire:model.defer="form.appointment_datetime" id="form_appointment_datetime"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.appointment_datetime') border-red-500 dark:border-red-400 @enderror">
        @error('form.appointment_datetime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Linha: Data/Hora Desejada Saída e Retorno --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label for="form_desired_departure_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Data/Hora Desejada de Saída')}}</label>
        <input type="datetime-local" wire:model.defer="form.desired_departure_datetime" id="form_desired_departure_datetime"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.desired_departure_datetime') border-red-500 dark:border-red-400 @enderror">
        @error('form.desired_departure_datetime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="form_desired_return_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Data/Hora Desejada de Retorno')}}</label>
        <input type="datetime-local" wire:model.defer="form.desired_return_datetime" id="form_desired_return_datetime"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.desired_return_datetime') border-red-500 dark:border-red-400 @enderror">
        @error('form.desired_return_datetime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</div>
