<div>
    <x-slot:title>
        {{-- $pageTitle será definido no EditTravelRequest.php, ex: "Editar Solicitação #123 - Paciente XYZ" --}}
        {{ $pageTitle }}
    </x-slot:title>

    {{-- Container principal do formulário --}}
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">

                {{-- Cabeçalho do Formulário --}}
                <div class="px-6 py-5 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-100 inline-flex items-center gap-3">
                            {{-- Ícone pode ser diferente para edição, se desejar, ex: mdi--clipboard-edit-outline --}}
                            <span class="icon-[mdi--clipboard-edit-outline] w-7 h-7 text-indigo-600 dark:text-sky-500"></span>
                            {{ $pageTitle }}
                        </h1>
                        {{-- Botão para voltar ao índice de solicitações de viagem --}}
                        <a href="{{ route('travel-requests.index') }}" wire:navigate
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 inline-flex items-center gap-1.5 transition-colors duration-150">
                            <span class="icon-[mdi--arrow-left-circle-outline] w-5 h-5"></span>
                            {{ __('Voltar para Lista') }}
                        </a>
                    </div>
                </div>

                {{-- Formulário --}}
                {{-- O método no componente EditTravelRequest.php provavelmente será updateUser() ou um nome similar --}}
                <form wire:submit.prevent="updateTravelRequest">
                    <div class="p-6 sm:p-8 space-y-8">

                        {{-- Seção de Informações do Cidadão (não editável, apenas para contexto) --}}
                        {{-- A variável $travelRequest e sua relação $travelRequest->citizen devem ser populadas no EditTravelRequest.php --}}
                        @if($travelRequest && $travelRequest->citizen)
                            <section aria-labelledby="patient-info-heading" class="bg-slate-50 dark:bg-neutral-700/30 p-5 rounded-md border border-slate-200 dark:border-neutral-700">
                                <h2 id="patient-info-heading" class="text-lg font-semibold text-gray-800 dark:text-neutral-100 mb-3">
                                    {{__('1. Paciente')}}
                                </h2>
                                <div class="text-sm">
                                    <p class="text-gray-700 dark:text-neutral-200">
                                        <strong class="font-medium">{{__('Nome:')}}</strong> {{ $travelRequest->citizen->nome_do_cidadao }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                        CPF: {{ $travelRequest->citizen->cpf ?? __('N/D') }} |
                                        CNS: {{ $travelRequest->citizen->cns ?? __('N/D') }} |
                                        Idade: {{ $travelRequest->citizen->idade ? : __('N/D') }}
                                    </p>
                                </div>
                                {{-- Erro de citizen_id não deve ocorrer na edição se o campo não for editável --}}
                                {{-- @error('form.citizen_id')
                                <p class="mt-2 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror --}}
                            </section>
                        @else
                            <div class="p-4 border-l-4 border-red-500 bg-red-100 dark:bg-red-800/40 text-red-700 dark:text-red-300 rounded-md" role="alert">
                                <p class="font-medium">{{ __('Informações do cidadão não disponíveis ou inválidas para esta solicitação.') }}</p>
                            </div>
                        @endif

                        {{-- Seções do Formulário de Viagem (Reutilizando a mesma estrutura) --}}
                        {{-- Os valores dos campos em $form serão preenchidos pelo método mount() do EditTravelRequest.php --}}
                        @if($travelRequest && $travelRequest->citizen)
                            {{-- Detalhes da Viagem --}}
                            <section aria-labelledby="trip-details-heading" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="trip-details-heading" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    {{__('2. Detalhes da Viagem')}}
                                </h2>
                                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                    {{-- Motivo/Propósito --}}
                                    <div class="sm:col-span-6">
                                        <label for="edit_form_reason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Motivo/Propósito da Viagem')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <textarea wire:model.defer="form.reason" id="edit_form_reason" rows="3" placeholder="{{__('Ex: Consulta com especialista, Exame de alta complexidade...')}}"
                                                      class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.reason') ring-red-500 dark:ring-red-400 @enderror"></textarea>
                                        </div>
                                        @error('form.reason') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Tipo de Procedimento --}}
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_procedure_type" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Tipo de Procedimento')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select wire:model.defer="form.procedure_type" id="edit_form_procedure_type"
                                                    class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.procedure_type') ring-red-500 dark:ring-red-400 @enderror">
                                                <option value="">{{__('Selecione...')}}</option>
                                                @foreach($procedureTypeOptions as $value => $label) {{-- $procedureTypeOptions vem do EditTravelRequest.php --}}
                                                <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('form.procedure_type') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Local de Embarque --}}
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_departure_location" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Local de Embarque')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2 flex items-center gap-x-2">
                                            <select wire:model="form.departure_location" id="edit_form_departure_location" {{-- Removido .defer para atualização imediata ao selecionar novo item --}}
                                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.departure_location') ring-red-500 dark:ring-red-400 @enderror">
                                                <option value="">{{__('Selecione um local...')}}</option>
                                                @foreach($boardingLocations as $location) {{-- $boardingLocations vem do EditTravelRequest.php --}}
                                                <option value="{{ $location->name }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" wire:click="openAddBoardingLocationModal"
                                                    class="shrink-0 inline-flex items-center justify-center p-2 rounded-md bg-indigo-600 dark:bg-sky-500 text-white hover:bg-indigo-700 dark:hover:bg-sky-400 transition-colors"
                                                    title="{{__('Adicionar Novo Local de Embarque')}}">
                                                <span class="icon-[mdi--plus] w-5 h-5"></span>
                                            </button>
                                        </div>
                                        @error('form.departure_location') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Endereço de Destino --}}
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_destination_address" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Endereço de Destino')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" wire:model.defer="form.destination_address" id="edit_form_destination_address" placeholder="{{__('Rua, Número, Bairro, Complemento...')}}"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.destination_address') ring-red-500 dark:ring-red-400 @enderror">
                                        </div>
                                        @error('form.destination_address') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Cidade Destino --}}
                                    <div class="sm:col-span-2">
                                        <label for="edit_form_destination_city" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Cidade Destino')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" wire:model.defer="form.destination_city" id="edit_form_destination_city"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.destination_city') ring-red-500 dark:ring-red-400 @enderror">
                                        </div>
                                        @error('form.destination_city') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- UF Destino --}}
                                    <div class="sm:col-span-1">
                                        <label for="edit_form_destination_state" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('UF Destino')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select wire:model.defer="form.destination_state" id="edit_form_destination_state"
                                                    class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.destination_state') ring-red-500 dark:ring-red-400 @enderror">
                                                {{-- <option value="MG">{{__('MG')}}</option> --}} {{-- Removido default fixo, valor será do $form --}}
                                                <option value="">{{__('Selecione UF')}}</option>
                                                @foreach($stateOptions as $value => $label) {{-- $stateOptions vem do EditTravelRequest.php --}}
                                                <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('form.destination_state') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Previsão de Saída --}}
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_desired_departure_datetime" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Previsão de Saída da Origem')}}</label>
                                        <div class="mt-2">
                                            <input type="datetime-local" wire:model.defer="form.desired_departure_datetime" id="edit_form_desired_departure_datetime"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.desired_departure_datetime') ring-red-500 dark:ring-red-400 @enderror">
                                        </div>
                                        @error('form.desired_departure_datetime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Data/Hora Compromisso --}}
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_appointment_datetime" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Data/Hora do Compromisso no Destino')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="datetime-local" wire:model.defer="form.appointment_datetime" id="edit_form_appointment_datetime"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.appointment_datetime') ring-red-500 dark:ring-red-400 @enderror">
                                        </div>
                                        @error('form.appointment_datetime') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Acompanhante e Passageiros --}}
                            <section aria-labelledby="companion-details-heading-edit" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="companion-details-heading-edit" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    {{__('3. Acompanhante e Passageiros')}}
                                </h2>
                                <div class="space-y-6">
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input id="edit_needs_companion_form_input" wire:model.live="form.needs_companion" wire:change="$dispatch('updated-form-needs-companion', { value: $event.target.checked })" type="checkbox"
                                                   class="h-4 w-4 rounded border-gray-300 dark:border-neutral-500 text-indigo-600 dark:text-sky-500 focus:ring-indigo-600 dark:focus:ring-sky-500 bg-white dark:bg-neutral-700 dark:checked:bg-sky-500 dark:checked:border-sky-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="edit_needs_companion_form_input" class="font-medium text-gray-900 dark:text-neutral-200">{{__('Precisa de Acompanhante?')}}</label>
                                        </div>
                                    </div>

                                    @if($form['needs_companion'])
                                        <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                            <div class="sm:col-span-3">
                                                <label for="edit_companion_name_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Nome do Acompanhante')}} <span class="text-red-500">*</span></label>
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="form.companion_name" id="edit_companion_name_form_input"
                                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.companion_name') ring-red-500 dark:ring-red-400 @enderror">
                                                </div>
                                                @error('form.companion_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="sm:col-span-3">
                                                <label for="edit_companion_cpf_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('CPF do Acompanhante (Opcional)')}}</label>
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="form.companion_cpf" id="edit_companion_cpf_form_input" placeholder="000.000.000-00"
                                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.companion_cpf') ring-red-500 dark:ring-red-400 @enderror">
                                                </div>
                                                @error('form.companion_cpf') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    @endif
                                    <div class="sm:col-span-2"> {{-- Ajuste o sm:col-span conforme o layout desejado --}}
                                        <label for="edit_number_of_passengers_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Número Total de Passageiros')}} <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="number" wire:model.defer="form.number_of_passengers" id="edit_number_of_passengers_form_input" min="1"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.number_of_passengers') ring-red-500 dark:ring-red-400 @enderror"
                                                @readonly($form['needs_companion']) >
                                        </div>
                                        @error('form.number_of_passengers') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Documentação e Observações --}}
                            <section aria-labelledby="docs-obs-heading-edit" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="docs-obs-heading-edit" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    {{__('4. Documentação e Observações')}}
                                </h2>
                                <div class="space-y-6">
                                    {{-- Guia de Encaminhamento --}}
                                    <div>
                                        <label for="edit_referralDocumentFile_form" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            {{ ($form['referral_document_path'] ?? null) ? __('Alterar Foto da Guia/Encaminhamento') : __('Anexar Foto da Guia/Encaminhamento (Opcional)') }}
                                        </label>
                                        <div class="mt-2">
                                            <input type="file" wire:model="referralDocumentFile" id="edit_referralDocumentFile_form"
                                                   class="block w-full text-sm text-gray-900 dark:text-neutral-300 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 file:bg-gray-100 dark:file:bg-neutral-600 file:text-gray-700 dark:file:text-neutral-200 file:border-0 file:py-2.5 file:px-4 file:mr-4 dark:file:mr-0 dark:file:ml-4 hover:file:bg-gray-200 dark:hover:file:bg-neutral-500 @error('referralDocumentFile') border-red-500 dark:border-red-400 @enderror">
                                        </div>
                                        <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                            <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                            {{__('Carregando imagem...')}}
                                        </div>
                                        @error('referralDocumentFile') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                                        @if ($referralDocumentFile && method_exists($referralDocumentFile, 'temporaryUrl'))
                                            <div class="mt-3">
                                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Pré-visualização da Nova Imagem:')}}</p>
                                                <img src="{{ $referralDocumentFile->temporaryUrl() }}" alt="{{__('Preview da nova guia')}}" class="max-h-48 w-auto rounded border border-gray-300 dark:border-neutral-600 shadow-sm">
                                            </div>
                                        @elseif ($form['referral_document_path'] ?? null)
                                            <div class="mt-3">
                                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Guia Anexada Atualmente:')}}</p>
                                                <a href="{{ Storage::url($form['referral_document_path']) }}" target="_blank" class="inline-block">
                                                    <img src="{{ Storage::url($form['referral_document_path']) }}" alt="{{__('Preview da guia anexada')}}" class="max-h-48 w-auto rounded border border-gray-300 dark:border-neutral-600 shadow-sm hover:ring-2 hover:ring-indigo-500 dark:hover:ring-sky-500">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Observações --}}
                                    <div>
                                        <label for="edit_observations_form" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{__('Observações Gerais da Atendente')}}</label>
                                        <div class="mt-2">
                                            <textarea wire:model.defer="form.observations" id="edit_observations_form" rows="4" placeholder="{{__('Informações adicionais sobre a necessidade da viagem, restrições do paciente, etc...')}}"
                                                      class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 @error('form.observations') ring-red-500 dark:ring-red-400 @enderror"></textarea>
                                        </div>
                                        @error('form.observations') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </section>

                            {{-- Seção Adicional para Gerenciamento da Viagem (Status, etc. - REPETIDO DO EXEMPLO ANTERIOR) --}}
                            {{-- Esta seção é específica para a edição --}}
                            <section aria-labelledby="management-heading-edit" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="management-heading-edit" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    {{__('5. Gerenciamento da Viagem')}}
                                </h2>
                                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <label for="edit_form_status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Status da Solicitação') }}</label>
                                        <div class="mt-2">
                                            <select wire:model.defer="form.status" id="edit_form_status"
                                                    class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('form.status') ring-red-500 dark:ring-red-500 @enderror">
                                                <option value="">{{ __('Selecione o status') }}</option>
                                                @if(!empty($statusOptions)) {{-- $statusOptions vem do EditTravelRequest.php --}}
                                                @foreach($statusOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('form.status') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Adicione aqui outros campos de gerenciamento se necessário: motorista, veículo, etc. --}}
                                </div>
                            </section>
                        @endif
                    </div>

                    @if($travelRequest && $travelRequest->citizen) {{-- Só mostra os botões se houver dados para salvar --}}
                    <div class="px-6 py-4 bg-gray-100 dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700 flex items-center justify-end gap-x-4">
                        <a href="{{ route('travel-requests.index') }}" wire:navigate
                           class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <span class="icon-[mdi--cancel] w-4 h-4 mr-1.5 rtl:mr-0 rtl:ml-1.5 inline-block align-middle"></span>
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="updateTravelRequest, referralDocumentFile"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70 transition-colors">
                                <span wire:loading.remove wire:target="updateTravelRequest, referralDocumentFile">
                                    <span class="icon-[mdi--content-save-edit-outline] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                                    {{ __('Salvar Alterações') }}
                                </span>
                            <span wire:loading wire:target="updateTravelRequest, referralDocumentFile" class="inline-flex items-center">
                                    <span class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                    {{ __('Salvando...') }}
                                </span>
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL PARA ADICIONAR NOVO LOCAL DE EMBARQUE (reutilizado) --}}
    @if($showAddBoardingLocationModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
             x-data="{ show: @entangle('showAddBoardingLocationModal') }"
             x-show="show"
             x-trap.noscroll="show"
             x-on:keydown.escape.window="show = false"
             style="display: none;">
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" wire:click="closeAddBoardingLocationModal"></div>
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl transform transition-all sm:max-w-lg w-full p-6 space-y-4"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-neutral-100">{{__('Adicionar Novo Local de Embarque')}}</h3>
                <div>
                    <label for="newBoardingLocationName_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Nome do Local')}} <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="newBoardingLocationName" id="newBoardingLocationName_edit"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('newBoardingLocationName') border-red-500 dark:border-red-400 @enderror">
                    @error('newBoardingLocationName') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="newBoardingLocationAddress_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Endereço/Ponto de Referência (Opcional)')}}</label>
                    <textarea wire:model.defer="newBoardingLocationAddress" id="newBoardingLocationAddress_edit" rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('newBoardingLocationAddress') border-red-500 dark:border-red-400 @enderror"></textarea>
                    @error('newBoardingLocationAddress') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end space-x-3 pt-3">
                    <button type="button" wire:click="closeAddBoardingLocationModal"
                            class="rounded-md bg-white dark:bg-neutral-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="button" wire:click="saveNewBoardingLocation" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                        <span wire:loading wire:target="saveNewBoardingLocation" class="icon-[svg-spinners--6-dots-scale-middle] w-4 h-4 mr-1.5"></span>
                        <span wire:loading.remove wire:target="saveNewBoardingLocation">{{ __('Salvar Local') }}</span>
                        <span wire:loading wire:target="saveNewBoardingLocation">{{ __('Salvando...') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    {{-- FIM DO MODAL --}}
</div>
