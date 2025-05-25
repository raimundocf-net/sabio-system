<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-100 dark:bg-zinc-800">
<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

    <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navbar class="-mb-px max-lg:hidden">

        <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:navbar.item>

        @can('import-citizens')
            <flux:navbar.item :href="route('units.index')" :current="request()->routeIs('units.*')" wire:navigate>
                <x-slot:icon> <span class="icon-[mdi--hospital-building] w-5 h-5"></span> </x-slot:icon>
                {{ __('Unidades') }}
            </flux:navbar.item>

            <flux:navbar.item :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                <x-slot:icon> <span class="icon-[mdi--account-group-outline] w-5 h-5"></span> </x-slot:icon>
                {{ __('Usuários') }}
            </flux:navbar.item>
        @endcan

        <flux:navbar.item :href="route('prescriptions.index')" :current="request()->routeIs('prescriptions.*')" wire:navigate>
            <x-slot:icon>
                <span class="icon-[mdi--medical-bag] w-5 h-5"></span>
            </x-slot:icon>
            {{ __('Receitas') }}
        </flux:navbar.item>

        {{-- MENU DROPDOWN PARA TRANSPORTE COM CONTROLE DE ACESSO --}}
        @can('view-transport-menu') {{-- Aplica o Gate aqui --}}
        <flux:dropdown align="start" position="bottom">
            <flux:navbar.item
                as="button"
                :current="request()->routeIs('vehicles.*') || request()->routeIs('travel-requests.*') || request()->routeIs('boarding-locations.*') || request()->routeIs('companions.*')"
                icon-trailing="chevron-down">
                <x-slot:icon>
                    <span class="icon-[mdi--ambulance] w-5 h-5"></span>
                </x-slot:icon>
                {{ __('Transporte') }}
            </flux:navbar.item>

            <flux:menu class="w-56">
                @can('viewAny', \App\Models\Vehicle::class)
                    <flux:menu.item :href="route('vehicles.index')" :current="request()->routeIs('vehicles.*')" wire:navigate>
                        <x-slot:icon><span class="icon-[ph--car-fill] w-5 h-5"></span></x-slot:icon>
                        {{ __('Veículos') }}
                    </flux:menu.item>
                @endcan

                @can('viewAny', \App\Models\TravelRequest::class)
                    <flux:menu.item :href="route('travel-requests.index')" :current="request()->routeIs('travel-requests.*')" wire:navigate>
                        <x-slot:icon><span class="icon-[mdi--clipboard-text-clock-outline] w-5 h-5"></span></x-slot:icon>
                        {{ __('Solicitações de Viagem') }}
                    </flux:menu.item>
                @endcan

                {{-- Para Locais de Embarque e Acompanhantes, idealmente teríamos policies ou gates específicos também.
                     Por enquanto, eles herdarão a visibilidade do dropdown principal. Se precisar de controle mais fino,
                     adicione @can para eles também.
                --}}
                <flux:menu.item :href="route('boarding-locations.index')" :current="request()->routeIs('boarding-locations.*')" wire:navigate>
                    <x-slot:icon><span class="icon-[mdi--map-marker-path] w-5 h-5"></span></x-slot:icon>
                    {{ __('Locais de Embarque') }}
                </flux:menu.item>

                <flux:menu.item :href="route('companions.index')" :current="request()->routeIs('companions.*')" wire:navigate>
                    <x-slot:icon><span class="icon-[mdi--account-multiple-outline] w-5 h-5"></span></x-slot:icon>
                    {{ __('Acompanhantes') }}
                </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
        @endcan
        {{-- FIM DO MENU DROPDOWN --}}

    </flux:navbar>

    <flux:spacer />

    <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
        <flux:tooltip :content="__('Search')" position="bottom">
            <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
        </flux:tooltip>

        @can('import-citizens')
            <flux:tooltip :content="__('Importar Cidadãos')" position="bottom">
                <flux:navbar.item
                    class="!h-10"
                    :href="route('citizens.import.form')"
                    :current="request()->routeIs('citizens.import.form')"
                    wire:navigate
                    :label="__('Importar Cidadãos')">
                    <x-slot:icon>
                        <span class="icon-[lucide--file-input] w-5 h-5"></span>
                    </x-slot:icon>
                </flux:navbar.item>
            </flux:tooltip>
        @endcan

        <flux:tooltip :content="__('Repository')" position="bottom">
            <flux:navbar.item
                class="h-10 max-lg:hidden [&>div>svg]:size-5"
                icon="folder-git-2"
                href="https://github.com/laravel/livewire-starter-kit"
                target="_blank"
                :label="__('Repository')"
            />
        </flux:tooltip>
        <flux:tooltip :content="__('Documentation')" position="bottom">
            <flux:navbar.item
                class="h-10 max-lg:hidden [&>div>svg]:size-5"
                icon="book-open-text"
                href="https://laravel.com/docs"
                target="_blank"
                label="Documentation"
            />
        </flux:tooltip>
    </flux:navbar>

    <flux:dropdown position="top" align="end">
        <flux:profile
            class="cursor-pointer"
            :initials="auth()->user()->initials()"
        />
        <flux:menu>
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>
            <flux:menu.separator />
            <flux:menu.radio.group>
                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
            </flux:menu.radio.group>
            <flux:menu.separator />
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

<flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')">
            <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group :heading="__('Gerenciamento')" class="grid">
            <flux:navlist.item :href="route('units.index')" :current="request()->routeIs('units.*')" wire:navigate>
                <x-slot:icon><span class="icon-[mdi--hospital-building] w-5 h-5"></span></x-slot:icon>
                {{ __('Unidades de Saúde') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
                <x-slot:icon><span class="icon-[mdi--account-group-outline] w-5 h-5"></span></x-slot:icon>
                {{ __('Usuários') }}
            </flux:navlist.item>
            <flux:navlist.item :href="route('prescriptions.index')" :current="request()->routeIs('prescriptions.*')" wire:navigate>
                <x-slot:icon>
                    <span class="icon-[mdi--medical-bag] w-5 h-5"></span>
                </x-slot:icon>
                {{ __('Receitas') }}
            </flux:navlist.item>
        </flux:navlist.group>

        {{-- GRUPO TRANSPORTE NA SIDEBAR COM CONTROLE DE ACESSO --}}
        @can('view-transport-menu')
            <flux:navlist.group :heading="__('Transporte')" class="grid" :expandable="true"
                                :expanded="request()->routeIs('vehicles.*') || request()->routeIs('travel-requests.*') || request()->routeIs('boarding-locations.*') || request()->routeIs('companions.*')">

                @can('viewAny', \App\Models\Vehicle::class)
                    <flux:navlist.item :href="route('vehicles.index')" :current="request()->routeIs('vehicles.*')" wire:navigate>
                        <x-slot:icon><span class="icon-[ph--car-fill] w-5 h-5"></span></x-slot:icon>
                        {{ __('Veículos') }}
                    </flux:navlist.item>
                @endcan

                @can('viewAny', \App\Models\TravelRequest::class)
                    <flux:navlist.item :href="route('travel-requests.index')" :current="request()->routeIs('travel-requests.*')" wire:navigate>
                        <x-slot:icon><span class="icon-[mdi--clipboard-text-clock-outline] w-5 h-5"></span></x-slot:icon>
                        {{ __('Solicitações de Viagem') }}
                    </flux:navlist.item>
                @endcan

                <flux:navlist.item :href="route('boarding-locations.index')" :current="request()->routeIs('boarding-locations.*')" wire:navigate>
                    <x-slot:icon><span class="icon-[mdi--map-marker-path] w-5 h-5"></span></x-slot:icon>
                    {{ __('Locais de Embarque') }}
                </flux:navlist.item>

                <flux:navlist.item :href="route('companions.index')" :current="request()->routeIs('companions.*')" wire:navigate>
                    <x-slot:icon><span class="icon-[mdi--account-multiple-outline] w-5 h-5"></span></x-slot:icon>
                    {{ __('Acompanhantes') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @endcan
        {{-- FIM DO GRUPO TRANSPORTE NA SIDEBAR --}}

    </flux:navlist>

    <flux:spacer />

    <flux:navlist variant="outline">
        <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
            {{ __('Repository') }}
        </flux:navlist.item>
        <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
            {{ __('Documentation') }}
        </flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

{{ $slot }}

@fluxScripts
</body>
</html>
