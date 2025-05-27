<!--[if BLOCK]><![endif]--><?php if(session('status')): ?>
    <div class="mb-4 rounded-md bg-green-100 p-4 dark:bg-green-800/30">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="icon-[mdi--check-circle] h-5 w-5 text-green-500 dark:text-green-300" aria-hidden="true"></span>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-700 dark:text-green-200">
                    <?php echo e(session('status')); ?>

                </p>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<!--[if BLOCK]><![endif]--><?php if(session('error')): ?>
    <div class="mb-4 rounded-md bg-red-100 p-4 dark:bg-red-800/30">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="icon-[mdi--alert-circle] h-5 w-5 text-red-500 dark:text-red-300" aria-hidden="true"></span>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-700 dark:text-red-200">
                    <?php echo e(session('error')); ?>

                </p>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH /var/www/html/sabio-system/resources/views/livewire/partials/session-messages.blade.php ENDPATH**/ ?>