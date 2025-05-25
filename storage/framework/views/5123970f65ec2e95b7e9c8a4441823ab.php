<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="my-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 inline-flex items-center gap-2">
                        <span class="icon-[ph--car-profile-fill] w-6 h-6 mr-2 rtl:mr-0 rtl:ml-2 inline-block text-indigo-600 dark:text-sky-500"></span>
                        <?php echo e($pageTitle); ?>

                    </h2>
                    <a href="<?php echo e(route('vehicles.index')); ?>" wire:navigate
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        <span class="icon-[icon-park-outline--back] w-4 h-4 mr-1 rtl:mr-0 rtl:ml-1 inline-block"></span>
                        <?php echo e(__('Voltar para Lista de Veículos')); ?>

                    </a>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="p-4 sm:p-6 space-y-5">
                    <?php echo $__env->make('livewire.vehicles._form-fields', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-200 dark:border-neutral-700">
                    <a href="<?php echo e(route('vehicles.index')); ?>" wire:navigate
                       class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                        <?php echo e(__('Cancelar')); ?>

                    </a>
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                        <span wire:loading wire:target="save" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                        <span wire:loading.remove wire:target="save">
                            <span class="icon-[mdi--content-save] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                            <?php echo e(__('Atualizar Veículo')); ?>

                        </span>
                        <span wire:loading wire:target="save"><?php echo e(__('Atualizando...')); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/vehicles/update-vehicle.blade.php ENDPATH**/ ?>