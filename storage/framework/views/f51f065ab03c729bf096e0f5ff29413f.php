<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white dark:bg-neutral-800 shadow-xl sm:rounded-lg">
            <div class="p-6 border-b dark:border-neutral-700">
                
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100"><?php echo e($pageTitle); ?></h2>
                    <a href="<?php echo e(route('prescriptions.request.search')); ?>" wire:navigate class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        <?php echo e(__('Alterar Cidadão')); ?>

                    </a>
                </div>
                <div class="mt-2 text-sm text-gray-600 dark:text-neutral-300">
                    <p><strong><?php echo e(__('CPF:')); ?></strong> <?php echo e($citizen->cpf ?: 'N/A'); ?></p>
                    <p><strong><?php echo e(__('CNS:')); ?></strong> <?php echo e($citizen->cns ?: 'N/A'); ?></p>
                    <p><strong><?php echo e(__('Nascimento:')); ?></strong> <?php echo e($citizen->date_of_birth ? \Carbon\Carbon::createFromFormat('d/m/Y', $citizen->date_of_birth)->format('d/m/Y') : 'N/A'); ?></p>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($currentUserUnitName): ?>
                <form wire:submit.prevent="submitPrescriptionRequest">
                    <div class="p-6 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Unidade de Saúde Solicitante')); ?></label>
                                <div class="mt-2">
                                    <p class="block w-full rounded-md border-0 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700/50 py-2.5 px-3 text-gray-700 dark:text-neutral-300 shadow-sm sm:text-sm">
                                        <?php echo e($currentUserUnitName); ?>

                                    </p>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div>
                                <label for="doctor_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Atribuir ao Médico (Opcional)')); ?></label>
                                <div class="mt-2">
                                    <select wire:model.defer="doctor_id" id="doctor_id" class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 shadow-sm focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value=""><?php echo e(__('Nenhum médico específico')); ?></option>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $doctorsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($doctor->id); ?>"><?php echo e($doctor->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </select>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        
                        <div>
                            <label for="prescriptionRequestDetails" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                <?php echo e(__('Medicamentos Solicitados e Instruções')); ?> <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <textarea wire:model.defer="prescriptionRequestDetails"
                                          id="prescriptionRequestDetails"
                                          rows="6"
                                          class="block w-full rounded-md border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['prescriptionRequestDetails'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Descreva os medicamentos, dosagens, quantidades, instruções de uso ou escreva 'Conforme imagem em anexo'."
                                          required></textarea>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['prescriptionRequestDetails'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label for="prescriptionImages" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                <?php echo e(__('Anexar Imagens da Receita (Máx. 3, Opcional)')); ?>

                            </label>
                            <div class="mt-2">
                                <input type="file" wire:model="prescriptionImages" id="prescriptionImages" multiple 
                                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                       class="block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                                              file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4
                                              file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                                              hover:file:bg-indigo-200 dark:hover:file:bg-sky-600">
                                <div wire:loading wire:target="prescriptionImages" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                    <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                    <?php echo e(__('Carregando imagens...')); ?>

                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['prescriptionImages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['prescriptionImages.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                            
                            <!--[if BLOCK]><![endif]--><?php if($prescriptionImages): ?>
                                <div class="mt-4 space-y-2">
                                    <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1"><?php echo e(__('Pré-visualização das Imagens Selecionadas:')); ?></p>
                                    <div class="flex flex-wrap gap-2">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $prescriptionImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <!--[if BLOCK]><![endif]--><?php if(method_exists($image, 'temporaryUrl')): ?>
                                                <div class="relative group">
                                                    <img src="<?php echo e($image->temporaryUrl()); ?>" alt="<?php echo e(__('Preview da receita anexada')); ?> <?php echo e($index + 1); ?>" class="max-h-32 h-32 w-auto object-cover rounded border dark:border-neutral-500 shadow-sm">
                                                    <button type="button" wire:click="removeImage(<?php echo e($index); ?>)"
                                                            class="absolute top-0 right-0 m-1 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-700 opacity-0 group-hover:opacity-100 transition-opacity"
                                                            title="<?php echo e(__('Remover imagem')); ?> <?php echo e($index + 1); ?>">
                                                        <span class="icon-[mdi--close] w-4 h-4"></span>
                                                    </button>
                                                </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-end gap-x-3 bg-gray-50 dark:bg-neutral-900/30 px-4 py-3 sm:px-6 border-t border-gray-900/10 dark:border-neutral-100/10">
                        <a href="<?php echo e(route('prescriptions.index')); ?>" wire:navigate
                           class="rounded-md bg-white dark:bg-neutral-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-neutral-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-500 hover:bg-gray-50 dark:hover:bg-neutral-600">
                            <?php echo e(__('Cancelar')); ?>

                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitPrescriptionRequest, prescriptionImages" 
                        class="inline-flex justify-center rounded-md bg-blue-600 dark:bg-sky-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 dark:focus-visible:outline-sky-500 disabled:opacity-70">
                            <span wire:loading.remove wire:target="submitPrescriptionRequest, prescriptionImages" class="icon-[mdi--send-check-outline] w-5 h-5 mr-1.5 -ml-0.5"></span>
                            <svg wire:loading wire:target="submitPrescriptionRequest, prescriptionImages" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading wire:target="submitPrescriptionRequest, prescriptionImages"><?php echo e(__('Enviando...')); ?></span>
                            <span wire:loading.remove wire:target="submitPrescriptionRequest, prescriptionImages"><?php echo e(__('Enviar Solicitação')); ?></span>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="p-6 text-center">
                    <p class="text-lg text-red-600 dark:text-red-400"><?php echo e(__('Não é possível solicitar receitas.')); ?></p>
                    <p class="text-sm text-gray-700 dark:text-neutral-300 mt-2"><?php echo e(__('Você não está associado a uma unidade de saúde ou sua unidade não pôde ser determinada. Por favor, contate o suporte.')); ?></p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div><?php /**PATH /var/www/html/sabio-system/resources/views/livewire/prescriptions/request/prescription-form-step.blade.php ENDPATH**/ ?>