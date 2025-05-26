{{-- Conteúdo do seu arquivo existente: resources/views/livewire/travel-requests/_form-document-observations.blade.php --}}
{{-- Este arquivo deve conter os campos para upload de documento e observações --}}
{{-- Exemplo de estrutura (adapte ao seu conteúdo real): --}}

@props(['isEditing' => false]) {{-- Adicionado para consistência, caso precise diferenciar --}}

<fieldset class="space-y-6 mt-8">
    <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700">{{__('Documentos e Observações')}}</legend>

    {{-- Upload da Guia de Encaminhamento --}}
    <div>
        <label for="referralDocumentFile" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
            {{ __('Guia de Encaminhamento (Opcional)') }}
            @if($isEditing && $form['referral_document_path'])
                <a href="{{ Storage::url($form['referral_document_path']) }}" target="_blank" class="ml-2 text-xs text-indigo-600 dark:text-sky-400 hover:underline">({{__('Ver atual')}})</a>
            @endif
        </label>
        <div class="mt-2">
            <input type="file" wire:model="referralDocumentFile" id="referralDocumentFile"
                   class="block w-full text-sm text-gray-900 dark:text-neutral-200 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-l-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                          hover:file:bg-indigo-100 dark:hover:file:bg-sky-600
                          @error('referralDocumentFile') border-red-500 dark:border-red-500 @enderror">
            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400" id="file_input_help">
                {{__('Imagem (JPG, PNG, GIF, WEBP) até 5MB.')}}
                @if($isEditing && $form['referral_document_path'])
                    {{__('Enviar novo arquivo substituirá o atual.')}}
                @endif
            </p>
        </div>
        <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
            {{ __('Carregando arquivo...') }}
        </div>
        @error('referralDocumentFile') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

        {{-- Preview da nova imagem --}}
        @if ($referralDocumentFile && str_starts_with($referralDocumentFile->getMimeType(), 'image'))
            <div class="mt-2">
                <img src="{{ $referralDocumentFile->temporaryUrl() }}" alt="{{__('Preview da Guia')}}" class="max-h-40 rounded border dark:border-neutral-600">
            </div>
        @endif
    </div>

    {{-- Observações --}}
    <div>
        <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">{{ __('Observações Adicionais') }}</label>
        <textarea wire:model.defer="form.observations" id="observations" rows="4" placeholder="{{__('Informações relevantes sobre o paciente, a viagem, necessidades especiais, etc.')}}"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 @error('form.observations') border-red-500 dark:border-red-400 @enderror"></textarea>
        @error('form.observations') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>
</fieldset>
