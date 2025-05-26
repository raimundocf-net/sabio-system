




<fieldset class="space-y-6">
    <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700"><?php echo e(__('Detalhes da Viagem')); ?></legend>

    
    <div class="relative flex items-start">
        <div class="flex h-6 items-center">
            <input id="needs_companion" wire:model.live="form.needs_companion" type="checkbox"
                   class="h-4 w-4 rounded border-gray-300 dark:border-neutral-600 text-indigo-600 dark:text-sky-500 focus:ring-indigo-600 dark:focus:ring-sky-500 dark:bg-neutral-700 dark:checked:bg-sky-500">
        </div>
        <div class="ml-3 text-sm leading-6">
            <label for="needs_companion" class="font-medium text-gray-900 dark:text-neutral-200"><?php echo e(__('Precisa de Acompanhante?')); ?></label>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($form['needs_companion']): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-dashed dark:border-neutral-600 rounded-md">
            <div>
                <label for="companion_name" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Nome do Acompanhante')); ?> <span class="text-red-500">*</span></label>
                <input type="text" wire:model.defer="form.companion_name" id="companion_name"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm  dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.companion_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.companion_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div>
                <label for="companion_cpf" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('CPF do Acompanhante')); ?></label>
                <input type="text" wire:model.defer="form.companion_cpf" id="companion_cpf" x-mask="999.999.999-99"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.companion_cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.companion_cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-3">
            <label for="destination_address" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Endereço de Destino Completo')); ?> <span class="text-red-500">*</span></label>
            <input type="text" wire:model.defer="form.destination_address" id="destination_address" placeholder="Rua, Número, Bairro, Ponto de Referência"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.destination_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div>
            <label for="destination_city" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Cidade de Destino')); ?> <span class="text-red-500">*</span></label>
            <input type="text" wire:model.defer="form.destination_city" id="destination_city"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.destination_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div>
            <label for="destination_state" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Estado de Destino (UF)')); ?> <span class="text-red-500">*</span></label>
            <select wire:model.defer="form.destination_state" id="destination_state"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 pl-3 pr-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.destination_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Selecione...</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $stateOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uf => $nome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($uf); ?>"><?php echo e($nome); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Motivo da Viagem')); ?> <span class="text-red-500">*</span></label>
            <textarea wire:model.defer="form.reason" id="reason" rows="3" placeholder="Ex: Consulta médica, Exame, Tratamento"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div>
            <label for="procedure_type" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Tipo de Procedimento')); ?> <span class="text-red-500">*</span></label>
            <select wire:model.defer="form.procedure_type" id="procedure_type"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 pl-3 pr-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.procedure_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Selecione...</option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $procedureTypeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.procedure_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="departure_location" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Local de Embarque')); ?> <span class="text-red-500">*</span></label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <select wire:model.defer="form.departure_location" id="departure_location"
                        class="block w-full rounded-none rounded-l-md border-gray-300 dark:border-neutral-600 py-2 pl-3 pr-8 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.departure_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value=""><?php echo e(__('Selecione o local')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $boardingLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($location->name); ?>"><?php echo e($location->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <button type="button" wire:click="openAddBoardingLocationModal"
                        class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700/50 px-3 py-2 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:bg-gray-100 dark:hover:bg-neutral-600 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <span class="icon-[mdi--plus] w-5 h-5 text-gray-400 dark:text-neutral-400" aria-hidden="true"></span>
                    <span><?php echo e(__('Novo')); ?></span>
                </button>
            </div>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.departure_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div>
            <label for="appointment_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Data e Hora do Compromisso')); ?> <span class="text-red-500">*</span></label>
            <input type="datetime-local" wire:model.defer="form.appointment_datetime" id="appointment_datetime"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.appointment_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.appointment_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="desired_departure_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Data/Hora Desejada de Saída')); ?></label>
            <input type="datetime-local" wire:model.defer="form.desired_departure_datetime" id="desired_departure_datetime"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.desired_departure_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.desired_departure_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div>
            <label for="desired_return_datetime" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Data/Hora Desejada de Retorno')); ?></label>
            <input type="datetime-local" wire:model.defer="form.desired_return_datetime" id="desired_return_datetime"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.desired_return_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.desired_return_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    
    <div>
        <label for="number_of_passengers" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Número Total de Passageiros (incluindo paciente)')); ?> <span class="text-red-500">*</span></label>
        <input type="number" wire:model.live="form.number_of_passengers" id="number_of_passengers" min="1"
               class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.number_of_passengers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            <?php if($form['needs_companion']): echo 'readonly'; endif; ?>> 
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.number_of_passengers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        <!--[if BLOCK]><![endif]--><?php if($form['needs_companion']): ?>
            <p class="mt-1 text-xs text-indigo-600 dark:text-sky-400"><?php echo e(__('Automático: Paciente + Acompanhante. Para mais acompanhantes, justifique nas observações.')); ?></p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</fieldset>


<!--[if BLOCK]><![endif]--><?php if($showAddBoardingLocationModal): ?>
    <div class="fixed inset-0 z-[100] flex items-end justify-center px-4 py-6 pointer-events-none sm:items-center sm:p-6"
         aria-labelledby="modal-title-add-boarding-location" role="dialog" aria-modal="true">
        <div wire:click="closeAddBoardingLocationModal"
             class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity pointer-events-auto"
             aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full pointer-events-auto">
            <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-sky-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="icon-[mdi--map-marker-plus-outline] w-6 h-6 text-indigo-600 dark:text-sky-400"></span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-add-boarding-location">
                            <?php echo e(__('Adicionar Novo Local de Embarque')); ?>

                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="newBoardingLocationName" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Nome do Local')); ?> <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="newBoardingLocationName" id="newBoardingLocationName"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['newBoardingLocationName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newBoardingLocationName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div>
                                <label for="newBoardingLocationAddress" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Endereço do Local (Opcional)')); ?></label>
                                <input type="text" wire:model.defer="newBoardingLocationAddress" id="newBoardingLocationAddress"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['newBoardingLocationAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newBoardingLocationAddress'];
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
                <button wire:click="saveNewBoardingLocation" type="button" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-sky-500 dark:hover:bg-sky-400 dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveNewBoardingLocation"><?php echo e(__('Salvar Local')); ?></span>
                    <svg wire:loading wire:target="saveNewBoardingLocation" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <button wire:click="closeAddBoardingLocationModal" type="button" wire:loading.attr="disabled"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm dark:focus:ring-offset-neutral-800 disabled:opacity-50">
                    <?php echo e(__('Cancelar')); ?>

                </button>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH /var/www/html/system/resources/views/livewire/travel-requests/_form-fields.blade.php ENDPATH**/ ?>