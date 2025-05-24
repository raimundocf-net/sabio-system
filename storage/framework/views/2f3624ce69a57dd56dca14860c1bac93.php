<div>
    
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle ?? ($isEditing ? __('Editar Veículo') : __('Novo Veículo'))); ?>

     <?php $__env->endSlot(); ?>

    <div class="my-6 mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            
            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100 inline-flex items-center gap-2">
                        
                        <span class="icon-[ph--car-profile-fill] w-6 h-6 text-indigo-600 dark:text-sky-500"></span>
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
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="plate_number" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Placa')); ?> <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.plate_number" id="plate_number" placeholder="AAA0A00 ou ABC1234"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.plate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.plate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Marca')); ?> <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.brand" id="brand" placeholder="<?php echo e(__('Ex: Fiat')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Modelo')); ?> <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.model" id="model" placeholder="<?php echo e(__('Ex: Cronos, Sprinter')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="year_of_manufacture" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Ano Fabric.')); ?> <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.defer="form.year_of_manufacture" id="year_of_manufacture" placeholder="AAAA" min="1900" max="<?php echo e(date('Y') + 1); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.year_of_manufacture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.year_of_manufacture'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="model_year" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Ano Modelo')); ?></label>
                            <input type="number" wire:model.defer="form.model_year" id="model_year" placeholder="AAAA" min="1900" max="<?php echo e(date('Y') + 2); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.model_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.model_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="passenger_capacity" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Capacidade Passageiros')); ?> <span class="text-red-500">*</span></label>
                            <input type="number" wire:model.defer="form.passenger_capacity" id="passenger_capacity" placeholder="<?php echo e(__('Ex: 5')); ?>" min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.passenger_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.passenger_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="renavam" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">RENAVAM <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.renavam" id="renavam" placeholder="<?php echo e(__('9 ou 11 dígitos')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.renavam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.renavam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="chassis" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Chassi')); ?> <span class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="form.chassis" id="chassis" placeholder="<?php echo e(__('17 caracteres')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.chassis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.chassis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Cor')); ?></label>
                            <input type="text" wire:model.defer="form.color" id="color" placeholder="<?php echo e(__('Ex: Prata')); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Tipo do Veículo')); ?> <span class="text-red-500">*</span></label>
                            <select wire:model.defer="form.type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('Selecione o Tipo...')); ?></option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $vehicleTypeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="availability_status" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Status de Disponibilidade')); ?> <span class="text-red-500">*</span></label>
                            <select wire:model.defer="form.availability_status" id="availability_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 pl-3 pr-10 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.availability_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value=""><?php echo e(__('Selecione o Status...')); ?></option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availabilityStatusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.availability_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="acquisition_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Data de Aquisição')); ?></label>
                            <input type="date" wire:model.defer="form.acquisition_date" id="acquisition_date"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.acquisition_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.acquisition_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="current_mileage" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('KM Atual')); ?></label>
                            <input type="number" wire:model.defer="form.current_mileage" id="current_mileage" placeholder="<?php echo e(__('Ex: 120500')); ?>" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.current_mileage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.current_mileage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div>
                            <label for="last_inspection_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Última Revisão')); ?></label>
                            <input type="date" wire:model.defer="form.last_inspection_date" id="last_inspection_date"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.last_inspection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.last_inspection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="pt-2">
                        <label for="is_pwd_accessible" class="flex items-center">
                            <input type="checkbox" wire:model.defer="form.is_pwd_accessible" id="is_pwd_accessible"
                                   class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-neutral-500 rounded focus:ring-indigo-500 dark:focus:ring-sky-500 bg-white dark:bg-neutral-700 dark:checked:bg-sky-500 dark:checked:border-sky-500">
                            <span class="ml-2 rtl:ml-0 rtl:mr-2 text-sm text-gray-700 dark:text-neutral-300"><?php echo e(__('Veículo adaptado para PNE (Pessoa com Necessidades Especiais)')); ?></span>
                        </label>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.is_pwd_accessible'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Observações')); ?></label>
                        <div class="mt-2">
                            <textarea wire:model.defer="form.notes" id="notes" rows="3" placeholder="<?php echo e(__('Detalhes adicionais, histórico de problemas, etc...')); ?>"
                                      class="block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2.5 px-3 bg-white dark:bg-neutral-700 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 <?php $__errorArgs = ['form.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
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
                            <?php echo e($isEditing ? __('Atualizar Veículo') : __('Salvar Veículo')); ?>

                        </span>
                        <span wire:loading wire:target="save">
                            <?php echo e($isEditing ? __('Atualizando...') : __('Salvando...')); ?>

                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/vehicles/manage-vehicle.blade.php ENDPATH**/ ?>