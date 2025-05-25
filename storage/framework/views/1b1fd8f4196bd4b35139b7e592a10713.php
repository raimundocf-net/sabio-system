<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    
    <div class="flex flex-col gap-4 mb-6 mt-4 sm:flex-row sm:items-center sm:justify-between px-4 sm:px-6 lg:px-8">

        
        <div class="flex justify-between items-center w-full sm:w-auto">
            <h1 class="inline-flex items-center gap-2 text-lg font-semibold text-gray-900 dark:text-neutral-100">
                <span class="icon-[mdi--clipboard-text-clock-outline] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
                <?php echo e($pageTitle); ?>

            </h1>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\TravelRequest::class)): ?>
                <div class="sm:hidden">
                    <a href="<?php echo e(route('travel-requests.create')); ?>"
                       wire:navigate
                       class="ml-2 inline-flex items-center justify-center gap-1 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                        <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                        <?php echo e(__('Nova')); ?>

                    </a>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="w-full sm:flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 items-center">
            
            <div class="w-full">
                <label for="perPage" class="sr-only"><?php echo e(__('Itens por página')); ?></label>
                <select wire:model.live="perPage" id="perPage" title="<?php echo e(__('Itens por página')); ?>" class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value="10">10 <?php echo e(__('por pág.')); ?></option>
                    <option value="15">15 <?php echo e(__('por pág.')); ?></option>
                    <option value="25">25 <?php echo e(__('por pág.')); ?></option>
                    <option value="50">50 <?php echo e(__('por pág.')); ?></option>
                </select>
            </div>

            
            <div class="w-full">
                <label for="filterStatus" class="sr-only"><?php echo e(__('Filtrar por status')); ?></label>
                <select wire:model.live="filterStatus" id="filterStatus" title="<?php echo e(__('Filtrar por status')); ?>"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value=""><?php echo e(__('Todo Status')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            
            <div class="w-full">
                <label for="filterProcedureType" class="sr-only"><?php echo e(__('Filtrar por tipo de procedimento')); ?></label>
                <select wire:model.live="filterProcedureType" id="filterProcedureType" title="<?php echo e(__('Filtrar por tipo de procedimento')); ?>"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value=""><?php echo e(__('Todo Tipo Proced.')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $procedureTypeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            
            <div class="w-full">
                <label for="filterDateOption" class="sr-only"><?php echo e(__('Filtrar por data de')); ?></label>
                <select wire:model.live="filterDateOption" id="filterDateOption" title="<?php echo e(__('Filtrar por data de')); ?>"
                        class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 pl-3 pr-8 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                    <option value=""><?php echo e(__('Filtrar Data Por...')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $dateFilterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            
            <div class="w-full lg:col-span-2"> 
                <label for="searchTerm" class="sr-only"><?php echo e(__('Termo de Busca')); ?></label>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm"
                       placeholder="<?php echo e(__('Buscar por ID, Paciente, Destino...')); ?>"
                       class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500" />
            </div>

            
            <!--[if BLOCK]><![endif]--><?php if($filterDateOption): ?>
                <div class="w-full">
                    <label for="filterStartDate" class="sr-only"><?php echo e(__('Data Início')); ?></label>
                    <input type="date" wire:model.live="filterStartDate" id="filterStartDate" title="<?php echo e(__('Data Início')); ?>"
                           class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                </div>
                <div class="w-full">
                    <label for="filterEndDate" class="sr-only"><?php echo e(__('Data Fim')); ?></label>
                    <input type="date" wire:model.live="filterEndDate" id="filterEndDate" title="<?php echo e(__('Data Fim')); ?>"
                           class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-1.5 px-3 text-sm text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500">
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\TravelRequest::class)): ?>
            <div class="hidden sm:flex w-full sm:w-auto sm:justify-end mt-3 sm:mt-0">
                <a href="<?php echo e(route('travel-requests.create')); ?>"
                   wire:navigate
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 dark:bg-sky-500 dark:hover:bg-sky-400 px-3 py-1.5 rounded-md shadow-sm transition-colors duration-150">
                    <span class="icon-[mdi--plus-box-outline] w-5 h-5"></span>
                    <?php echo e(__('Nova Solicitação')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>

    
    <?php echo $__env->make('livewire.partials.session-messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-neutral-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider">#<?php echo e(__('ID')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Paciente')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Destino')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Data Compromisso')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Tipo Proced.')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Status')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Solicitante')); ?></th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-neutral-300 uppercase tracking-wider"><?php echo e(__('Data Solic.')); ?></th>
                    <th scope="col" class="relative px-3 py-3"><span class="sr-only"><?php echo e(__('Ações')); ?></span></th>
                </tr>
                </thead>
                <tbody class="bg-white dark:bg-neutral-800 divide-y divide-gray-200 dark:divide-neutral-700">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $travelRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr wire:key="travel-request-row-<?php echo e($request->id); ?>" class="hover:bg-gray-50 dark:hover:bg-neutral-700/30 transition-colors duration-150">
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-neutral-100"><?php echo e($request->id); ?></td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <div class="flex flex-col">
                                <span><?php echo e($request->citizen?->name ?? __('N/D')); ?></span>
                                <span class="text-xs text-gray-500 dark:text-neutral-400">
                                    CPF: <?php echo e($request->citizen?->cpf ?? __('N/D')); ?>

                                </span>
                            </div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <?php echo e($request->destination_city); ?> / <?php echo e($request->destination_state); ?>

                            <div class="text-xs text-gray-500 dark:text-neutral-400 truncate max-w-xs" title="<?php echo e($request->destination_address); ?>"><?php echo e(Str::limit($request->destination_address, 30)); ?></div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <?php echo e($request->appointment_datetime ? \Carbon\Carbon::parse($request->appointment_datetime)->format('d/m/Y H:i') : __('N/D')); ?>

                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300">
                            <?php echo e($request->procedure_type?->label() ?? ($request->procedure_type ?: __('N/D'))); ?>

                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm">
                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($request->status?->badgeClasses() ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'); ?>">
                                <?php echo e($request->status?->label() ?? ($request->status ?: __('N/D'))); ?>

                            </span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300"><?php echo e($request->requester?->name ?? __('N/D')); ?></td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-neutral-300"><?php echo e($request->created_at ? $request->created_at->format('d/m/Y H:i') : __('N/D')); ?></td>
                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium space-x-1 rtl:space-x-reverse">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $request)): ?>
                                <a href="<?php echo e(route('travel-requests.edit', $request->id)); ?>"
                                   wire:navigate title="<?php echo e(__('Editar Solicitação')); ?>"
                                   class="inline-flex items-center justify-center p-1.5 rounded-full text-indigo-600 hover:bg-indigo-100 dark:text-indigo-400 dark:hover:bg-neutral-600 transition-colors">
                                    <span class="icon-[tabler--pencil] w-5 h-5"></span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $request)): ?> 
                            <!--[if BLOCK]><![endif]--><?php if(!in_array($request->status, [\App\Enums\TravelRequestStatus::CANCELLED_BY_USER, \App\Enums\TravelRequestStatus::CANCELLED_BY_ADMIN, \App\Enums\TravelRequestStatus::SCHEDULED])): ?> 
                            <button wire:click="openCancelModal(<?php echo e($request->id); ?>)"
                                    title="<?php echo e(__('Cancelar Solicitação')); ?>"
                                    class="inline-flex items-center justify-center p-1.5 rounded-full text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-neutral-600 transition-colors">
                                <span class="icon-[mdi--cancel-bold] w-5 h-5"></span>
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-16 text-center text-sm text-gray-500 dark:text-neutral-400">
                            <div class="flex flex-col items-center">
                                <span class="icon-[mdi--alert-rhombus-outline] text-6xl text-gray-300 dark:text-neutral-600 mb-3"></span>
                                <?php echo e(__('Nenhuma solicitação de viagem encontrada.')); ?>

                                <!--[if BLOCK]><![endif]--><?php if(empty($searchTerm) && empty($filterStatus) && empty($filterProcedureType) && empty($filterDateOption)): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\TravelRequest::class)): ?>
                                        <p class="mt-2 text-xs"><?php echo e(__('Clique em "Nova Solicitação" para adicionar a primeira.')); ?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="mt-2 text-xs"><?php echo e(__('Tente ajustar seus filtros ou termo de busca.')); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </tbody>
            </table>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($travelRequests->hasPages()): ?>
            <div class="py-4 px-1">
                <?php echo e($travelRequests->links(data: ['scrollTo' => false])); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showCancelModal && $cancellingTravelRequest): ?>
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-cancel-request" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div wire:click="closeCancelModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-800/30 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="icon-[mdi--alert-outline] w-6 h-6 text-red-600 dark:text-red-400"></span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-cancel-request"><?php echo e(__('Confirmar Cancelamento da Solicitação')); ?> #<?php echo e($cancellingTravelRequest->id); ?></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 dark:text-neutral-300">
                                        <?php echo e(__('Paciente:')); ?> <strong><?php echo e($cancellingTravelRequest->citizen?->name); ?></strong><br>
                                        <?php echo e(__('Destino:')); ?> <strong><?php echo e($cancellingTravelRequest->destination_city); ?> - <?php echo e($cancellingTravelRequest->destination_state); ?></strong><br>
                                        <?php echo e(__('Data Compromisso:')); ?> <strong><?php echo e($cancellingTravelRequest->appointment_datetime ? \Carbon\Carbon::parse($cancellingTravelRequest->appointment_datetime)->format('d/m/Y H:i') : 'N/D'); ?></strong>
                                    </p>
                                    <div class="mt-3">
                                        <label for="cancellationReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Motivo do Cancelamento')); ?> <span class="text-red-500">*</span></label>
                                        <textarea wire:model.lazy="cancellationReason" id="cancellationReason" rows="3"
                                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-red-500 dark:focus:border-red-400 focus:ring-1 focus:ring-red-500 dark:focus:ring-red-400 <?php $__errorArgs = ['cancellationReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cancellationReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">
                                        <?php echo e(__('Esta ação não poderá ser desfeita facilmente. A solicitação será marcada como cancelada.')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-neutral-800/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="cancelTravelRequest" type="button" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-red-500 dark:hover:bg-red-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                            <span wire:loading.remove wire:target="cancelTravelRequest"><?php echo e(__('Confirmar Cancelamento')); ?></span>
                            <svg wire:loading wire:target="cancelTravelRequest" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
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
</div><?php /**PATH /var/www/html/system/resources/views/livewire/travel-requests/index-travel-request.blade.php ENDPATH**/ ?>