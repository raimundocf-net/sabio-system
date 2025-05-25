<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="my-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            {{-- Cabeçalho do Card --}}
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 inline-flex items-center gap-2">
                        <span class="icon-[mdi--clipboard-edit-outline] w-6 h-6 mr-2 rtl:mr-0 rtl:ml-2 inline-block text-indigo-600 dark:text-sky-500"></span>
                        {{ $pageTitle }}
                    </h2>
                    <a href="{{ route('travel-requests.index') }}" wire:navigate
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        <span class="icon-[icon-park-outline--back] w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1 inline-block"></span>
                        {{ __('Voltar para Lista de Solicitações') }}
                    </a>
                </div>
            </div>

            {{-- Formulário --}}
            <form wire:submit.prevent="save">
                <div class="p-4 sm:p-6 space-y-6">

                    {{-- Seção de Informações do Cidadão (Apenas Exibição) --}}
                    <fieldset class="border dark:border-neutral-700 p-4 rounded-md">
                        <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('1. Identificação do Paciente')}}</legend>
                        <div class="mt-4 space-y-1 text-sm">
                            <p class="text-gray-800 dark:text-neutral-200"><strong>{{__('Nome:')}}</strong> {{ $travelRequestInstance->citizen?->name }}</p>
                            <p class="text-gray-700 dark:text-neutral-300"><strong>{{__('CPF:')}}</strong> {{ $travelRequestInstance->citizen?->cpf ?? __('N/D') }}</p>
                            <p class="text-gray-700 dark:text-neutral-300"><strong>{{__('CNS:')}}</strong> {{ $travelRequestInstance->citizen?->cns ?? __('N/D') }}</p>
                            <p class="text-gray-700 dark:text-neutral-300"><strong>{{__('Nascimento:')}}</strong> {{ $travelRequestInstance->citizen?->date_of_birth ? \Carbon\Carbon::parse($travelRequestInstance->citizen->date_of_birth)->format('d/m/Y') : __('N/D') }}</p>
                        </div>
                    </fieldset>

                    {{-- Seção de Detalhes da Viagem (Campos Editáveis) --}}
                    <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-6">
                        <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('2. Detalhes da Viagem')}}</legend>
                        <div class="mt-4 space-y-5">
                            {{-- Incluir o mesmo _form-fields.blade.php usado na criação --}}
                            {{-- Certifique-se que os wire:model em _form-fields estão como "form.nomeDoCampo" --}}
                            {{-- Se você não criou um _form-fields.blade.php, copie os campos do travel-request-form-step.blade.php aqui --}}
                            @include('livewire.travel-requests._form-fields') {{-- ASSUMINDO QUE VOCÊ CRIARÁ ESTE PARCIAL --}}
                        </div>
                    </fieldset>

                    {{-- Seção Acompanhante e Passageiros (Campos Editáveis) --}}
                    <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-6">
                        <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('3. Acompanhante e Passageiros')}}</legend>
                        <div class="mt-4 space-y-5">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="needs_companion_edit" wire:model.live="form.needs_companion" type="checkbox"
                                           class="focus:ring-indigo-500 dark:focus:ring-sky-500 h-4 w-4 text-indigo-600 dark:text-sky-500 border-gray-300 dark:border-neutral-600 rounded bg-white dark:bg-neutral-700 dark:checked:bg-sky-500">
                                </div>
                                <div class="ml-3 rtl:ml-0 rtl:mr-3 text-sm">
                                    <label for="needs_companion_edit" class="font-medium text-gray-700 dark:text-neutral-300">{{__('Precisa de Acompanhante?')}}</label>
                                </div>
                            </div>

                            @if($form['needs_companion'])
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pl-2 sm:pl-0">
                                    <div>
                                        <label for="companion_name_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Nome do Acompanhante')}} <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="form.companion_name" id="companion_name_edit"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.companion_name') border-red-500 dark:border-red-400 @enderror">
                                        @error('form.companion_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="companion_cpf_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('CPF do Acompanhante')}}</label>
                                        <input type="text" wire:model.defer="form.companion_cpf" id="companion_cpf_edit" placeholder="000.000.000-00"
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.companion_cpf') border-red-500 dark:border-red-400 @enderror">
                                        @error('form.companion_cpf') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            @endif
                            <div>
                                <label for="number_of_passengers_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Número Total de Passageiros')}} <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.defer="form.number_of_passengers" id="number_of_passengers_edit" min="1"
                                       class="mt-1 block w-full sm:w-1/3 rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.number_of_passengers') border-red-500 dark:border-red-400 @enderror"
                                       @if(!$form['needs_companion']) readonly @endif>
                                @error('form.number_of_passengers') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- Seção de Documentação e Observações (Campos Editáveis) --}}
                    <fieldset class="border dark:border-neutral-700 p-4 rounded-md mt-6">
                        <legend class="text-md font-semibold text-gray-700 dark:text-neutral-200 px-2">{{__('4. Documentação e Observações')}}</legend>
                        <div class="mt-4 space-y-5">
                            <div>
                                <label for="referralDocumentFile_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                                    {{ $form['referral_document_path'] ? __('Alterar Foto da Guia/Encaminhamento') : __('Anexar Foto da Guia/Encaminhamento') }}
                                </label>
                                <input type="file" wire:model="referralDocumentFile" id="referralDocumentFile_edit"
                                       class="mt-1 block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4 file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200 hover:file:bg-indigo-200 dark:hover:file:bg-sky-600 @error('referralDocumentFile') border-red-500 dark:border-red-400 @enderror">
                                <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                    <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                    {{__('Carregando nova imagem...')}}
                                </div>
                                @error('referralDocumentFile') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                                {{-- Preview da imagem nova ou da existente --}}
                                @if ($referralDocumentFile && method_exists($referralDocumentFile, 'temporaryUrl'))
                                    <div class="mt-2">
                                        <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Pré-visualização da Nova Imagem:')}}</p>
                                        <img src="{{ $referralDocumentFile->temporaryUrl() }}" alt="{{__('Preview da nova guia')}}" class="max-h-40 w-auto rounded border dark:border-neutral-600">
                                    </div>
                                @elseif ($form['referral_document_path'])
                                    <div class="mt-2">
                                        <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Guia Anexada:')}}</p>
                                        <a href="{{ Storage::url($form['referral_document_path']) }}" target="_blank">
                                            <img src="{{ Storage::url($form['referral_document_path']) }}" alt="{{__('Preview da guia anexada')}}" class="max-h-40 w-auto rounded border dark:border-neutral-600 hover:ring-2 hover:ring-indigo-500 dark:hover:ring-sky-500">
                                        </a>
                                        @can('update', $travelRequestInstance) {{-- Ou uma policy mais específica para remover imagem --}}
                                        <button type="button" wire:click="removeReferralDocument" wire:confirm="{{__('Tem certeza que deseja remover a imagem da guia?')}}"
                                                class="mt-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <span class="icon-[mdi--delete-outline] w-4 h-4 mr-1"></span>
                                            {{__('Remover Imagem')}}
                                        </button>
                                        @endcan
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label for="observations_edit" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Observações Gerais da Atendente')}}</label>
                                <textarea wire:model.defer="form.observations" id="observations_edit" rows="3" placeholder="{{__('Informações adicionais...')}}"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.observations') border-red-500 dark:border-red-400 @enderror"></textarea>
                                @error('form.observations') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>
                </div>

                {{-- Botões de Ação --}}
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
                            <span class="icon-[mdi--content-save-edit-outline] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                            {{ __('Atualizar Solicitação') }}
                        </span>
                        <span wire:loading wire:target="save">{{ __('Atualizando...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
