<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg overflow-hidden">

                
                <div class="px-6 py-5 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-100 inline-flex items-center gap-3">
                            <span class="icon-[mdi--clipboard-text-clock-outline] w-7 h-7 text-indigo-600 dark:text-sky-500"></span>
                            <?php echo e($pageTitle); ?>

                        </h1>
                        <a href="<?php echo e(route('travel-requests.create.search-citizen')); ?>" wire:navigate
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-sky-400 dark:hover:text-sky-300 inline-flex items-center gap-1.5 transition-colors duration-150">
                            <span class="icon-[mdi--account-search-outline] w-5 h-5"></span>
                            <?php echo e(__('Alterar Paciente / Nova Busca')); ?>

                        </a>
                    </div>
                </div>

                
                <form wire:submit.prevent="save">
                    <div class="p-6 sm:p-8 space-y-8">

                        
                        <!--[if BLOCK]><![endif]--><?php if($selectedCitizen): ?>
                            <section aria-labelledby="patient-info-heading" class="bg-slate-50 dark:bg-neutral-700/30 p-5 rounded-md border border-slate-200 dark:border-neutral-700">
                                <h2 id="patient-info-heading" class="text-lg font-semibold text-gray-800 dark:text-neutral-100 mb-3">
                                    <?php echo e(__('1. Paciente Selecionado')); ?>

                                </h2>
                                <div class="text-sm">
                                    <p class="text-gray-700 dark:text-neutral-200">
                                        <strong class="font-medium"><?php echo e(__('Nome:')); ?></strong> <?php echo e($selectedCitizen->nome_do_cidadao); ?>

                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-neutral-400 mt-1">
                                        CPF: <?php echo e($selectedCitizen->cpf ?? __('N/D')); ?> | CNS: <?php echo e($selectedCitizen->cns ?? __('N/D')); ?> | Idade: <?php echo e($selectedCitizen->idade ? : __('N/D')); ?>

                                    </p>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.citizen_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-xs text-red-500 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </section>
                        <?php else: ?>
                            <div class="p-4 border-l-4 border-red-500 bg-red-100 dark:bg-red-800/40 text-red-700 dark:text-red-300 rounded-md" role="alert">
                                <p class="font-medium"><?php echo e(__('Nenhum cidadão selecionado.')); ?>

                                    <a href="<?php echo e(route('travel-requests.create.search-citizen')); ?>" wire:navigate class="font-semibold hover:underline"><?php echo e(__('Por favor, volte e selecione um cidadão.')); ?></a>
                                </p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        
                        <!--[if BLOCK]><![endif]--><?php if($selectedCitizen): ?>
                            
                            <section aria-labelledby="trip-details-heading" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="trip-details-heading" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    <?php echo e(__('2. Detalhes da Viagem')); ?>

                                </h2>
                                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                    <div class="sm:col-span-6">
                                        <label for="form_reason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Motivo/Propósito da Viagem')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <textarea wire:model.defer="form.reason" id="form_reason" rows="3" placeholder="<?php echo e(__('Ex: Consulta com especialista, Exame de alta complexidade...')); ?>"
                                                      class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="form_procedure_type" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Tipo de Procedimento')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select wire:model.defer="form.procedure_type" id="form_procedure_type"
                                                    class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.procedure_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value=""><?php echo e(__('Selecione...')); ?></option>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $procedureTypeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </select>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.procedure_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    
                                    <div class="sm:col-span-3">
                                        <label for="form_departure_location" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Local de Embarque')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2 flex items-center gap-x-2">
                                            <select wire:model="form.departure_location" id="form_departure_location" 
                                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.departure_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value=""><?php echo e(__('Selecione um local...')); ?></option>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $boardingLocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($location->name); ?>"><?php echo e($location->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </select>
                                            <button type="button" wire:click="openAddBoardingLocationModal"
                                                    class="shrink-0 inline-flex items-center justify-center p-2 rounded-md bg-indigo-600 dark:bg-sky-500 text-white hover:bg-indigo-700 dark:hover:bg-sky-400 transition-colors"
                                                    title="<?php echo e(__('Adicionar Novo Local de Embarque')); ?>">
                                                <span class="icon-[mdi--plus] w-5 h-5"></span>
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
                                    


                                    <div class="sm:col-span-3">
                                        <label for="form_destination_address" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Endereço de Destino')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" wire:model.defer="form.destination_address" id="form_destination_address" placeholder="<?php echo e(__('Rua, Número, Bairro, Complemento...')); ?>"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.destination_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="form_destination_city" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Cidade Destino')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="text" wire:model.defer="form.destination_city" id="form_destination_city"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.destination_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="sm:col-span-1">
                                        <label for="form_destination_state" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('UF Destino')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <select wire:model.defer="form.destination_state" id="form_destination_state"
                                                    class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.destination_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="MG"><?php echo e(__('MG')); ?></option>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $stateOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </select>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.destination_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="form_desired_departure_datetime" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Previsão de Saída da Origem')); ?></label>
                                        <div class="mt-2">
                                            <input type="datetime-local" wire:model.defer="form.desired_departure_datetime" id="form_desired_departure_datetime"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.desired_departure_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.desired_departure_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label for="form_appointment_datetime" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Data/Hora do Compromisso no Destino')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="datetime-local" wire:model.defer="form.appointment_datetime" id="form_appointment_datetime"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.appointment_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
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
                            </section>

                            
                            <section aria-labelledby="companion-details-heading" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="companion-details-heading" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    <?php echo e(__('3. Acompanhante e Passageiros')); ?>

                                </h2>
                                <div class="space-y-6">
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input id="needs_companion_form_input" wire:model.live="form.needs_companion" wire:change="$dispatch('updated-form-needs-companion', { value: $event.target.checked })" type="checkbox"
                                                   class="h-4 w-4 rounded border-gray-300 dark:border-neutral-500 text-indigo-600 dark:text-sky-500 focus:ring-indigo-600 dark:focus:ring-sky-500 bg-white dark:bg-neutral-700 dark:checked:bg-sky-500 dark:checked:border-sky-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="needs_companion_form_input" class="font-medium text-gray-900 dark:text-neutral-200"><?php echo e(__('Precisa de Acompanhante?')); ?></label>
                                        </div>
                                    </div>

                                    <!--[if BLOCK]><![endif]--><?php if($form['needs_companion']): ?>
                                        <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                                            <div class="sm:col-span-3">
                                                <label for="companion_name_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Nome do Acompanhante')); ?> <span class="text-red-500">*</span></label>
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="form.companion_name" id="companion_name_form_input"
                                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.companion_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                </div>
                                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.companion_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                            <div class="sm:col-span-3">
                                                <label for="companion_cpf_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('CPF do Acompanhante (Opcional)')); ?></label>
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="form.companion_cpf" id="companion_cpf_form_input" placeholder="000.000.000-00"
                                                           class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.companion_cpf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                </div>
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
                                    <div class="sm:col-span-2">
                                        <label for="number_of_passengers_form_input" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Número Total de Passageiros')); ?> <span class="text-red-500">*</span></label>
                                        <div class="mt-2">
                                            <input type="number" wire:model.defer="form.number_of_passengers" id="number_of_passengers_form_input" min="1"
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.number_of_passengers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   <?php if(!$form['needs_companion']): ?> readonly <?php endif; ?> >
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.number_of_passengers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </section>

                            
                            <section aria-labelledby="docs-obs-heading" class="space-y-6 pt-6 border-t border-gray-300 dark:border-neutral-700">
                                <h2 id="docs-obs-heading" class="text-lg font-semibold leading-7 text-gray-900 dark:text-neutral-100">
                                    <?php echo e(__('4. Documentação e Observações')); ?>

                                </h2>
                                <div class="space-y-6">
                                    <div>
                                        <label for="referralDocumentFile_form" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            <?php echo e(($form['referral_document_path'] ?? null) ? __('Alterar Foto da Guia/Encaminhamento') : __('Anexar Foto da Guia/Encaminhamento (Opcional)')); ?>

                                        </label>
                                        <div class="mt-2">
                                            <input type="file" wire:model="referralDocumentFile" id="referralDocumentFile_form"
                                                   class="block w-full text-sm text-gray-900 dark:text-neutral-300 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 file:bg-gray-100 dark:file:bg-neutral-600 file:text-gray-700 dark:file:text-neutral-200 file:border-0 file:py-2.5 file:px-4 file:mr-4 dark:file:mr-0 dark:file:ml-4 hover:file:bg-gray-200 dark:hover:file:bg-neutral-500 <?php $__errorArgs = ['referralDocumentFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        </div>
                                        <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                            <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                            <?php echo e(__('Carregando imagem...')); ?>

                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['referralDocumentFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                                        <!--[if BLOCK]><![endif]--><?php if($referralDocumentFile && method_exists($referralDocumentFile, 'temporaryUrl')): ?>
                                            <div class="mt-3">
                                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1"><?php echo e(__('Pré-visualização da Nova Imagem:')); ?></p>
                                                <img src="<?php echo e($referralDocumentFile->temporaryUrl()); ?>" alt="<?php echo e(__('Preview da nova guia')); ?>" class="max-h-48 w-auto rounded border border-gray-300 dark:border-neutral-600 shadow-sm">
                                            </div>
                                        <?php elseif($form['referral_document_path'] ?? null): ?>
                                            <div class="mt-3">
                                                <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1"><?php echo e(__('Guia Anexada:')); ?></p>
                                                <a href="<?php echo e(Storage::url($form['referral_document_path'])); ?>" target="_blank" class="inline-block">
                                                    <img src="<?php echo e(Storage::url($form['referral_document_path'])); ?>" alt="<?php echo e(__('Preview da guia anexada')); ?>" class="max-h-48 w-auto rounded border border-gray-300 dark:border-neutral-600 shadow-sm hover:ring-2 hover:ring-indigo-500 dark:hover:ring-sky-500">
                                                </a>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <div>
                                        <label for="observations_form" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Observações Gerais da Atendente')); ?></label>
                                        <div class="mt-2">
                                            <textarea wire:model.defer="form.observations" id="observations_form" rows="4" placeholder="<?php echo e(__('Informações adicionais sobre a necessidade da viagem, restrições do paciente, etc...')); ?>"
                                                      class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 bg-white dark:bg-neutral-700 <?php $__errorArgs = ['form.observations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                        </div>
                                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.observations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </section>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if($selectedCitizen): ?>
                        <div class="px-6 py-4 bg-gray-100 dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-700 flex items-center justify-end gap-x-4">
                            <a href="<?php echo e(route('travel-requests.create.search-citizen')); ?>" wire:navigate
                               class="rounded-md bg-white dark:bg-neutral-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                                <span class="icon-[mdi--arrow-left] w-4 h-4 mr-1.5 rtl:mr-0 rtl:ml-1.5 inline-block align-middle"></span>
                                <?php echo e(__('Voltar para Busca')); ?>

                            </a>
                            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                    class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70 transition-colors">
                                <span wire:loading.remove wire:target="save">
                                    <span class="icon-[mdi--content-save-outline] w-5 h-5 mr-1.5 rtl:mr-0 rtl:ml-1.5 -ml-0.5"></span>
                                    <?php echo e(__('Salvar Solicitação')); ?>

                                </span>
                                <span wire:loading wire:target="save" class="inline-flex items-center">
                                    <span class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                    <?php echo e(__('Salvando...')); ?>

                                </span>
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </form>
            </div>
        </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($showAddBoardingLocationModal): ?>
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
        x-data="{ show: <?php if ((object) ('showAddBoardingLocationModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showAddBoardingLocationModal'->value()); ?>')<?php echo e('showAddBoardingLocationModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showAddBoardingLocationModal'); ?>')<?php endif; ?> }"
             x-show="show"
             x-trap.noscroll="show"
             x-on:keydown.escape.window="show = false"
             style="display: none;" >

            
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" wire:click="closeAddBoardingLocationModal"></div>

            
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl transform transition-all sm:max-w-lg w-full p-6 space-y-4"
                 x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-neutral-100"><?php echo e(__('Adicionar Novo Local de Embarque')); ?></h3>

                <div>
                    <label for="newBoardingLocationName" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Nome do Local')); ?> <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="newBoardingLocationName" id="newBoardingLocationName"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['newBoardingLocationName'];
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
                    <label for="newBoardingLocationAddress" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Endereço/Ponto de Referência (Opcional)')); ?></label>
                    <textarea wire:model.defer="newBoardingLocationAddress" id="newBoardingLocationAddress" rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['newBoardingLocationAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newBoardingLocationAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="flex justify-end space-x-3 pt-3">
                    <button type="button" wire:click="closeAddBoardingLocationModal"
                            class="rounded-md bg-white dark:bg-neutral-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                        <?php echo e(__('Cancelar')); ?>

                    </button>
                    <button type="button" wire:click="saveNewBoardingLocation" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                        <span wire:loading wire:target="saveNewBoardingLocation" class="icon-[svg-spinners--6-dots-scale-middle] w-4 h-4 mr-1.5"></span>
                        <span wire:loading.remove wire:target="saveNewBoardingLocation"><?php echo e(__('Salvar Local')); ?></span>
                        <span wire:loading wire:target="saveNewBoardingLocation"><?php echo e(__('Salvando...')); ?></span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
</div><?php /**PATH /var/www/html/system/resources/views/livewire/travel-requests/travel-request-form.blade.php ENDPATH**/ ?>