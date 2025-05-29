<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="flex flex-col gap-4 mb-6 mt-4 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center w-full sm:w-auto">
            <h1 class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-neutral-100">
                <span class="icon-[mdi--map-marker-multiple-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                <?php echo e($pageTitle); ?>

            </h1>
            <div class="sm:hidden">
                <a href="<?php echo e(route('boarding-locations.create')); ?>" wire:navigate
                   class="ml-2 inline-flex items-center justify-center gap-1 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm">
                    <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span> <?php echo e(__('Novo')); ?>

                </a>
            </div>
        </div>

        <div class="w-full sm:flex-1 flex flex-col sm:flex-row flex-wrap gap-2 justify-start sm:justify-end items-center">
            <div class="w-full sm:w-auto">
                <select wire:model.live="perPage" title="<?php echo e(__('Itens por página')); ?>" class="block w-full sm:w-auto rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="10">10 <?php echo e(__('por pág.')); ?></option>
                    <option value="25">25 <?php echo e(__('por pág.')); ?></option>
                    <option value="50">50 <?php echo e(__('por pág.')); ?></option>
                </select>
            </div>
            <div class="w-full sm:w-auto flex-grow sm:flex-grow-0 sm:max-w-xs">
                <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="<?php echo e(__('Buscar por Nome ou Endereço...')); ?>"
                       class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500" />
            </div>
            <div class="hidden sm:flex">
                <a href="<?php echo e(route('boarding-locations.create')); ?>" wire:navigate
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm">
                    <span class="icon-[mdi--map-marker-plus] w-5 h-5"></span> <?php echo e(__('Novo Local')); ?>

                </a>
            </div>
        </div>
    </div>

    <?php echo $__env->make('livewire.partials.session-messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Nome')); ?></th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Endereço')); ?></th>
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Status')); ?></th>
                    <th scope="col" class="relative px-4 py-3"><span class="sr-only"><?php echo e(__('Ações')); ?></span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr wire:key="bl-row-<?php echo e($location->id); ?>" class="hover:bg-gray-50 dark:hover:bg-neutral-700/30">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100"><?php echo e($location->name); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-neutral-300"><?php echo e(Str::limit($location->address, 70) ?: 'N/D'); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                            <!--[if BLOCK]><![endif]--><?php if($location->is_active): ?>
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-200">
                                    <?php echo e(__('Ativo')); ?>

                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-200">
                                    <?php echo e(__('Inativo')); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1">
                            <a href="<?php echo e(route('boarding-locations.edit', $location->id)); ?>" wire:navigate title="<?php echo e(__('Editar Local')); ?>"
                               class="inline-flex items-center justify-center p-1.5 rounded-full text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-neutral-600">
                                <span class="icon-[tabler--pencil] w-5 h-5"></span>
                            </a>
                            <!--[if BLOCK]><![endif]--><?php if($location->is_active): ?> 
                            <button wire:click="openDeleteModal(<?php echo e($location->id); ?>)" title="<?php echo e(__('Desativar Local')); ?>"
                                    class="inline-flex items-center justify-center p-1.5 rounded-full text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-neutral-600">
                                <span class="icon-[mdi--cancel-bold] w-5 h-5"></span>
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-16 text-center text-sm text-gray-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                <span class="icon-[mdi--map-marker-off-outline] text-6xl text-gray-300 dark:text-neutral-600 mb-3"></span>
                                <?php echo e(__('Nenhum local de embarque cadastrado.')); ?>

                                <a href="<?php echo e(route('boarding-locations.create')); ?>" wire:navigate class="mt-2 text-sm text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 font-medium">
                                    <?php echo e(__('Cadastrar Novo Local de Embarque')); ?>

                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($locations->hasPages()): ?>
            <div class="py-4 px-1">
                <?php echo e($locations->links(data: ['scrollTo' => false])); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal && $deletingLocation): ?>
        <div class="fixed inset-0 z-[100] flex items-end justify-center px-4 py-6 pointer-events-none sm:items-center sm:p-6" aria-labelledby="modal-title-delete-bl" role="dialog" aria-modal="true">
            <div wire:click="closeDeleteModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity pointer-events-auto" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pointer-events-auto">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-delete-bl"><?php echo e(__('Confirmar Desativação')); ?></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 dark:text-neutral-300">
                                    <?php echo e(__('Você tem certeza que deseja desativar o local de embarque')); ?> <strong>"<?php echo e($deletingLocation->name); ?>"</strong>?
                                    <?php echo e(__('Ele não poderá ser selecionado para novas solicitações, mas permanecerá no histórico.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="deleteLocation" type="button" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 disabled:opacity-50">
                        <span wire:loading.remove wire:target="deleteLocation"><?php echo e(__('Desativar')); ?></span>
                        <svg wire:loading wire:target="deleteLocation" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="closeDeleteModal" type="button" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50">
                        <?php echo e(__('Cancelar')); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /var/www/html/sabio-system/resources/views/livewire/boarding-locations/index-boarding-location.blade.php ENDPATH**/ ?>