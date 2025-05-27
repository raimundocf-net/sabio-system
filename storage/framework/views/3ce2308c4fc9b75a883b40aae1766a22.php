<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Gerenciar Usuários')); ?>

     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                <?php echo e(__('Usuários do Sistema')); ?>

            </h1>
            <a href="<?php echo e(route('users.create')); ?>" wire:navigate
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-sky-500 dark:hover:bg-sky-400">
                <span class="icon-[mdi--account-plus-outline] w-5 h-5 mr-2"></span>
                <?php echo e(__('Novo Usuário')); ?>

            </a>
        </div>

        <!--[if BLOCK]><![endif]--><?php if(session('status')): ?>
            <div class="mb-4 rounded-md bg-green-100 p-4 dark:bg-green-800/30">
                <div class="flex">
                    <div class="flex-shrink-0"><span class="icon-[mdi--check-circle] h-5 w-5 text-green-500 dark:text-green-300"></span></div>
                    <div class="ml-3"><p class="text-sm font-medium text-green-700 dark:text-green-200"><?php echo e(session('status')); ?></p></div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if(session('error')): ?>
            <div class="mb-4 rounded-md bg-red-100 p-4 dark:bg-red-800/30">
                <div class="flex">
                    <div class="flex-shrink-0"><span class="icon-[mdi--alert-circle] h-5 w-5 text-red-500 dark:text-red-300"></span></div>
                    <div class="ml-3"><p class="text-sm font-medium text-red-700 dark:text-red-200"><?php echo e(session('error')); ?></p></div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-white dark:bg-neutral-800 shadow sm:rounded-lg">
            <div>
                <label for="searchTerm" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Buscar')); ?></label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm" placeholder="<?php echo e(__('Nome ou Email')); ?>"
                       class="mt-1 block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm">
            </div>
            <div>
                <label for="filterRole" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Papel')); ?></label>
                <select wire:model.live="filterRole" id="filterRole"
                        class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm">
                    <option value=""><?php echo e(__('Todos os Papéis')); ?></option>
                    
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($roleKey); ?>"><?php echo e(__($roleLabel)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
            <div>
                <label for="filterUnitId" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Unidade')); ?></label>
                <select wire:model.live="filterUnitId" id="filterUnitId"
                        class="mt-1 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm">
                    <option value=""><?php echo e(__('Todas as Unidades')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $unitsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>


        <div class="overflow-x-auto rounded-lg border border-neutral-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-100 dark:bg-neutral-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300"><?php echo e(__('Nome')); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300"><?php echo e(__('Microárea')); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300"><?php echo e(__('Email')); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300"><?php echo e(__('Unidade')); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-300"><?php echo e(__('Papel')); ?></th>
                    <th class="relative px-6 py-3"><span class="sr-only"><?php echo e(__('Ações')); ?></span></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr wire:key="user-<?php echo e($user->id); ?>">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100"><?php echo e($user->name); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100"><?php echo e($user->microarea); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300"><?php echo e($user->email); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300"><?php echo e($user->unit?->name ?: __('N/A')); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__(ucfirst(str_replace('_', ' ', $user->role)))); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="<?php echo e(route('users.edit', $user)); ?>" wire:navigate class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="<?php echo e(__('Editar')); ?>">
                                <span class="icon-[mdi--account-edit-outline] w-5 h-5"></span>
                            </a>
                            <button wire:click="openDeleteModal(<?php echo e($user->id); ?>)" class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="<?php echo e(__('Excluir')); ?>">
                                <span class="icon-[mdi--delete-outline] w-5 h-5"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nenhum usuário encontrado.')); ?></td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($users->hasPages()): ?>
            <div class="px-2 py-2"><?php echo e($users->links()); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if($showDeleteModal && $deletingUser): ?>
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div wire:click="closeDeleteModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title"><?php echo e(__('Confirmar Exclusão')); ?></h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 dark:text-neutral-300">
                                            <?php echo e(__('Deseja excluir o usuário')); ?> <strong><?php echo e($deletingUser->name); ?></strong>?
                                            <?php echo e(__('Esta ação não poderá ser desfeita.')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button wire:click="deleteUser" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--delete-outline] w-5 h-5 mr-2"></span>
                                <?php echo e(__('Excluir Usuário')); ?>

                            </button>
                            <button wire:click="closeDeleteModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800">
                                <span class="icon-[mdi--cancel] w-5 h-5 mr-2"></span>
                                <?php echo e(__('Cancelar')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH /var/www/html/sabio-system/resources/views/livewire/users/index-user.blade.php ENDPATH**/ ?>