@props(['isEditing' => false])

{{-- Dados do Usuário --}}
<fieldset class="space-y-6">
    <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700">{{__('Informações do Usuário')}}</legend>

    {{-- Nome do Usuário --}}
    <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Nome do Usuário') }}</label>
        <div class="mt-2">
            <input type="text" wire:model.defer="name" id="name" placeholder="{{ __('Digite o nome completo do usuário') }}"
                   class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('name') ring-red-500 dark:ring-red-500 @enderror">
        </div>
        @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('E-mail') }}</label>
        <div class="mt-2">
            <input type="email" wire:model.defer="email" id="email" placeholder="{{ __('exemplo@dominio.com') }}"
                   class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('email') ring-red-500 dark:ring-red-500 @enderror">
        </div>
        @error('email') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Unidade de Saúde --}}
        <div>
            <label for="unit_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Unidade de Saúde') }}</label>
            <div class="mt-2">
                <select wire:model.defer="unit_id" id="unit_id"
                        class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('unit_id') ring-red-500 dark:ring-red-500 @enderror">
                    <option value="">{{ __('Selecione a unidade') }}</option>
                    @foreach($unitsList as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('unit_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Papel (Role) --}}
        <div>
            <label for="role" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Papel / Cargo') }}</label>
            <div class="mt-2">
                <select wire:model.defer="role" id="role"
                        class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('role') ring-red-500 dark:ring-red-500 @enderror">
                    <option value="">{{ __('Selecione o papel') }}</option>
                    @foreach($availableRoles as $roleKey => $roleLabel)
                        <option value="{{ $roleKey }}">{{ $roleLabel }}</option>
                    @endforeach
                </select>
            </div>
            @error('role') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- CNS do Usuário --}}
        <div>
            <label for="cns" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('CNS do Usuário') }}</label>
            <div class="mt-2">
                <input type="text" wire:model.defer="cns" id="cns" placeholder="{{ __('Digite o CNS (opcional)') }}"
                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('cns') ring-red-500 dark:ring-red-500 @enderror">
            </div>
            @error('cns') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- CBO do Usuário --}}
        <div>
            <label for="cbo" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('CBO do Usuário') }}</label>
            <div class="mt-2">
                <input type="text" wire:model.defer="cbo" id="cbo" placeholder="{{ __('Digite o CBO (opcional)') }}"
                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('cbo') ring-red-500 dark:ring-red-500 @enderror">
            </div>
            @error('cbo') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
    </div>
</fieldset>

{{-- Senha --}}
<fieldset class="space-y-6 mt-8">
    <legend class="text-lg font-semibold text-gray-900 dark:text-neutral-100 mb-4 pb-2 border-b border-gray-200 dark:border-neutral-700">
        {{ $isEditing ? __('Alterar Senha (Opcional)') : __('Definir Senha') }}
    </legend>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Senha') }}</label>
            <div class="mt-2">
                <input type="password" wire:model.defer="password" id="password" placeholder="{{ $isEditing ? __('Deixe em branco para não alterar') : __('Mínimo 8 caracteres') }}"
                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('password') ring-red-500 dark:ring-red-500 @enderror">
            </div>
            @error('password') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900 dark:text-neutral-200">{{ __('Confirmação da Senha') }}</label>
            <div class="mt-2">
                <input type="password" wire:model.defer="password_confirmation" id="password_confirmation" placeholder="{{ __('Repita a senha') }}"
                       class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 dark:text-neutral-100 bg-white dark:bg-neutral-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-neutral-600 placeholder:text-gray-400 dark:placeholder:text-neutral-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-sky-500 sm:text-sm sm:leading-6 @error('password_confirmation') ring-red-500 dark:ring-red-500 @enderror">
            </div>
            {{-- O erro de password_confirmation é geralmente mostrado junto com o erro de 'password' se a regra 'confirmed' falhar no campo 'password' --}}
        </div>
    </div>
</fieldset>
