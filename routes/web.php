<?php

use App\Livewire\BoardingLocations\CreateBoardingLocation;
use App\Livewire\BoardingLocations\IndexBoardingLocation;
use App\Livewire\BoardingLocations\UpdateBoardingLocation;
use App\Livewire\Citizens\ImportCitizen;
use App\Livewire\DashboardSummary; // Corretamente usado para /dashboard
use App\Livewire\Prescriptions\EditPrescription;
use App\Livewire\Prescriptions\ListPrescriptions;
use App\Livewire\Prescriptions\Request\PrescriptionFormStep;
use App\Livewire\Prescriptions\Request\SearchCitizenStep;
use App\Livewire\Units\IndexUnit;
use App\Livewire\Units\ManageUnit;
use App\Livewire\Users\IndexUser;
use App\Livewire\Users\ManageUser;
use App\Livewire\Vehicles\CreateVehicle;
use App\Livewire\Vehicles\IndexVehicle;
use App\Livewire\Vehicles\ManageVehicle;
use App\Livewire\Vehicles\UpdateVehicle;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt; // Para as rotas de Configurações

// Rota principal redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Grupo de rotas que exigem autenticação
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', DashboardSummary::class)->name('dashboard');

    // Configurações (usando Volt)
    Route::redirect('settings', 'settings/profile')->name('settings'); // Boa prática o redirect
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Rotas para Unidades de Saúde
    // Sugestão: Proteger o grupo de unidades com viewAny se nem todos podem ver a lista
    // Route::middleware(['can:viewAny,' . \App\Models\Unit::class])->prefix('units')->name('units.')->group(function () {
    Route::prefix('units')->name('units.')->group(function () { // Organizado com prefixo e nome
        Route::get('/', IndexUnit::class)->name('index');
        Route::get('/create', ManageUnit::class)->name('create'); // Adicionar ->middleware('can:create,' . \App\Models\Unit::class) aqui
        Route::get('/{unit}/edit', ManageUnit::class)->name('edit'); // Usar {unit} para route-model binding se ManageUnit::mount(Unit $unit)
        // Se ManageUnit::mount(int $unitId), então {unitId} está OK.
    });

    // Rotas para Usuários
    // Sugestão: Proteger o grupo de usuários com viewAny
    // Route::middleware(['can:viewAny,' . \App\Models\User::class])->prefix('users')->name('users.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', IndexUser::class)->name('index');
        Route::get('/create', ManageUser::class)->name('create'); // Adicionar ->middleware('can:create,' . \App\Models\User::class)
        Route::get('/{user}/edit', ManageUser::class)->name('edit'); // Usar {user} se ManageUser::mount(User $user)
    });

    // Rota para Importar Cidadãos (já corretamente protegida)
    Route::middleware(['can:import-citizens'])->group(function () {
        Route::get('citizens/import', ImportCitizen::class)->name('citizens.import.form');
    });

    // Rotas para Receitas (Prescriptions)
    // Sugestão: Proteger o grupo de prescrições com viewAny
    // Route::middleware(['can:viewAny,' . \App\Models\Prescription::class])->prefix('prescriptions')->name('prescriptions.')->group(function () {
    Route::prefix('prescriptions')->name('prescriptions.')->group(function () { // Sugestão de agrupar para consistência
        Route::get('/', ListPrescriptions::class)->name('index');
        Route::get('/request/search-citizen', SearchCitizenStep::class)->name('request.search');
        Route::get('/request/form/{citizenId}', PrescriptionFormStep::class)->name('request.form');
        Route::get('/{prescription}/edit', EditPrescription::class)->name('edit'); // Route Model Binding para {prescription} está correto
        Route::get('/{prescription}/image-pdf', [\App\Http\Controllers\PrescriptionViewController::class, 'showImageAsPdf'])->name('image.pdf'); // Middleware 'auth' já está no grupo pai
    });

    // >>> ROTAS PARA VEÍCULOS ATUALIZADAS <<<
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', IndexVehicle::class)
            ->name('index')
            ->can('viewAny', \App\Models\Vehicle::class);

        Route::get('/create', CreateVehicle::class) // Aponta para CreateVehicle
        ->name('create')
            ->can('create', \App\Models\Vehicle::class);

        Route::get('/{vehicle}/edit', UpdateVehicle::class) // Aponta para UpdateVehicle
        ->name('edit')
            ->can('update', 'vehicle'); // 'vehicle' é o parâmetro da rota
    });
    // >>> FIM DAS ROTAS ATUALIZADAS <<<



    /*
    |--------------------------------------------------------------------------
    | Travel Requests Module
    |--------------------------------------------------------------------------
    */
    Route::prefix('travel-requests')->name('travel-requests.')->group(function () {
        Route::get('/', \App\Livewire\TravelRequests\IndexTravelRequest::class)->name('index')
            ->can('viewAny', \App\Models\TravelRequest::class);

        // Etapa 1: Busca de cidadão
        Route::get('/create/search-citizen', \App\Livewire\TravelRequests\SearchCitizenForTravel::class)
            ->name('create.search-citizen')
            ->can('create', \App\Models\TravelRequest::class);

        // Etapa 2: Formulário de solicitação (após selecionar o cidadão)
        // O parâmetro {citizen} permitirá o Route Model Binding
        Route::get('/create/form/{citizen}', \App\Livewire\TravelRequests\TravelRequestForm::class)
            ->name('create.form')
            ->can('create', \App\Models\TravelRequest::class);

        Route::get('/{travelRequest}/edit', \App\Livewire\TravelRequests\EditTravelRequest::class)
            ->name('edit')
            ->can('update', 'travelRequest'); // 'travelRequest' é o nome do parâmetro para RMB
    });

    /*
    |--------------------------------------------------------------------------
    | Boarding Locations Module (Locais de Embarque)
    |--------------------------------------------------------------------------
    */
    Route::prefix('boarding-locations')->name('boarding-locations.')->group(function () {
        Route::get('/', IndexBoardingLocation::class)->name('index');
        Route::get('/create', CreateBoardingLocation::class)->name('create'); // ALTERADO
        Route::get('/{boardingLocation}/edit', UpdateBoardingLocation::class)->name('edit'); // ALTERADO
    });


}); // Fim do grupo principal Route::middleware(['auth'])

require __DIR__.'/auth.php';
