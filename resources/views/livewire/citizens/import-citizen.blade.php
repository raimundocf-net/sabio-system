<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">{{ $pageTitle }}</h1>
            {{-- Botão Voltar (opcional) --}}
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <form wire:submit.prevent="import" class="p-6 space-y-6">
                <div>
                    <label for="file-upload" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                        {{ __('Selecione o arquivo JSON') }}
                    </label>
                    <div class="mt-2">
                        {{-- Alterado accept para .json --}}
                        <input type="file" wire:model="file" id="file-upload" accept=".json,application/json,text/plain"
                               class="block w-full text-sm text-gray-900 dark:text-neutral-200 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-l-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                                      hover:file:bg-indigo-100 dark:hover:file:bg-sky-600
                                      @error('file') border-red-500 dark:border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400" id="file_input_help">
                            {{__('O arquivo JSON deve ter a chave principal "data" contendo um array de cidadãos.')}}
                        </p>
                    </div>
                    <div wire:loading wire:target="file" class="mt-2 text-sm text-gray-500 dark:text-neutral-400">
                        {{ __('Carregando arquivo...') }}
                    </div>
                    @error('file') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="import, file"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-50">
                        <span wire:loading.remove wire:target="import, file" class="icon-[mdi--cloud-upload-outline] w-5 h-5 mr-2"></span>
                        <svg wire:loading wire:target="import, file" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading wire:target="import, file">{{ __('Importando...') }}</span>
                        <span wire:loading.remove wire:target="import, file">{{ __('Executar Importação') }}</span>
                    </button>
                </div>
            </form>

            {{-- Feedback da Importação (mantém a mesma estrutura) --}}
            @if (session('status') || session('error') || session('warning_message') || !is_null($importedCount))
                <div class="mt-6 p-6 border-t border-gray-200 dark:border-neutral-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-100">{{__('Resultado da Importação')}}</h3>
                    @if (session('status'))
                        <div class="mt-2 rounded-md bg-green-50 dark:bg-green-800/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0"><span class="icon-[mdi--check-circle-outline] h-5 w-5 text-green-600 dark:text-green-400"></span></div>
                                <div class="ml-3"><p class="text-sm font-medium text-green-700 dark:text-green-200">{{ session('status') }}</p></div>
                            </div>
                        </div>
                    @endif
                    @if (session('warning_message'))
                        <div class="mt-2 rounded-md bg-yellow-50 dark:bg-yellow-600/20 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0"><span class="icon-[mdi--alert-outline] h-5 w-5 text-yellow-500 dark:text-yellow-400"></span></div>
                                <div class="ml-3"><p class="text-sm font-medium text-yellow-700 dark:text-yellow-200">{{ session('warning_message') }}</p></div>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mt-2 rounded-md bg-red-50 dark:bg-red-800/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0"><span class="icon-[mdi--alert-circle-outline] h-5 w-5 text-red-600 dark:text-red-400"></span></div>
                                <div class="ml-3"><p class="text-sm font-medium text-red-700 dark:text-red-200">{{ session('error') }}</p></div>
                            </div>
                        </div>
                    @endif

                    @if (!is_null($importedCount))
                        <div class="mt-4 text-sm text-gray-700 dark:text-neutral-300 space-y-1">
                            <p><strong>✔️ {{__('Inseridos/atualizados:')}}</strong> {{ $importedCount }}</p>
                            <p><strong>⚠️ {{__('Ignorados:')}}</strong> {{ $skippedCount }}</p>
                            <p><strong>❌ {{__('Erros de processamento:')}}</strong> {{ $errorCount }}</p>
                            @if(count($errorsDetails) > 0 && ($errorCount > 0 || $skippedCount > 0))
                                <div class="mt-2">
                                    <p><strong>{{__('Detalhes dos erros/alertas:')}}</strong></p>
                                    <ul class="list-disc list-inside max-h-40 overflow-y-auto text-xs bg-gray-100 dark:bg-neutral-700 p-2 rounded">
                                        @foreach($errorsDetails as $errDetail)
                                            <li>{{ $errDetail }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    {{-- O @push('scripts') com Livewire.on('file-input-reset') já deve existir da implementação anterior e continua útil --}}
    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('file-input-reset', (event) => {
                    const fileInput = document.getElementById('file-upload');
                    if (fileInput) {
                        fileInput.value = null;
                    }
                });
            });
        </script>
    @endpush
</div>
