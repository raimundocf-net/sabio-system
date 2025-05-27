



<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['isEditing' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['isEditing' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?> 

<fieldset class="space-y-6 mt-8">
    <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700"><?php echo e(__('Documentos e Observações')); ?></legend>

    
    <div>
        <label for="referralDocumentFile" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">
            <?php echo e(__('Guia de Encaminhamento (Opcional)')); ?>

            <!--[if BLOCK]><![endif]--><?php if($isEditing && $form['referral_document_path']): ?>
                <a href="<?php echo e(Storage::url($form['referral_document_path'])); ?>" target="_blank" class="ml-2 text-xs text-indigo-600 dark:text-sky-400 hover:underline">(<?php echo e(__('Ver atual')); ?>)</a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </label>
        <div class="mt-2">
            <input type="file" wire:model="referralDocumentFile" id="referralDocumentFile"
                   class="block w-full text-sm text-gray-900 dark:text-neutral-200 border border-gray-300 dark:border-neutral-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-neutral-700 focus:outline-none
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-l-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 dark:file:bg-sky-700 file:text-indigo-700 dark:file:text-sky-200
                          hover:file:bg-indigo-100 dark:hover:file:bg-sky-600
                          <?php $__errorArgs = ['referralDocumentFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400" id="file_input_help">
                <?php echo e(__('Imagem (JPG, PNG, GIF, WEBP) até 5MB.')); ?>

                <!--[if BLOCK]><![endif]--><?php if($isEditing && $form['referral_document_path']): ?>
                    <?php echo e(__('Enviar novo arquivo substituirá o atual.')); ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </p>
        </div>
        <div wire:loading wire:target="referralDocumentFile" class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
            <?php echo e(__('Carregando arquivo...')); ?>

        </div>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['referralDocumentFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($referralDocumentFile && str_starts_with($referralDocumentFile->getMimeType(), 'image')): ?>
            <div class="mt-2">
                <img src="<?php echo e($referralDocumentFile->temporaryUrl()); ?>" alt="<?php echo e(__('Preview da Guia')); ?>" class="max-h-40 rounded border dark:border-neutral-600">
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    
    <div>
        <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-neutral-300"><?php echo e(__('Observações Adicionais')); ?></label>
        <textarea wire:model.defer="form.observations" id="observations" rows="4" placeholder="<?php echo e(__('Informações relevantes sobre o paciente, a viagem, necessidades especiais, etc.')); ?>"
                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-600 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-neutral-700 dark:text-neutral-100 <?php $__errorArgs = ['form.observations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 dark:border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"></textarea>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.observations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</fieldset><?php /**PATH /var/www/html/system/resources/views/livewire/travel-requests/_form-document-observations.blade.php ENDPATH**/ ?>