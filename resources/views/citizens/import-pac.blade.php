{{-- resources/views/citizens/import-pac.blade.php --}}
<x-layouts.app :title="__('Importar Cidadãos PAC')">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">{{ __('Importar Cidadãos do PAC (.csv)') }}</h1>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <form action="{{ route('citizens.import-pac.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="file-upload-pac" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                        {{ __('Selecione o arquivo CSV') }}
                    </label>
                    <div class="mt-2">
                        <input
                            type="file"
                            name="file"
                            id="file-upload-pac"
                            accept=".csv,text/csv"
                            class="block w-full text-sm text-gray-900 dark:text-neutral-200 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-l-lg file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-indigo-50 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                                   hover:file:bg-indigo-100 dark:hover:file:bg-sky-600
                                   @error('file') border-red-500 dark:border-red-500 @enderror"
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
                            {{__('O arquivo CSV deve ter os dados começando na linha 20.')}}
                        </p>
                    </div>
                    @error('file')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-50">
                        <span class="icon-[mdi--cloud-upload-outline] w-5 h-5 mr-2"></span>
                        {{ __('Executar Importação PAC') }}
                    </button>
                </div>
            </form>

            @if (session('message') || session('error') || session('warning_message') || session()->has('importedCount'))
                <div class="mt-6 p-6 border-t border-gray-200 dark:border-neutral-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-100">{{__('Resultado da Importação PAC')}}</h3>
                    @if (session('message'))
                        <div class="mt-2 rounded-md bg-green-50 dark:bg-green-800/30 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0"><span class="icon-[mdi--check-circle-outline] h-5 w-5 text-green-600 dark:text-green-400"></span></div>
                                <div class="ml-3"><p class="text-sm font-medium text-green-700 dark:text-green-200">{{ session('message') }}</p></div>
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

                    @if (session()->has('importedCount'))
                        <div class="mt-4 text-sm text-gray-700 dark:text-neutral-300 space-y-1">
                            <p><strong>✔️ {{__('Inseridos/atualizados:')}}</strong> {{ session('importedCount', 0) }}</p>
                            <p><strong>⚠️ {{__('Ignorados:')}}</strong> {{ session('skippedCount', 0) }}</p>
                            <p><strong>❌ {{__('Erros de processamento:')}}</strong> {{ session('errorCount', 0) }}</p>
                            @if(session()->has('errorsDetails') && count(session('errorsDetails')) > 0 && (session('errorCount', 0) > 0 || session('skippedCount', 0) > 0))
                                <div class="mt-2">
                                    <p><strong>{{__('Detalhes dos erros/alertas:')}}</strong></p>
                                    <ul class="list-disc list-inside max-h-40 overflow-y-auto text-xs bg-gray-100 dark:bg-neutral-700 p-2 rounded">
                                        @foreach(session('errorsDetails', []) as $errDetail)
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
</x-layouts.app>
