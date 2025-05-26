<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?> 
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100"><?php echo e($pageTitle); ?></h1>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            
            <form wire:submit.prevent="storeUser">
                <div class="p-6">
                    
                    <?php echo $__env->make('livewire.users._form-user', ['isEditing' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                
                <div class="flex items-center justify-end space-x-3 bg-gray-50 dark:bg-neutral-800/50 px-6 py-4 border-t border-gray-200 dark:border-neutral-700 sm:rounded-b-lg">
                    <a href="<?php echo e(route('users.index')); ?>" wire:navigate
                       class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                        <span class="icon-[mdi--cancel] w-4 h-4 mr-2"></span>
                        <?php echo e(__('Cancelar')); ?>

                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-xs font-semibold text-white uppercase tracking-widest shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-25">
                        <span wire:loading.remove wire:target="storeUser" class="icon-[mdi--content-save] w-4 h-4 mr-2"></span>
                        <svg wire:loading wire:target="storeUser" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading wire:target="storeUser"><?php echo e(__('Criando...')); ?></span>
                        <span wire:loading.remove wire:target="storeUser"><?php echo e(__('Criar Usuário')); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/users/create-user.blade.php ENDPATH**/ ?>