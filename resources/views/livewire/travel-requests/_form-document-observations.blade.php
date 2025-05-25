<div>
    <label for="referralDocumentFile_form" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
        {{-- Lógica para exibir "Alterar" ou "Anexar" baseada se já existe um arquivo --}}
        {{ ($form['referral_document_path'] ?? null) ? __('Alterar Foto da Guia/Encaminhamento') : __('Anexar Foto da Guia/Encaminhamento (Opcional)') }}
    </label>
    <input type="file" wire:model="referralDocumentFile" id="referralDocumentFile_form"
           class="mt-1 block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4 file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200 hover:file:bg-indigo-200 dark:hover:file:bg-sky-600 @error('referralDocumentFile') border-red-500 dark:border-red-400 @enderror">
    <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
        <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
        {{__('Carregando imagem...')}}
    </div>
    @error('referralDocumentFile') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

    @if ($referralDocumentFile && method_exists($referralDocumentFile, 'temporaryUrl'))
        <div class="mt-2">
            <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Pré-visualização da Nova Imagem:')}}</p>
            <img src="{{ $referralDocumentFile->temporaryUrl() }}" alt="{{__('Preview da nova guia')}}" class="max-h-40 w-auto rounded border dark:border-neutral-600">
        </div>
    @elseif ($form['referral_document_path'] ?? null)
        <div class="mt-2">
            <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Guia Anexada:')}}</p>
            <a href="{{ Storage::url($form['referral_document_path']) }}" target="_blank">
                <img src="{{ Storage::url($form['referral_document_path']) }}" alt="{{__('Preview da guia anexada')}}" class="max-h-40 w-auto rounded border dark:border-neutral-600 hover:ring-2 hover:ring-indigo-500 dark:hover:ring-sky-500">
            </a>
            @if(isset($travelRequestInstance) && method_exists($this, 'removeReferralDocument'))
                @can('update', $travelRequestInstance)
                    <button type="button" wire:click="removeReferralDocument" wire:confirm="{{__('Tem certeza que deseja remover a imagem da guia?')}}"
                            class="mt-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <span class="icon-[mdi--delete-outline] w-4 h-4 mr-1"></span>
                        {{__('Remover Imagem')}}
                    </button>
                @endcan
            @endif
        </div>
    @endif
</div>

<div>
    <label for="observations_form" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{__('Observações Gerais da Atendente')}}</label>
    <textarea wire:model.defer="form.observations" id="observations_form" rows="3" placeholder="{{__('Informações adicionais sobre a necessidade da viagem, restrições do paciente, etc...')}}"
              class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 @error('form.observations') border-red-500 dark:border-red-400 @enderror"></textarea>
    @error('form.observations') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
</div>
