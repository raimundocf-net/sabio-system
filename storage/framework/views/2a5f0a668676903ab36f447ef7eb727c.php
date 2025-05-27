<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6"> 
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100">
                <?php echo e($pageTitle); ?>

            </h1>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Prescription::class)): ?> 
            <a href="<?php echo e(route('prescriptions.request.search')); ?>" wire:navigate
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-sky-500 dark:hover:bg-sky-400 dark:active:bg-sky-600 dark:focus:border-sky-600 dark:focus:ring-sky-300">
                <span class="icon-[mdi--text-box-plus-outline] w-5 h-5 mr-2"></span>
                <?php echo e(__('Solicitar Nova Receita')); ?>

            </a>
            <?php endif; ?>
        </div>

        <?php echo $__env->make('livewire.partials.session-messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <div>
                <label for="searchTermList" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Buscar por Paciente, CPF, Solicitante...')); ?></label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTermList" placeholder="<?php echo e(__('Digite para buscar...')); ?>"
                       class="mt-1 block w-full rounded-md
                      border border-gray-300 dark:border-neutral-500  
                      py-2 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700
                      focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm">
                
            </div>
            <div>
                <label for="filterStatus" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Filtrar por Status')); ?></label>
                <select wire:model.live="filterStatus" id="filterStatus"
                        class="mt-1 block w-full rounded-md
                       border border-gray-300 dark:border-neutral-500  
                       py-2 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700
                       focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm">
                    
                    <option value=""><?php echo e(__('Todos os Status')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>
        </div>

        
        
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4"> 
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div wire:key="prescription-card-<?php echo e($prescription->id); ?>"
                     class="relative flex flex-col bg-white dark:bg-neutral-800 shadow-md hover:shadow-lg border border-gray-200 dark:border-neutral-700 overflow-hidden transition-shadow duration-300 ease-in-out">
                    

                    <div class="p-4 flex flex-col flex-grow"> 
                        <div class="mb-2"> 
                            <h3 class="text-md font-semibold text-indigo-700 dark:text-indigo-400 truncate" title="<?php echo e($prescription->citizen?->name ?? $prescription->citizen?->name); ?>">
                                
                                <?php echo e($prescription->citizen?->name ?? $prescription->citizen?->name ?: __('Cidadão não informado')); ?>

                            </h3>
                            <p class="text-xs text-gray-500 dark:text-neutral-400">
                                CPF: <?php echo e($prescription->citizen?->cpf ?: 'N/A'); ?>

                            </p>
                        </div>

                        
                        <div class="text-xs text-gray-700 dark:text-neutral-300 mb-3">
                            <p class=" text-gray-800 dark:text-neutral-100"><?php echo e(__('Pedido da ACS:')); ?>: <?php echo e($prescription->requester?->name ?: '—'); ?></p>
                            <p class="py-4  text-gray-600 dark:text-neutral-400 break-words font-medium text-center">
                                <?php echo e($prescription->prescription_details ? \Illuminate\Support\Str::limit($prescription->prescription_details, 120, '...') : __('Nenhum detalhe fornecido')); ?>

                            </p>
                        </div>

                        
                        <div class="mt-auto text-xs text-gray-500 dark:text-neutral-400 space-y-0.5"> 
                            <p><span class="font-medium"><?php echo e(__('Solicitante:')); ?></span> <?php echo e($prescription->requester?->name ?: '—'); ?></p>
                            <p><span class="font-medium"><?php echo e(__('Data:')); ?></span> <?php echo e($prescription->created_at->format('d/m/y H:i')); ?></p>
                            <!--[if BLOCK]><![endif]--><?php if($prescription->doctor): ?>
                                <p><span class="font-medium"><?php echo e(__('Médico:')); ?></span> <?php echo e($prescription->doctor?->name); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($prescription->unit): ?>
                                <p><span class="font-medium"><?php echo e(__('Unidade:')); ?></span> <?php echo e($prescription->unit?->name); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    
                    
                    <div class="flex items-center justify-between px-4 py-2 bg-gray-50 dark:bg-neutral-700/70 border-t dark:border-neutral-600">
                        <div class="flex items-center gap-x-2">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-md <?php echo e($prescription->status->badgeClasses()); ?>">
                                <?php echo e($prescription->status->label()); ?>

                            </span>

                            
                            <!--[if BLOCK]><![endif]--><?php if($prescription->status === \App\Enums\PrescriptionStatus::APPROVED_FOR_ISSUANCE): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('changeStatus', [$prescription, \App\Enums\PrescriptionStatus::READY_FOR_PICKUP])): ?>
                                    <button wire:click="openReadyForPickupModal(<?php echo e($prescription->id); ?>)"
                                            class="p-1 rounded-full hover:bg-purple-100 dark:hover:bg-purple-600 transition"
                                            title="<?php echo e(__('Marcar como Pronta para Retirada')); ?>">
                                        <span class="icon-[mdi--package-variant-closed-check] w-4 h-4 text-purple-600 dark:text-purple-400"></span>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            
                            <!--[if BLOCK]><![endif]--><?php if($prescription->status === \App\Enums\PrescriptionStatus::READY_FOR_PICKUP): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('changeStatus', [$prescription, \App\Enums\PrescriptionStatus::DELIVERED])): ?>
                                    <button wire:click="openDeliveryModal(<?php echo e($prescription->id); ?>)"
                                            class="p-1 rounded-full hover:bg-teal-100 dark:hover:bg-teal-600 transition"
                                            title="<?php echo e(__('Registrar Entrega')); ?>">
                                        <span class="icon-[mdi--check-circle-outline] w-4 h-4 text-teal-600 dark:text-teal-400"></span>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="flex space-x-1"> 
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $prescription)): ?>
                                <a href="<?php echo e(route('prescriptions.edit', $prescription->id)); ?>"
                                   wire:navigate
                                   class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-600 transition"
                                   title="<?php echo e(__('Detalhes / Editar')); ?>">
                                    <span class="icon-[mdi--eye-outline] w-5 h-5 text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300"></span>
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel', $prescription)): ?>
                                <button wire:click="openCancelModal(<?php echo e($prescription->id); ?>)"
                                        class="p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-neutral-600 transition"
                                        title="<?php echo e(__('Cancelar Receita')); ?>">
                                    <span class="icon-[mdi--cancel-bold] w-5 h-5 text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300"></span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16">
                    <span class="icon-[mdi--text-box-search-outline] w-20 h-20 text-gray-300 dark:text-neutral-600 mx-auto"></span>
                    <h3 class="mt-4 text-xl font-semibold text-gray-600 dark:text-neutral-300"><?php echo e(__('Nenhuma solicitação de receita encontrada.')); ?></h3>
                    <!--[if BLOCK]><![endif]--><?php if(empty($searchTerm) && empty($filterStatus)): ?>
                        <p class="mt-2 text-sm text-gray-400 dark:text-neutral-500">
                            <?php echo e(__('Clique em "Solicitar Nova Receita" para começar ou ajuste os filtros.')); ?>

                        </p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!--[if BLOCK]><![endif]--><?php if($prescriptions->hasPages()): ?>
            <div class="pt-6 mt-6 border-t dark:border-neutral-700"> 
                <?php echo e($prescriptions->links()); ?> 
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($showCancelModal && $cancellingPrescription): ?>
            <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-cancel" role="dialog" aria-modal="true"> 
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div wire:click="closeCancelModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-cancel"><?php echo e(__('Cancelar Solicitação de Receita')); ?></h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 dark:text-neutral-300 mb-1">
                                            <?php echo e(__('Tem certeza que deseja cancelar a solicitação para')); ?> <strong><?php echo e($cancellingPrescription->citizen?->name ?? $cancellingPrescription->citizen?->name); ?></strong>? 
                                        </p>
                                        <div>
                                            <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Motivo do Cancelamento')); ?><span class="text-red-500">*</span></label>
                                            <textarea wire:model.defer="cancellationReason" 
                                            id="cancellationReason" rows="3"
                                                      class="mt-1 block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-sky-500/50 sm:text-sm <?php $__errorArgs = ['cancellationReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cancellationReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button wire:click="cancelPrescription" type="button" wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                                <span wire:loading.remove><?php echo e(__('Confirmar Cancelamento')); ?></span>
                                <span wire:loading><?php echo e(__('Cancelando...')); ?></span>
                            </button>
                            <button wire:click="closeCancelModal" type="button" wire:loading.attr="disabled"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                                <?php echo e(__('Manter Solicitação')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!--[if BLOCK]><![endif]--><?php if($showReadyForPickupModal && $confirmingReadyForPickupPrescriptionId): ?>
        <div class="fixed inset-0 z-[100] flex items-end justify-center px-4 py-6 pointer-events-none sm:items-center sm:p-6" aria-labelledby="modal-title-ready" role="dialog" aria-modal="true">
            <div wire:click="closeReadyForPickupModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity pointer-events-auto" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pointer-events-auto">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="icon-[mdi--help-circle-outline] w-6 h-6 text-purple-600 dark:text-purple-400"></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-ready"><?php echo e(__('Confirmar Status')); ?></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 dark:text-neutral-300">
                                    <?php echo e(__('Deseja realmente marcar esta receita como "Pronta para Retirada"?')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="confirmReadyForPickup" type="button" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-purple-500 dark:hover:bg-purple-400 disabled:opacity-50">
                        <span wire:loading.remove wire:target="confirmReadyForPickup"><?php echo e(__('Confirmar')); ?></span>
                        <svg wire:loading wire:target="confirmReadyForPickup" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="closeReadyForPickupModal" type="button" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50">
                        <?php echo e(__('Voltar')); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <!--[if BLOCK]><![endif]--><?php if($showDeliveryModal && $deliveringPrescriptionId): ?>
        <div class="fixed inset-0 z-[100] flex items-end justify-center px-4 py-6 pointer-events-none sm:items-center sm:p-6" aria-labelledby="modal-title-delivery" role="dialog" aria-modal="true">
            <div wire:click="closeDeliveryModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity pointer-events-auto" aria-hidden="true"></div>
            <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pointer-events-auto">
                <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-teal-100 dark:bg-teal-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <span class="icon-[mdi--account-check-outline] w-6 h-6 text-teal-600 dark:text-teal-400"></span>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-delivery"><?php echo e(__('Registrar Entrega da Receita')); ?></h3>
                            <div class="mt-4 space-y-3">
                                <div>
                                    <label for="retrieved_by_name" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Nome de Quem Retirou')); ?> <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.defer="retrieved_by_name" id="retrieved_by_name"
                                           class="mt-1 border block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['retrieved_by_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['retrieved_by_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div>
                                    <label for="retrieved_by_document" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Documento de Quem Retirou (Opcional)')); ?></label>
                                    <input type="text" wire:model.defer="retrieved_by_document" id="retrieved_by_document" placeholder="Ex: CPF, RG"
                                           class="border mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['retrieved_by_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['retrieved_by_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="confirmDelivery" type="button" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-teal-500 dark:hover:bg-teal-400 disabled:opacity-50">
                        <span wire:loading.remove wire:target="confirmDelivery"><?php echo e(__('Confirmar Entrega')); ?></span>
                        <svg wire:loading wire:target="confirmDelivery" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button wire:click="closeDeliveryModal" type="button" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm disabled:opacity-50">
                        <?php echo e(__('Cancelar')); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <style> /* Estilos do scrollbar mantidos, ok */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/prescriptions/list-prescriptions.blade.php ENDPATH**/ ?>