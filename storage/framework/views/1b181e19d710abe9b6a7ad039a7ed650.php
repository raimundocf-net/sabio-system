<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white dark:bg-neutral-800 shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 mb-1"><?php echo e(__('Para Solicitar uma Receita, Busque por um Cidadão')); ?></h2>
                
                <p class="text-sm text-gray-600 dark:text-neutral-300 mb-6"><?php echo e(__('Preencha o campo abaixo para encontrar o cidadão.')); ?></p>

                <form wire:submit.prevent="searchCitizen" class="space-y-4">
                    <div>
                        <label for="search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Nome, CPF ou CNS do Cidadão')); ?> <span class="text-red-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" wire:model.defer="search" id="search" placeholder="<?php echo e(__('Digite Nome, CPF ou CNS')); ?>"
                                   class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm <?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['search'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="<?php echo e(route('prescriptions.index')); ?>" wire:navigate
                           class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                            <span class="icon-[mdi--cancel] w-4 h-4 mr-2"></span>
                            <?php echo e(__('Cancelar')); ?>

                        </a>
                        <button type="button" wire:click="clearSearch"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                            <span class="icon-[mdi--eraser] w-4 h-4 mr-2"></span>
                            <?php echo e(__('Limpar Busca')); ?>

                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-50">
                            <span wire:loading.remove class="icon-[mdi--account-search-outline] w-5 h-5 mr-2"></span>
                            <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading><?php echo e(__('Buscando...')); ?></span>
                            <span wire:loading.remove><?php echo e(__('Buscar Cidadão')); ?></span>
                        </button>
                    </div>
                </form>

                <?php echo $__env->make('livewire.partials.session-messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

                <!--[if BLOCK]><![endif]--><?php if($results && $results->isNotEmpty()): ?>
                    <div class="border-t dark:border-neutral-700 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-neutral-100"><?php echo e(__('Cidadãos Encontrados:')); ?> (<?php echo e($results->count()); ?>)</h3>
                        <ul class="mt-3 space-y-3">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $citizen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li wire:key="citizen-result-<?php echo e($citizen->id); ?>" class="border dark:border-neutral-700 p-4 rounded-md shadow-sm text-sm text-gray-700 dark:text-neutral-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                                    <div class="flex-grow">
                                        
                                        <p><strong><?php echo e(__('Nome:')); ?></strong> <?php echo e($citizen->nome_do_cidadao ?: 'N/A'); ?></p>
                                        <p><strong><?php echo e(__('CPF:')); ?></strong> <?php echo e($citizen->cpf ?: 'N/A'); ?></p>
                                        <p><strong><?php echo e(__('CNS:')); ?></strong> <?php echo e($citizen->cns ?: 'N/A'); ?></p>
                                        <p><strong><?php echo e(__('Micro Área:')); ?></strong> <?php echo e($citizen->microarea ?: 'N/A'); ?></p>
                                        <p><strong><?php echo e(__('Nascimento:')); ?></strong> <?php echo e($citizen->data_de_nascimento ? \Carbon\Carbon::parse($citizen->data_de_nascimento)->format('d/m/Y') : 'N/A'); ?></p>
                                    </div>
                                    <a href="<?php echo e(route('prescriptions.request.form', ['citizenId' => $citizen->id])); ?>" wire:navigate
                                       class="inline-flex items-center justify-center shrink-0 w-full sm:w-auto mt-2 sm:mt-0 px-3 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 dark:hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition"
                                       title="<?php echo e(__('Solicitar receita para este cidadão')); ?>">
                                        <span class="icon-[mdi--text-box-plus-outline] w-4 h-4 mr-1.5"></span>
                                        <?php echo e(__('Selecionar Cidadão')); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    </div>
                    
                <?php elseif($results && $results->isEmpty() && !empty($search)): ?>
                    <div class="border-t dark:border-neutral-700 pt-6 mt-6">
                        
                        
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div><?php /**PATH /var/www/html/sabio-system/resources/views/livewire/prescriptions/request/search-citizen-step.blade.php ENDPATH**/ ?>