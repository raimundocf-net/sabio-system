<div>
    <x-slot:title>
        {{ $pageTitle }}
    </x-slot:title>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-neutral-800 shadow-xl sm:rounded-lg">
            <div class="p-6 border-b dark:border-neutral-700">
                {{-- Seção de informações do cidadão --}}
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

            @if($currentUserUnitName)
                <form wire:submit.prevent="submitPrescriptionRequest">
                    <div class="p-6 space-y-6">
                        {{-- Detalhes da Solicitação (Unidade, Médico) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Unidade de Saúde Solicitante') }}</label>
                                <div class="mt-2">
                                    <p class="block w-full rounded-md border-0 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700/50 py-2.5 px-3 text-gray-700 dark:text-neutral-300 shadow-sm sm:text-sm">
                                        {{ $currentUserUnitName }}
                                    </p>
                                </div>
                                @error('unit_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="doctor_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Atribuir ao Médico (Opcional)') }}</label>
                                <div class="mt-2">
                                    <select wire:model.defer="doctor_id" id="doctor_id" class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('doctor_id') border-red-500 dark:border-red-500 @enderror">
                                        <option value="">{{ __('Nenhum médico específico') }}</option>
                                        @foreach($doctorsList as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('doctor_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Campo de texto único para detalhes da receita --}}
                        <div>
                            <label for="prescriptionRequestDetails" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                {{ __('Medicamentos Solicitados e Instruções') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <textarea wire:model.defer="prescriptionRequestDetails"
                                          id="prescriptionRequestDetails"
                                          rows="6"
                                          class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm @error('prescriptionRequestDetails') border-red-500 dark:border-red-500 @enderror"
                                          placeholder="Descreva os medicamentos, dosagens, quantidades, instruções de uso ou escreva 'Conforme imagem em anexo'."
                                          required></textarea>
                            </div>
                            @error('prescriptionRequestDetails') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Upload de Múltiplas Imagens da Receita --}}
                        <div>
                            <label for="prescriptionImages" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                {{ __('Anexar Imagens da Receita (Máx. 3, Opcional)') }}
                            </label>
                            <div class="mt-2">
                                <input type="file" wire:model="prescriptionImages" id="prescriptionImages" multiple {{-- Adicionado multiple --}}
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       class="block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                                              file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4
                                              file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                                              hover:file:bg-indigo-200 dark:hover:file:bg-sky-600">
                                <div wire:loading wire:target="prescriptionImages" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                    <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                    {{__('Carregando imagens...')}}
                                </div>
                            </div>
                            @error('prescriptionImages') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            @error('prescriptionImages.*') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                            {{-- Pré-visualização das Imagens Selecionadas --}}
                            @if ($prescriptionImages)
                                <div class="mt-4 space-y-2">
                                    <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1">{{__('Pré-visualização das Imagens Selecionadas:')}}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($prescriptionImages as $index => $image)
                                            @if(method_exists($image, 'temporaryUrl'))
                                                <div class="relative group">
                                                    <img src="{{ $image->temporaryUrl() }}" alt="{{__('Preview da receita anexada')}} {{ $index + 1 }}" class="max-h-32 h-32 w-auto object-cover rounded border dark:border-neutral-500 shadow-sm">
                                                    <button type="button" wire:click="removeImage({{ $index }})"
                                                            class="absolute top-0 right-0 m-1 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                                                            title="{{__('Remover imagem')}} {{ $index + 1 }}">
                                                        <span class="icon-[mdi--close] w-4 h-4"></span>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-900/10 dark:border-neutral-100/10">
                        <a href="{{ route('prescriptions.index') }}" wire:navigate
                           class="rounded-md bg-white dark:bg-neutral-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitPrescriptionRequest, prescriptionImages" {{-- Adicionado prescriptionImages ao wire:target do loading --}}
                        class="inline-flex justify-center rounded-md bg-blue-600 dark:bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                            <span wire:loading.remove wire:target="submitPrescriptionRequest, prescriptionImages" class="icon-[mdi--send-check-outline] w-5 h-5 mr-1.5 -ml-0.5"></span>
                            <svg wire:loading wire:target="submitPrescriptionRequest, prescriptionImages" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading wire:target="submitPrescriptionRequest, prescriptionImages">{{__('Enviando...')}}</span>
                            <span wire:loading.remove wire:target="submitPrescriptionRequest, prescriptionImages">{{ __('Enviar Solicitação') }}</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-6 text-center">
                    <p class="text-lg text-red-600 dark:text-red-400">{{ __('Não é possível solicitar receitas.') }}</p>
                    <p class="text-sm text-gray-700 dark:text-neutral-300 mt-2">{{ __('Você não está associado a uma unidade de saúde ou sua unidade não pôde ser determinada. Por favor, contate o suporte.') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
