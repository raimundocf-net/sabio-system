<div>
    <x-slot:title>
        {{ $pageTitle }} {{-- Título da aba do navegador --}}
    </x-slot:title>

    {{-- Container Principal do Conteúdo --}}
    <div class="my-6 mx-auto px-2 sm:px-6 lg:px-8 max-w-4xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            {{-- Cabeçalho da Página (dentro do card) --}}
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100">
                        {{ $pageTitle }}
                    </h2>
                    <a href="{{ route('prescriptions.index') }}" wire:navigate {{-- Ajuste para sua rota de listagem --}}
                    class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        {{ __('Voltar para Lista') }}
                    </a>
                </div>
            </div>

            {{-- Seção de Detalhes do Cidadão e Solicitação Original --}}
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-100 mb-3">{{ __('Cidadão') }}</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('Nome:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->citizen?->name ?? $prescription->citizen?->nome_do_cidadao ?: 'N/A' }}</dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('CPF:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->citizen?->cpf ?: 'N/A' }}</dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('CNS:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->citizen?->cns ?: 'N/A' }}</dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('Nascimento:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->citizen?->date_of_birth ? \Carbon\Carbon::createFromFormat('d/m/Y', $prescription->citizen->date_of_birth)->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-100 mb-3">{{ __('Detalhes da Solicitação') }}</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('Unidade:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->unit?->name ?: 'N/A' }}</dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('Solicitante:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->requester?->name ?: 'N/A' }}</dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200">{{__('Solicitado em:')}}</dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300">{{ $prescription->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Conteúdo do Pedido Original (Read-only) --}}
                <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                    <dl>
                        <dt class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-1">{{__('Conteúdo do Pedido Original (ACS):')}}</dt>
                        <dd class="p-3 bg-gray-100 dark:bg-neutral-700/60 rounded-md max-h-40 overflow-y-auto custom-scrollbar text-sm text-gray-800 dark:text-neutral-200 whitespace-pre-wrap">
                            {{ $prescription->getOriginal('prescription_details') ?: __('Nenhum detalhe fornecido.') }}
                        </dd>
                    </dl>
                </div>

                {{-- Informações de Status e Histórico de Notas --}}
                <div class="mt-4 pt-4 border-t dark:border-neutral-700 space-y-2">
                    <div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-neutral-200">{{__('Status Atual:')}}</span>
                        <span class="ml-2 px-2.5 py-1 text-xs font-semibold rounded-full {{ $prescription->status->badgeClasses() }}">
                            {{ $prescription->status->label() }}
                        </span>
                    </div>
                    @if($prescription->doctor)
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200">{{__('Médico Responsável:')}}</span> {{ $prescription->doctor?->name }}</p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200">{{__('Médico Responsável:')}}</span> {{__('Nenhum atribuído')}}</p>
                    @endif
                    @if($prescription->reviewed_at)
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200">{{__('Analisado em:')}}</span> {{ $prescription->reviewed_at->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($prescription->completed_at)
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200">{{__('Finalizado em:')}}</span> {{ $prescription->completed_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
                @if($prescription->processing_notes)
                    <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-1">{{__('Histórico de Notas de Processamento:')}}</h4>
                        <div class="p-3 bg-gray-100 dark:bg-neutral-700/60 rounded-md max-h-32 overflow-y-auto custom-scrollbar">
                            <p class="text-xs text-gray-600 dark:text-neutral-300 whitespace-pre-wrap">{{ $prescription->processing_notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Seção de Edição e Ações --}}
            @if(!in_array($prescription->status, [\App\Enums\PrescriptionStatus::DELIVERED, \App\Enums\PrescriptionStatus::CANCELLED]))
                <div class="p-4 sm:p-6 space-y-6 border-t dark:border-neutral-700">

                    {{-- SEÇÃO PARA ACS EDITAR/CORRIGIR PRESCRIÇÃO --}}
                    @if(Auth::user() && Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id &&
                        in_array($prescription->status, [
                            \App\Enums\PrescriptionStatus::REQUESTED,
                            \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR
                        ])
                    )
                        @can('update', $prescription)
                            <div class="p-4 border rounded-lg shadow-sm
                                @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                    border-yellow-400 dark:border-yellow-600 bg-yellow-50 dark:bg-yellow-900/20
                                @else {{-- Estilo para status REQUESTED --}}
                                    border-blue-300 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20
                                @endif
                            ">
                                <h3 class="text-md font-semibold mb-2
                                    @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                        text-yellow-800 dark:text-yellow-300
                                    @else
                                        text-blue-800 dark:text-blue-300
                                    @endif
                                ">
                                    @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                        {{__('Corrigir e Reenviar Solicitação')}}
                                    @else {{-- Status is REQUESTED --}}
                                    {{__('Editar Conteúdo da Solicitação')}}
                                    @endif
                                </h3>

                                @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                    <p class="text-xs text-yellow-700 dark:text-yellow-200 mb-3">
                                        {{ __('Esta solicitação foi marcada como necessitando de correções. Por favor, ajuste os detalhes do pedido abaixo e reenvie para uma nova análise.')}}
                                    </p>
                                @else <p class="text-xs text-gray-600 dark:text-neutral-400 mb-3">
                                    {{ __('Você pode editar os detalhes do pedido enquanto o status for "Solicitada".')}}
                                </p>
                                @endif

                                <div class="space-y-4">
                                    <div>
                                        <label for="editablePrescriptionDetails" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            {{ __('Detalhes do Pedido') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-1">
                                            <textarea wire:model.defer="editablePrescriptionDetails" id="editablePrescriptionDetails" rows="6"
                                                      class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('editablePrescriptionDetails') border-red-500 dark:border-red-500 @enderror"
                                                      placeholder="{{ $prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR ? __('Descreva os medicamentos e instruções corrigidos aqui...') : __('Descreva os medicamentos e instruções aqui...') }}"
                                                      required></textarea>
                                            @error('editablePrescriptionDetails') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div> {{-- Nota sobre a edição/correção --}}
                                        <label for="editOrCorrectionReason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            {{ __('Observação sobre a Edição/Correção (Opcional)') }}
                                        </label>
                                        <div class="mt-1">
                                            <input type="text" wire:model.defer="editOrCorrectionReason" id="editOrCorrectionReason"
                                                   class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('editOrCorrectionReason') border-red-500 dark:border-red-500 @enderror"
                                                   placeholder="Ex: Dosagem ajustada conforme orientação.">
                                            @error('editOrCorrectionReason') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="savePrescriptionContentChanges" wire:loading.attr="disabled"
                                                class="inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                                                @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                                    bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 focus-visible:outline-green-600
                                                @else
                                                    bg-blue-600 hover:bg-blue-700 dark:bg-sky-500 dark:hover:bg-sky-400 focus-visible:outline-blue-600
                                                @endif">
                                            <span wire:loading wire:target="savePrescriptionContentChanges" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                            <span wire:loading.remove wire:target="savePrescriptionContentChanges">
                                                @if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR)
                                                    {{ __('Salvar Correções e Reenviar') }}
                                                @else
                                                    {{ __('Salvar Alterações no Conteúdo') }}
                                                @endif
                                            </span>
                                            <span wire:loading wire:target="savePrescriptionContentChanges">{{ __('Salvando...') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @endif
                    {{-- FIM DA SEÇÃO DE EDIÇÃO/CORREÇÃO PARA ACS --}}

                    {{-- No arquivo edit-prescription.blade.php --}}
                    {{-- Dentro da seção de Detalhes do Cidadão e Solicitação Original, após o bloco @if($prescription->processing_notes) --}}
                    {{-- Ou em um local apropriado onde você queira exibir a imagem da receita --}}

                    @if ($prescription->image_path) {{-- Verifica se existe um caminho de imagem salvo --}}
                    <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-2">{{__('Imagem da Receita Anexada:')}}</h4>
                        <div class="mb-3">
                            {{-- Pré-visualização da imagem --}}
                            <a href="{{ $prescription->image_url }}" target="_blank"
                               class="inline-block p-1 border border-dashed border-gray-300 dark:border-neutral-600 rounded-md hover:border-indigo-500 dark:hover:border-sky-500 transition-colors">
                                <img src="{{ $prescription->image_url }}" alt="{{__('Preview da receita anexada')}}" class="max-h-48 w-auto rounded shadow-sm">
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-2 items-center">
                            {{-- Botão para Abrir Imagem em Nova Aba (para visualização e impressão) --}}
                            <a href="{{ $prescription->image_url }}" target="_blank"
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-sky-500 dark:hover:bg-sky-400 dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--arrow-expand-all] w-4 h-4 mr-1.5 rtl:mr-0 rtl:ml-1.5"></span>
                                {{ __('Abrir Imagem (Nova Aba)') }}
                            </a>

                            {{-- Botão para Remover Imagem --}}
                            @can('update', $prescription) {{-- Ou uma policy mais específica como 'removeImage' --}}
                            <button type="button" wire:click="removeAttachedImage"
                                    wire:confirm="Tem certeza que deseja remover a imagem anexada?"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--image-remove-outline] w-4 h-4 mr-1.5 rtl:mr-0 rtl:ml-1.5"></span>
                                {{ __('Remover Imagem') }}
                            </button>
                            @endcan
                        </div>
                    </div>
                    @else
                        {{-- Seção para Upload de Nova Imagem (se nenhuma imagem existir) --}}
                        {{-- Esta seção pode ser combinada com a de "Alterar Imagem" se você preferir --}}
                        @if(!in_array($prescription->status, [\App\Enums\PrescriptionStatus::DELIVERED, \App\Enums\PrescriptionStatus::CANCELLED]))
                            @can('update', $prescription)
                                <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                                    <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 mb-2">{{__('Anexar Imagem da Receita')}}</h3>
                                    {{-- O código para upload de newPrescriptionImage iria aqui, como no formulário de criação --}}
                                    {{-- Exemplo simplificado: --}}
                                    <div>
                                        <label for="newPrescriptionImageUploadEdit" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            {{ __('Selecionar imagem para anexar') }}
                                        </label>
                                        <div class="mt-2">
                                            <input type="file" wire:model="newPrescriptionImage" id="newPrescriptionImageUploadEdit"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                                   class="block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4 file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200 hover:file:bg-indigo-200 dark:hover:file:bg-sky-600">
                                            <div wire:loading wire:target="newPrescriptionImage" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                                <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                                {{__('Carregando nova imagem...')}}
                                            </div>
                                        </div>
                                        @error('newPrescriptionImage') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                                        @if ($newPrescriptionImage && method_exists($newPrescriptionImage, 'temporaryUrl'))
                                            <div class="mt-4">
                                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Pré-visualização da Nova Imagem:')}}</p>
                                                <img src="{{ $newPrescriptionImage->temporaryUrl() }}" alt="{{__('Preview da nova receita anexada')}}" class="max-h-48 w-auto rounded-md border dark:border-neutral-600 shadow-sm">
                                            </div>


                                        @endif
                                        @if ($newPrescriptionImage)
                                            <div class="mt-3 text-right">
                                                <button type="button" wire:click="updateAttachedImage" wire:loading.attr="disabled"
                                                        class="inline-flex items-center justify-center rounded-md bg-teal-600 dark:bg-teal-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-teal-700 dark:hover:bg-teal-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600">
                                                    <span wire:loading wire:target="updateAttachedImage" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                                    <span wire:loading.remove wire:target="updateAttachedImage">{{ __('Salvar Nova Imagem') }}</span>
                                                    <span wire:loading wire:target="updateAttachedImage">{{ __('Salvando Imagem...') }}</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endcan
                        @endif
                    @endif

                    {{-- A seção de "Adicionar Nota ao Processamento" e "Ações de Mudança de Status" viria abaixo, --}}
                    {{-- dentro do @if(!in_array($prescription->status, ...)) principal --}}

                    {{-- Adicionar Nota ao Processamento (para outros perfis, ou ACS em outros status se permitido) --}}
                    @if(!(Auth::user() && Auth::user()->hasRole('acs') && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR])))
                        @can('addProcessingNote', $prescription)
                            <div class="pt-6 @if(Auth::user() && Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR]) && Auth::user()->can('update', $prescription)) border-t dark:border-neutral-700 @endif">
                                <label for="new_processing_notes" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Adicionar Nota ao Processamento') }}</label>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-1">
                                    {{ __('Suas notas serão adicionadas ao histórico com data e seu nome. O conteúdo anterior será mantido.')}}
                                </p>
                                <div class="mt-2">
                                    <textarea wire:model.defer="current_processing_notes" id="new_processing_notes" rows="3" placeholder="Digite uma nova nota aqui..."
                                              class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('current_processing_notes') border-red-500 dark:border-red-500 @enderror"></textarea>
                                </div>
                                @error('current_processing_notes') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                <div class="mt-3 flex justify-end">
                                    <button type="button" wire:click="saveProcessingNotes" wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500">
                                        <span wire:loading wire:target="saveProcessingNotes" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                        <span wire:loading.remove wire:target="saveProcessingNotes">{{ __('Adicionar Nota') }}</span>
                                        <span wire:loading wire:target="saveProcessingNotes">{{ __('Adicionando...') }}</span>
                                    </button>
                                </div>
                            </div>
                        @endcan
                    @endif


                    {{-- Ações de Mudança de Status (outras que não Cancelar ou edição de conteúdo pela ACS) --}}
                    @if(count($statusOptionsForSelect) > 0)
                        <div class="pt-6 border-t dark:border-neutral-700">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 mb-3">{{__('Próximas Ações / Alterar Status')}}</h3>
                            <div class="flex flex-wrap items-center gap-3">
                                @foreach($statusOptionsForSelect as $statusValue => $statusLabel)
                                    @php
                                        $statusEnum = \App\Enums\PrescriptionStatus::from($statusValue);
                                        $buttonClasses = 'inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-50 transition-colors duration-150';
                                        $iconClasses = 'w-4 h-4 sm:w-5 sm:h-5 mr-1.5 -ml-0.5 rtl:mr-0 rtl:ml-1.5';
                                        $specificClasses = ''; $icon = '';
                                        switch($statusEnum) {
                                            case \App\Enums\PrescriptionStatus::APPROVED_FOR_ISSUANCE: $specificClasses = 'bg-green-600 hover:bg-green-700 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-400'; $icon = 'icon-[mdi--check-decagram-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::READY_FOR_PICKUP:    $specificClasses = 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400'; $icon = 'icon-[mdi--package-variant-closed-check]'; break;
                                            case \App\Enums\PrescriptionStatus::DELIVERED:           $specificClasses = 'bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 dark:bg-teal-500 dark:hover:bg-teal-400'; $icon = 'icon-[mdi--account-check-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR:  $specificClasses = 'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-400'; $icon = 'icon-[mdi--file-cancel-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::UNDER_DOCTOR_REVIEW: $specificClasses = 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-500 dark:bg-amber-400 dark:hover:bg-amber-300'; $icon = 'icon-[mdi--account-search-outline]'; break;
                                            default:                                                 $specificClasses = 'bg-gray-500 hover:bg-gray-600 focus:ring-gray-500 dark:bg-gray-400 dark:hover:bg-gray-300'; $icon = 'icon-[mdi--progress-question]';
                                        }
                                    @endphp
                                    <button type="button" wire:click="prepareStatusUpdate('{{ $statusValue }}')"
                                            class="{{ $buttonClasses }} {{ $specificClasses }}">
                                        @if($icon)<span class="{{ $icon }} {{ $iconClasses }}"></span>@endif
                                        {{ $statusLabel }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- BOTÃO DE CANCELAR DEDICADO E TEXTO INFORMATIVO --}}
                    @if(Auth::user() && (Auth::user()->hasRole('acs') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('doctor')))
                        <div class="pt-6 @if( (count($statusOptionsForSelect) > 0) || (Auth::user()->can('addProcessingNote', $prescription)) || (Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR]) && Auth::user()->can('update', $prescription)) ) border-t dark:border-neutral-700 @endif">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 mb-1">{{__('Outras Ações')}}</h3>
                            @if(Auth::user()->hasRole('acs'))
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-3">
                                    {{ __('Como ACS, você só pode cancelar suas próprias solicitações se o status ainda for "Solicitada".') }}<br>
                                    {{ __('Se já estiver em processamento, o cancelamento por aqui não é mais possível.') }}
                                </p>
                            @elseif(Auth::user()->hasRole('manager') || Auth::user()->hasRole('doctor'))
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-3">
                                    {{ __('O cancelamento geralmente não é permitido para solicitações com status final (Ex: \'Entregue\') ou que já estejam \'Canceladas\'. Verifique as condições específicas.') }}
                                </p>
                            @endif
                            <button type="button"
                                    wire:click="prepareCancellation"
                                    @disabled(Auth::user()->cannot('cancel', $prescription))
                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition-colors duration-150 bg-orange-500 hover:bg-orange-600 focus:ring-orange-500 dark:bg-orange-400 dark:hover:bg-orange-300 disabled:bg-gray-300 dark:disabled:bg-neutral-600 disabled:text-gray-500 dark:disabled:text-neutral-400 disabled:cursor-not-allowed"
                                    title="{{ Auth::user()->can('cancel', $prescription) ? __('Cancelar Solicitação') : __('Cancelamento não permitido neste momento') }}">
                                <span class="icon-[mdi--cancel-bold] w-4 h-4 sm:w-5 sm:h-5 mr-1.5 -ml-0.5 rtl:mr-0 rtl:ml-1.5"></span>
                                {{ __('Cancelar Solicitação') }}
                            </button>
                        </div>
                    @endif
                </div>
            @endif {{-- Fim do @if para mostrar seção de edição --}}

            {{-- Modal de Atualização de Status com Motivo --}}
            @if($showStatusUpdateModal)
                <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-status-update" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div wire:click="closeStatusUpdateModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            {{-- Conteúdo do Modal --}}
                            <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                         :class="{
                                            'bg-red-100 dark:bg-red-800/30': '{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}',
                                            'bg-blue-100 dark:bg-blue-800/30': !('{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}')
                                         }">
                                        <span class="h-6 w-6"
                                              :class="{
                                                'icon-[mdi--alert-outline] text-red-600 dark:text-red-400': '{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}',
                                                'icon-[mdi--information-outline] text-blue-600 dark:text-blue-400': !('{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}')
                                              }"></span>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-status-update">{{ $modalTitle }}</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-neutral-300 mb-2">
                                                {{ __('Você está prestes a alterar o status da solicitação para') }} <strong>{{ \App\Enums\PrescriptionStatus::tryFrom($targetStatus ?? '')?->label() ?? $targetStatus }}</strong>.
                                            </p>
                                            @if(in_array($targetStatus, [\App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value, \App\Enums\PrescriptionStatus::CANCELLED->value]))
                                                <div>
                                                    <label for="statusUpdateReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Motivo')}}<span class="text-red-500">*</span></label>
                                                    <textarea wire:model.defer="statusUpdateReason"
                                                              id="statusUpdateReason" rows="3"
                                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('statusUpdateReason') border-red-500 dark:border-red-500 @enderror"></textarea>
                                                    @error('statusUpdateReason') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-600 dark:text-neutral-300">{{ __('Deseja continuar?') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-100 dark:bg-neutral-800/80 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-x-3">
                                <button wire:click="confirmStatusUpdate" type="button" wire:loading.attr="disabled"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 sm:w-auto disabled:opacity-50"
                                        :class="{
                                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-400': '{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}',
                                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-sky-500 dark:hover:bg-sky-400': !('{{ $targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value }}')
                                        }">
                                    <span wire:loading.remove wire:target="confirmStatusUpdate">{{ $modalConfirmationButtonText }}</span>
                                    <svg wire:loading wire:target="confirmStatusUpdate" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                <button wire:click="closeStatusUpdateModal" type="button" wire:loading.attr="disabled"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-500 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-sm font-semibold text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-neutral-800 sm:mt-0 sm:w-auto disabled:opacity-50">
                                    {{ __('Voltar') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Fim do Modal --}}
        </div>
    </div>
    <style> /* Estilo do scrollbar mantido */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; /* Light theme scrollbar */ border-radius: 3px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; /* Dark theme scrollbar */ }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; /* Light theme hover */ }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; /* Dark theme hover */ }
    </style>
</div>
