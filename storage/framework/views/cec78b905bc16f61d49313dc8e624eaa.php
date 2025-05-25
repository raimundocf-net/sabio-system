<div>
     <?php $__env->slot('title', null, []); ?> 
        <?php echo e($pageTitle); ?>

     <?php $__env->endSlot(); ?>

    <div class="my-6 mx-auto px-2 sm:px-6 lg:px-8 max-w-4xl">
        <div class="bg-white dark:bg-neutral-800 shadow-xl rounded-lg">

            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-neutral-100">
                        <?php echo e($pageTitle); ?>

                    </h2>
                    <a href="<?php echo e(route('prescriptions.index')); ?>" wire:navigate
                       class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline self-start sm:self-center">
                        <?php echo e(__('Voltar para Lista')); ?>

                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-6 border-b dark:border-neutral-700">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-100 mb-3"><?php echo e(__('Cidadão')); ?></h3>
                        <dl class="space-y-2 text-sm">
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('Nome:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->citizen?->name ?? $prescription->citizen?->nome_do_cidadao ?: 'N/A'); ?></dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('CPF:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->citizen?->cpf ?: 'N/A'); ?></dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('CNS:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->citizen?->cns ?: 'N/A'); ?></dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-24 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('Nascimento:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->citizen?->date_of_birth ? \Carbon\Carbon::createFromFormat('d/m/Y', $prescription->citizen->date_of_birth)->format('d/m/Y') : 'N/A'); ?></dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-100 mb-3"><?php echo e(__('Detalhes da Solicitação')); ?></h3>
                        <dl class="space-y-2 text-sm">
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('Unidade:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->unit?->name ?: 'N/A'); ?></dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('Solicitante:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->requester?->name ?: 'N/A'); ?></dd>
                            </div>
                            <div class="block sm:flex sm:gap-x-2">
                                <dt class="w-full sm:w-32 font-semibold shrink-0 text-gray-700 dark:text-neutral-200"><?php echo e(__('Solicitado em:')); ?></dt>
                                <dd class="w-full mt-0.5 sm:mt-0 text-gray-600 dark:text-neutral-300"><?php echo e($prescription->created_at->format('d/m/Y H:i')); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                
                <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                    <label for="originalPrescriptionDetails" class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-1 block"><?php echo e(__('Conteúdo do Pedido Original (ACS):')); ?></label>
                    <textarea id="originalPrescriptionDetails"
                              rows="6" 
                              readonly
                              class="w-full p-2 bg-gray-100 dark:bg-neutral-700/60 rounded-md text-sm text-gray-800 dark:text-neutral-200 border-gray-300 dark:border-neutral-600 focus:ring-0 focus:border-gray-300 dark:focus:border-neutral-600 custom-scrollbar"
                              style="resize: none; box-shadow: none; cursor: default;" 
                    ><?php echo e(trim($prescription->getOriginal('prescription_details')) ?: __('Nenhum detalhe fornecido.')); ?></textarea>
                </div>

                <div class="mt-4 pt-4 border-t dark:border-neutral-700 space-y-2">
                    
                    <div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-neutral-200"><?php echo e(__('Status Atual:')); ?></span>
                        <span class="ml-2 px-2.5 py-1 text-xs font-semibold rounded-full <?php echo e($prescription->status->badgeClasses()); ?>">
                            <?php echo e($prescription->status->label()); ?>

                        </span>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($prescription->doctor): ?>
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200"><?php echo e(__('Médico Responsável:')); ?></span> <?php echo e($prescription->doctor?->name); ?></p>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200"><?php echo e(__('Médico Responsável:')); ?></span> <?php echo e(__('Nenhum atribuído')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($prescription->reviewed_at): ?>
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200"><?php echo e(__('Analisado em:')); ?></span> <?php echo e($prescription->reviewed_at->format('d/m/Y H:i')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if($prescription->completed_at): ?>
                        <p class="text-xs text-gray-500 dark:text-neutral-400"><span class="font-semibold text-gray-700 dark:text-neutral-200"><?php echo e(__('Finalizado em:')); ?></span> <?php echo e($prescription->completed_at->format('d/m/Y H:i')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <!--[if BLOCK]><![endif]--><?php if($prescription->processing_notes): ?>
                    <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-1"><?php echo e(__('Histórico de Notas de Processamento:')); ?></h4>
                        <div class="p-3 bg-gray-100 dark:bg-neutral-700/60 rounded-md max-h-32 overflow-y-auto custom-scrollbar">
                            <p class="text-xs text-gray-600 dark:text-neutral-300 whitespace-pre-wrap"><?php echo e($prescription->processing_notes); ?></p>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <div class="mt-4 pt-4 border-t dark:border-neutral-700">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-neutral-200 mb-2"><?php echo e(__('Imagens da Receita Anexadas')); ?></h4>
                    <!--[if BLOCK]><![endif]--><?php if(!empty($existingImagePaths)): ?>
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 dark:text-neutral-400 mb-2"><?php echo e(__('Imagens Atuais:')); ?></p>
                            <div class="flex flex-wrap gap-3">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $existingImagePaths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $imagePath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $imageUrl = Storage::disk('public')->url($imagePath); ?>
                                    <div class="relative group w-32 h-32 sm:w-40 sm:h-40" wire:key="existing-img-<?php echo e($index); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if(!in_array($imagePath, $imagesToRemove)): ?>
                                            <a href="<?php echo e($imageUrl); ?>" target="_blank"
                                               class="block w-full h-full p-1 border border-dashed border-gray-300 dark:border-neutral-600 rounded-md hover:border-indigo-500 dark:hover:border-sky-500 transition-colors">
                                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e(__('Imagem da receita anexada')); ?> <?php echo e($index + 1); ?>" class="w-full h-full object-contain rounded shadow-sm">
                                            </a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $prescription)): ?>
                                                <button type="button" wire:click="markImageForRemoval('<?php echo e($imagePath); ?>')"
                                                        class="absolute top-1 right-1 m-0.5 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-700 opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity"
                                                        title="<?php echo e(__('Marcar para remover')); ?>">
                                                    <span class="icon-[mdi--delete-outline] w-4 h-4"></span>
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            
                                            <div class="w-full h-full border-2 border-dashed border-red-400 dark:border-red-600 rounded-md flex flex-col items-center justify-center bg-red-50 dark:bg-red-900/20 p-2">
                                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e(__('Imagem da receita')); ?> <?php echo e($index + 1); ?>" class="max-h-16 w-auto opacity-50 rounded">
                                                <p class="text-xs text-red-600 dark:text-red-300 mt-1 text-center"><?php echo e(__('Marcada para remoção')); ?></p>
                                                <button type="button" wire:click="unmarkImageForRemoval('<?php echo e($imagePath); ?>')"
                                                        class="mt-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                    <?php echo e(__('Desfazer')); ?>

                                                </button>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 dark:text-neutral-400 mb-3"><?php echo e(__('Nenhuma imagem anexada no momento.')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    
                    <!--[if BLOCK]><![endif]--><?php if(!in_array($prescription->status, [\App\Enums\PrescriptionStatus::DELIVERED, \App\Enums\PrescriptionStatus::CANCELLED])): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $prescription)): ?>
                            <div class="mt-4">
                                <label for="newPrescriptionImagesUpload" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                    <?php echo e(__('Adicionar Novas Imagens (Máx. 3 no total)')); ?>

                                </label>
                                <div class="mt-2">
                                    <input type="file" wire:model="newPrescriptionImages" id="newPrescriptionImagesUpload" multiple
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="block w-full text-sm text-gray-900 dark:text-neutral-100 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                                                      file:mr-4 file:py-2 file:px-4 rtl:file:mr-0 rtl:file:ml-4
                                                      file:rounded-l-lg rtl:file:rounded-l-none rtl:file:rounded-r-lg file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-indigo-100 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                                                      hover:file:bg-indigo-200 dark:hover:file:bg-sky-600 <?php $__errorArgs = ['newPrescriptionImages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['newPrescriptionImages.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <div wire:loading wire:target="newPrescriptionImages" class="mt-1 text-xs text-indigo-600 dark:text-sky-400">
                                        <span class="icon-[svg-spinners--ring-resize] w-4 h-4 inline-block animate-spin"></span>
                                        <?php echo e(__('Carregando novas imagens...')); ?>

                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newPrescriptionImages'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newPrescriptionImages.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                                <!--[if BLOCK]><![endif]--><?php if($newPrescriptionImages): ?>
                                    <div class="mt-4 space-y-2">
                                        <p class="text-xs font-medium text-gray-700 dark:text-neutral-300 mb-1"><?php echo e(__('Pré-visualização das Novas Imagens:')); ?></p>
                                        <div class="flex flex-wrap gap-3">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $newPrescriptionImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <!--[if BLOCK]><![endif]--><?php if(method_exists($image, 'temporaryUrl')): ?>
                                                    <div class="relative group w-24 h-24 sm:w-32 sm:h-32">
                                                        <img src="<?php echo e($image->temporaryUrl()); ?>" alt="<?php echo e(__('Preview da nova imagem')); ?> <?php echo e($index + 1); ?>" class="w-full h-full object-contain rounded border dark:border-neutral-500 shadow-sm">
                                                        <button type="button" wire:click="removeNewImage(<?php echo e($index); ?>)"
                                                                class="absolute top-0 right-0 m-0.5 p-0.5 bg-red-500 text-white rounded-full hover:bg-red-700 opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity"
                                                                title="<?php echo e(__('Remover nova imagem')); ?> <?php echo e($index + 1); ?>">
                                                            <span class="icon-[mdi--close] w-4 h-4"></span>
                                                        </button>
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!--[if BLOCK]><![endif]--><?php if($newPrescriptionImages || count($imagesToRemove) > 0): ?>
                                    <div class="mt-3 text-right">
                                        <button type="button" wire:click="saveImages" wire:loading.attr="disabled"
                                                class="inline-flex items-center justify-center rounded-md bg-teal-600 dark:bg-teal-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-teal-700 dark:hover:bg-teal-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal-600">
                                            <span wire:loading wire:target="saveImages" class="icon-[svg-spinners--6-dots-scale-middle] w-4 h-4 mr-1.5 -ml-0.5"></span>
                                            <span wire:loading.remove wire:target="saveImages"><?php echo e(__('Salvar Alterações nas Imagens')); ?></span>
                                            <span wire:loading wire:target="saveImages"><?php echo e(__('Salvando Imagens...')); ?></span>
                                        </button>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div> 


            
            <!--[if BLOCK]><![endif]--><?php if(!in_array($prescription->status, [\App\Enums\PrescriptionStatus::DELIVERED, \App\Enums\PrescriptionStatus::CANCELLED])): ?>
                <div class="p-4 sm:p-6 space-y-6 border-t dark:border-neutral-700">
                    
                    <!--[if BLOCK]><![endif]--><?php if(Auth::user() && Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id &&
                       in_array($prescription->status, [
                           \App\Enums\PrescriptionStatus::REQUESTED,
                           \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR
                       ])
                   ): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $prescription)): ?>
                            <div class="p-4 border rounded-lg shadow-sm
                                <?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                    border-yellow-400 dark:border-yellow-600 bg-yellow-50 dark:bg-yellow-900/20
                                <?php else: ?> 
                                    border-blue-300 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20
                                <?php endif; ?>
                            ">
                                <h3 class="text-md font-semibold mb-2
                                    <?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                        text-yellow-800 dark:text-yellow-300
                                    <?php else: ?>
                                        text-blue-800 dark:text-blue-300
                                    <?php endif; ?>
                                ">
                                    <!--[if BLOCK]><![endif]--><?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                        <?php echo e(__('Corrigir e Reenviar Solicitação')); ?>

                                    <?php else: ?> 
                                    <?php echo e(__('Editar Conteúdo da Solicitação')); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </h3>

                                <!--[if BLOCK]><![endif]--><?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-200 mb-3">
                                        <?php echo e(__('Esta solicitação foi marcada como necessitando de correções. Por favor, ajuste os detalhes do pedido abaixo e reenvie para uma nova análise.')); ?>

                                    </p>
                                <?php else: ?> <p class="text-xs text-gray-600 dark:text-neutral-400 mb-3">
                                    <?php echo e(__('Você pode editar os detalhes do pedido enquanto o status for "Solicitada".')); ?>

                                </p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <div class="space-y-4">
                                    <div>
                                        <label for="editablePrescriptionDetails" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            <?php echo e(__('Detalhes do Pedido')); ?> <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-1">
                                            <textarea wire:model.defer="editablePrescriptionDetails" id="editablePrescriptionDetails" rows="6"
                                                      class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['editablePrescriptionDetails'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                      placeholder="<?php echo e($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR ? __('Descreva os medicamentos e instruções corrigidos aqui...') : __('Descreva os medicamentos e instruções aqui...')); ?>"
                                                      required></textarea>
                                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editablePrescriptionDetails'];
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
                                        <label for="editOrCorrectionReason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
                                            <?php echo e(__('Observação sobre a Edição/Correção (Opcional)')); ?>

                                        </label>
                                        <div class="mt-1">
                                            <input type="text" wire:model.defer="editOrCorrectionReason" id="editOrCorrectionReason"
                                                   class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['editOrCorrectionReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="Ex: Dosagem ajustada conforme orientação.">
                                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['editOrCorrectionReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" wire:click="savePrescriptionContentChanges" wire:loading.attr="disabled"
                                                class="inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                                                <?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                                    bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 focus-visible:outline-green-600
                                                <?php else: ?>
                                                    bg-blue-600 hover:bg-blue-700 dark:bg-sky-500 dark:hover:bg-sky-400 focus-visible:outline-blue-600
                                                <?php endif; ?>">
                                            <span wire:loading wire:target="savePrescriptionContentChanges" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                            <span wire:loading.remove wire:target="savePrescriptionContentChanges">
                                                <!--[if BLOCK]><![endif]--><?php if($prescription->status === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR): ?>
                                                    <?php echo e(__('Salvar Correções e Reenviar')); ?>

                                                <?php else: ?>
                                                    <?php echo e(__('Salvar Alterações no Conteúdo')); ?>

                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </span>
                                            <span wire:loading wire:target="savePrescriptionContentChanges"><?php echo e(__('Salvando...')); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if(!(Auth::user() && Auth::user()->hasRole('acs') && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR]))): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('addProcessingNote', $prescription)): ?>
                            <div class="pt-6 <?php if(Auth::user() && Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR]) && Auth::user()->can('update', $prescription)): ?> border-t dark:border-neutral-700 <?php endif; ?>">
                                <label for="new_processing_notes" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200"><?php echo e(__('Adicionar Nota ao Processamento')); ?></label>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-1">
                                    <?php echo e(__('Suas notas serão adicionadas ao histórico com data e seu nome. O conteúdo anterior será mantido.')); ?>

                                </p>
                                <div class="mt-2">
                                    <textarea wire:model.defer="current_processing_notes" id="new_processing_notes" rows="3" placeholder="Digite uma nova nota aqui..."
                                              class="block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['current_processing_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['current_processing_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="mt-3 flex justify-end">
                                    <button type="button" wire:click="saveProcessingNotes" wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 dark:bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 dark:hover:bg-sky-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:focus-visible:outline-sky-500">
                                        <span wire:loading wire:target="saveProcessingNotes" class="icon-[svg-spinners--6-dots-scale-middle] w-5 h-5 mr-1.5 -ml-0.5"></span>
                                        <span wire:loading.remove wire:target="saveProcessingNotes"><?php echo e(__('Adicionar Nota')); ?></span>
                                        <span wire:loading wire:target="saveProcessingNotes"><?php echo e(__('Adicionando...')); ?></span>
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if(count($statusOptionsForSelect) > 0): ?>
                        <div class="pt-6 border-t dark:border-neutral-700">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 mb-3"><?php echo e(__('Próximas Ações / Alterar Status')); ?></h3>
                            <div class="flex flex-wrap items-center gap-3">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $statusOptionsForSelect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $statusEnum = \App\Enums\PrescriptionStatus::from($statusValue);
                                        $buttonClasses = 'inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-50 transition-colors duration-150';
                                        $iconClasses = 'w-4 h-4 sm:w-5 sm:h-5 mr-1.5 -ml-0.5 rtl:mr-0 rtl:ml-1.5';
                                        $specificClasses = ''; $icon = '';
                                        switch($statusEnum) {
                                            case \App\Enums\PrescriptionStatus::APPROVED_FOR_ISSUANCE: $specificClasses = 'bg-green-600 hover:bg-green-700 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-400'; $icon = 'icon-[mdi--check-decagram-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::READY_FOR_PICKUP:    $specificClasses = 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 dark:bg-sky-500 dark:hover:bg-sky-400'; $icon = 'icon-[mdi--package-variant-closed-check]'; break;
                                            case \App\Enums\PrescriptionStatus::DELIVERED:           $specificClasses = 'bg-teal-600 hover:bg-teal-700 focus:ring-teal-500 dark:bg-teal-500 dark:hover:bg-teal-400'; $icon = 'icon-[mdi--account-check-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR:  $specificClasses = 'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-400'; $icon = 'icon-[mdi--file-cancel-outline]'; break;
                                            case \App\Enums\PrescriptionStatus::UNDER_DOCTOR_REVIEW: $specificClasses = 'bg-amber-500 hover:bg-amber-600 focus:ring-amber-500 dark:bg-amber-400 dark:hover:bg-amber-300'; $icon = 'icon-[mdi--account-search-outline]'; break;
                                            default:                                                 $specificClasses = 'bg-gray-500 hover:bg-gray-600 focus:ring-gray-500 dark:bg-gray-400 dark:hover:bg-gray-300'; $icon = 'icon-[mdi--progress-question]';
                                        }
                                    ?>
                                    <button type="button" wire:click="prepareStatusUpdate('<?php echo e($statusValue); ?>')"
                                            class="<?php echo e($buttonClasses); ?> <?php echo e($specificClasses); ?>">
                                        <!--[if BLOCK]><![endif]--><?php if($icon): ?><span class="<?php echo e($icon); ?> <?php echo e($iconClasses); ?>"></span><?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php echo e($statusLabel); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <?php if(Auth::user() && (Auth::user()->hasRole('acs') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('doctor'))): ?>
                        <div class="pt-6 <?php if( (count($statusOptionsForSelect) > 0) || (Auth::user()->can('addProcessingNote', $prescription)) || (Auth::user()->hasRole('acs') && Auth::user()->id === $prescription->user_id && in_array($prescription->status, [\App\Enums\PrescriptionStatus::REQUESTED, \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR]) && Auth::user()->can('update', $prescription)) ): ?> border-t dark:border-neutral-700 <?php endif; ?>">
                            <h3 class="text-md font-semibold text-gray-900 dark:text-neutral-100 mb-1"><?php echo e(__('Outras Ações')); ?></h3>
                            <?php if(Auth::user()->hasRole('acs')): ?>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-3">
                                    <?php echo e(__('Como ACS, você só pode cancelar suas próprias solicitações se o status ainda for "Solicitada".')); ?><br>
                                    <?php echo e(__('Se já estiver em processamento, o cancelamento por aqui não é mais possível.')); ?>

                                </p>
                            <?php elseif(Auth::user()->hasRole('manager') || Auth::user()->hasRole('doctor')): ?>
                                <p class="text-xs text-gray-500 dark:text-neutral-400 mb-3">
                                    <?php echo e(__('O cancelamento geralmente não é permitido para solicitações com status final (Ex: \'Entregue\') ou que já estejam \'Canceladas\'. Verifique as condições específicas.')); ?>

                                </p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <button type="button"
                                    wire:click="prepareCancellation"
                                    <?php if(Auth::user()->cannot('cancel', $prescription)): echo 'disabled'; endif; ?>
                                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent rounded-md shadow-sm text-xs sm:text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition-colors duration-150 bg-orange-500 hover:bg-orange-600 focus:ring-orange-500 dark:bg-orange-400 dark:hover:bg-orange-300 disabled:bg-gray-300 dark:disabled:bg-neutral-600 disabled:text-gray-500 dark:disabled:text-neutral-400 disabled:cursor-not-allowed"
                                    title="<?php echo e(Auth::user()->can('cancel', $prescription) ? __('Cancelar Solicitação') : __('Cancelamento não permitido neste momento')); ?>">
                                <span class="icon-[mdi--cancel-bold] w-4 h-4 sm:w-5 sm:h-5 mr-1.5 -ml-0.5 rtl:mr-0 rtl:ml-1.5"></span>
                                <?php echo e(__('Cancelar Solicitação')); ?>

                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($showStatusUpdateModal): ?>
                
                <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title-status-update" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div wire:click="closeStatusUpdateModal" class="fixed inset-0 bg-gray-500/75 dark:bg-neutral-900/80 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            
                            <div class="bg-white dark:bg-neutral-800 px-4 pt-5 pb-4 sm:p-6">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                         :class="{
                                            'bg-red-100 dark:bg-red-800/30': '<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>',
                                            'bg-blue-100 dark:bg-blue-800/30': !('<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>')
                                         }">
                                        <span class="h-6 w-6"
                                              :class="{
                                                'icon-[mdi--alert-outline] text-red-600 dark:text-red-400': '<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>',
                                                'icon-[mdi--information-outline] text-blue-600 dark:text-blue-400': !('<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>')
                                              }"></span>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-neutral-100" id="modal-title-status-update"><?php echo e($modalTitle); ?></h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-neutral-300 mb-2">
                                                <?php echo e(__('Você está prestes a alterar o status da solicitação para')); ?> <strong><?php echo e(\App\Enums\PrescriptionStatus::tryFrom($targetStatus ?? '')?->label() ?? $targetStatus); ?></strong>.
                                            </p>
                                            <!--[if BLOCK]><![endif]--><?php if(in_array($targetStatus, [\App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value, \App\Enums\PrescriptionStatus::CANCELLED->value])): ?>
                                                <div>
                                                    <label for="statusUpdateReason" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Motivo')); ?><span class="text-red-500">*</span></label>
                                                    <textarea wire:model.defer="statusUpdateReason"
                                                              id="statusUpdateReason" rows="3"
                                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-700 py-2 px-3 text-gray-900 dark:text-neutral-100 shadow-sm placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:border-indigo-500 dark:focus:border-sky-500 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-sky-500 sm:text-sm <?php $__errorArgs = ['statusUpdateReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
                                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['statusUpdateReason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            <?php else: ?>
                                                <p class="text-sm text-gray-600 dark:text-neutral-300"><?php echo e(__('Deseja continuar?')); ?></p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-100 dark:bg-neutral-800/80 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-x-3">
                                <button wire:click="confirmStatusUpdate" type="button" wire:loading.attr="disabled"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 sm:w-auto disabled:opacity-50"
                                        :class="{
                                            'bg-red-600 hover:bg-red-700 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-400': '<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>',
                                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 dark:bg-sky-500 dark:hover:bg-sky-400': !('<?php echo e($targetStatus === \App\Enums\PrescriptionStatus::REJECTED_BY_DOCTOR->value || $targetStatus === \App\Enums\PrescriptionStatus::CANCELLED->value); ?>')
                                        }">
                                    <span wire:loading.remove wire:target="confirmStatusUpdate"><?php echo e($modalConfirmationButtonText); ?></span>
                                    <svg wire:loading wire:target="confirmStatusUpdate" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                                <button wire:click="closeStatusUpdateModal" type="button" wire:loading.attr="disabled"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-neutral-500 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-sm font-semibold text-gray-700 dark:text-neutral-200 hover:bg-gray-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-neutral-800 sm:mt-0 sm:w-auto disabled:opacity-50">
                                    <?php echo e(__('Voltar')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</div><?php /**PATH /var/www/html/system/resources/views/livewire/prescriptions/edit-prescription.blade.php ENDPATH**/ ?>