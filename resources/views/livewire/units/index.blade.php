<div>
    {{-- O Livewire requer um único elemento raiz na view --}}

    {{-- Título da Página para o Slot do Layout --}}
    <x-slot:title>
        {{ __('Unidades de Saúde') }}
    </x-slot>

    <div class="space-y-6">
        {{-- Cabeçalho da Página e Botão de Nova Unidade --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ __('Unidades de Saúde') }}
            </h1>
            <a href="{{ route('units.create') }}" wire:navigate
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-sky-500 dark:hover:bg-sky-400">
                <span class="icon-[mdi--plus-circle-outline] w-5 h-5 mr-2"></span>
                {{ __('Nova Unidade') }}
            </a>
        </div>

        {{-- Mensagem de Status/Sucesso --}}
        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-100 p-4 dark:bg-green-800/30">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="icon-[mdi--check-circle] h-5 w-5 text-green-500 dark:text-green-300" aria-hidden="true"></span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-700 dark:text-green-200">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabela de Unidades --}}
        <div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-100 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        {{ __('Nome') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        {{ __('Município') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        {{ __('CNES') }}
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">{{ __('Ações') }}</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                @forelse ($units as $unit)
                    <tr wire:key="unit-{{ $unit->id }}">
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-neutral-900 dark:text-neutral-100">
                            {{ $unit->name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-600 dark:text-neutral-300">
                            {{ $unit->municipality }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-600 dark:text-neutral-300">
                            {{ $unit->cnes }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('units.edit', $unit) }}" wire:navigate
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                               title="{{ __('Editar') }}">
                                <span class="icon-[mdi--pencil-outline] w-5 h-5"></span>
                            </a>
                            <button wire:click="openDeleteModal({{ $unit->id }})"
                                    class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    title="{{ __('Excluir') }}">
                                <span class="icon-[mdi--delete-outline] w-5 h-5"></span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('Nenhuma unidade encontrada.') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if ($units->hasPages())
            <div class="px-2 py-2">
                {{ $units->links() }}
            </div>
        @endif

        {{-- Modal de Confirmação de Exclusão --}}
        @if($showDeleteModal && $deletingUnit)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Background overlay --}}
                    <div wire:click="closeDeleteModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>

                    {{-- Modal panel --}}
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title">
                                        {{ __('Confirmar Exclusão') }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 dark:text-neutral-300">
                                            {{ __('Você tem certeza que deseja excluir a unidade') }} <strong>{{ $deletingUnit->name }}</strong>?
                                            {{ __('Esta ação não poderá ser desfeita.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button wire:click="deleteUnit" type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--delete-outline] w-5 h-5 mr-2"></span>
                                {{ __('Excluir Unidade') }}
                            </button>
                            <button wire:click="closeDeleteModal" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--cancel] w-5 h-5 mr-2"></span>
                                {{ __('Cancelar') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
