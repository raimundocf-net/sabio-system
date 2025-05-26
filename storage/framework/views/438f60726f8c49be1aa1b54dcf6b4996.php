
<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-100"><?php echo e($pageTitle); ?></h1>
        </div>

        <div class="bg-white dark:bg-neutral-800 shadow-md sm:rounded-lg">
            <form wire:submit.prevent="save">
                <div class="p-6">

                    
                    <fieldset class="space-y-6">
                        <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700"><?php echo e(__('Informações do Usuário')); ?></legend>

                        
                        <div>
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Nome do Usuário')); ?></label>
                            <div class="mt-2">
                                <input type="text" wire:model.defer="name" id="name" placeholder="<?php echo e(__('Digite o nome completo do usuário')); ?>"
                                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('E-mail')); ?></label>
                            <div class="mt-2">
                                <input type="email" wire:model.defer="email" id="email" placeholder="<?php echo e(__('exemplo@dominio.com')); ?>"
                                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="unit_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Unidade de Saúde')); ?></label>
                                <div class="mt-2">
                                    <select wire:model.defer="unit_id" id="unit_id"
                                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value=""><?php echo e(__('Selecione a unidade')); ?></option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $unitsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            
                            <div>
                                <label for="role" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Papel / Cargo')); ?></label>
                                <div class="mt-2">
                                    <select wire:model.defer="role" id="role"
                                            class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value=""><?php echo e(__('Selecione o papel')); ?></option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($roleKey); ?>"><?php echo e($roleLabel); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="cns" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('CNS do Usuário')); ?></label>
                                <div class="mt-2">
                                    <input type="text" wire:model.defer="cns" id="cns" placeholder="<?php echo e(__('Digite o CNS (opcional)')); ?>"
                                           class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['cns'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cns'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            
                            <div>
                                <label for="cbo" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('CBO do Usuário')); ?></label>
                                <div class="mt-2">
                                    <input type="text" wire:model.defer="cbo" id="cbo" placeholder="<?php echo e(__('Digite o CBO (opcional)')); ?>"
                                           class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['cbo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cbo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </fieldset>

                    
                    <fieldset class="space-y-6 mt-8">
                        <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700">
                            <?php echo e($isEditing ? __('Alterar Senha (Opcional)') : __('Definir Senha')); ?>

                        </legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Senha')); ?></label>
                                <div class="mt-2">
                                    <input type="password" wire:model.defer="password" id="password" placeholder="<?php echo e($isEditing ? __('Deixe em branco para não alterar') : __('Mínimo 8 caracteres')); ?>"
                                           class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Confirmação da Senha')); ?></label>
                                <div class="mt-2">
                                    <input type="password" wire:model.defer="password_confirmation" id="password_confirmation" placeholder="<?php echo e(__('Repita a senha')); ?>"
                                           class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ring-red-500 dark:ring-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </fieldset>

                    

                </div>

                
                <div class="flex items-center justify-end space-x-3 bg-gray-50 dark:bg-neutral-800/50 px-6 py-4 border-t border-gray-200 dark:border-neutral-700 sm:rounded-b-lg">
                    <button type="button" wire:click="cancel" class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-neutral-500 bg-white dark:bg-neutral-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 dark:text-neutral-200 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25">
                        <span class="icon-[mdi--cancel] w-4 h-4 mr-2"></span>
                        <?php echo e(__('Cancelar')); ?>

                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 dark:bg-sky-500 px-4 py-2 text-xs font-semibold text-white uppercase tracking-widest shadow-sm transition-colors hover:bg-blue-500 dark:hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-sky-600 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 active:bg-blue-700 dark:active:bg-sky-300 disabled:opacity-25">
                        <span class="icon-[mdi--content-save] w-4 h-4 mr-2"></span>
                        
                        <?php echo e($isEditing ? __('Salvar Alterações') : __('Criar Usuário')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/users/manage-user.blade.php ENDPATH**/ ?>