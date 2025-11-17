<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KecuranganController,
    DistributorController,
    SalesController,
    UserController,
    ProfileController,
    PageController,
    DashboardController,
    AsistenManagerController,
    MenuController,
    SanksiController
};

// ===============================
// 🏠 Halaman awal
// ===============================
Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // cek apakah user punya akses menu apa pun
    $hasAccess = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->exists();

    if ($hasAccess) {
        return redirect()->route('dashboard');
    }

    // jika user tidak punya akses sama sekali
    return view('welcome');
});

Auth::routes();

Route::get('/welcome', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');


// ===============================
// 🏠 Dashboard
// ===============================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'check.access:Dashboard,null,access'])
    ->name('dashboard');

// ===============================
// 🔐 Group halaman setelah login
// ===============================
Route::middleware('auth')->group(function () {

    // ===============================
    // ⚙️ User Management
    // ===============================
    Route::prefix('users')
        ->middleware('check.access:Pengaturan,User Management')
        ->group(function () {

            Route::get('/', [UserController::class, 'index'])
                ->middleware('check.access:Pengaturan,User Management,access')
                ->name('user.index');

            Route::get('/create', [UserController::class, 'create'])
                ->middleware('check.access:Pengaturan,User Management,create')
                ->name('user.create');

            Route::post('/', [UserController::class, 'store'])
                ->middleware('check.access:Pengaturan,User Management,create')
                ->name('user.store');

            Route::get('/{id}/edit', [UserController::class, 'edit'])
                ->middleware('check.access:Pengaturan,User Management,edit')
                ->name('user.edit');

            Route::put('/{id}', [UserController::class, 'update'])
                ->middleware('check.access:Pengaturan,User Management,edit')
                ->name('user.update');

            Route::delete('/{id}', [UserController::class, 'destroy'])
                ->middleware('check.access:Pengaturan,User Management,delete')
                ->name('user.delete');

            Route::get('/{id}/access', [UserController::class, 'access'])
                ->middleware('check.access:Pengaturan,User Management,access')
                ->name('user.access');

            Route::put('/{id}/access', [UserController::class, 'updateAccess'])
                ->middleware('check.access:Pengaturan,User Management,access')
                ->name('user.updateAccess');
        });

    // ===============================
    // 👤 Profile (selalu boleh diakses)
    // ===============================
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');

    // =====================================================
    // 🏢 MASTER DISTRIBUTOR
    // =====================================================
    Route::prefix('distributors')
        ->middleware('check.access:Master,Master Distributor')
        ->group(function () {

            Route::get('/', [DistributorController::class, 'index'])
                ->middleware('check.access:Master,Master Distributor,access')
                ->name('distributors.index');

            Route::get('/create', [DistributorController::class, 'create'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributors.create');

            Route::post('/', [DistributorController::class, 'store'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributors.store');

            Route::get('/{id}/edit', [DistributorController::class, 'edit'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributors.edit');

            Route::put('/{id}', [DistributorController::class, 'update'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributors.update');

            Route::delete('/{id}', [DistributorController::class, 'destroy'])
                ->middleware('check.access:Master,Master Distributor,delete')
                ->name('distributors.destroy');
        });

    // =====================================================
    // 📊 DATA DISTRIBUTOR
    // =====================================================
    Route::prefix('distributors')
        ->middleware('check.access:Data,Data Distributor')
        ->group(function () {
            Route::get('/data', [DistributorController::class, 'data'])
                ->middleware('check.access:Data,Data Distributor,access')
                ->name('distributors.data');

            Route::get('/export-excel', [DistributorController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Distributor,print')
                ->name('distributors.exportExcel');

            Route::get('/export-pdf', [DistributorController::class, 'exportPDF'])
                ->middleware('check.access:Data,Data Distributor,print')
                ->name('distributors.exportPDF');
        });

    // =====================================================
    // 🧾 MASTER SALES
    // =====================================================
    Route::prefix('sales')
        ->middleware('check.access:Master,Master Sales')
        ->group(function () {
            Route::get('/', [SalesController::class, 'index'])
                ->middleware('check.access:Master,Master Sales,access')
                ->name('sales.index');

            Route::get('/create', [SalesController::class, 'create'])
                ->middleware('check.access:Master,Master Sales,create')
                ->name('sales.create');

            Route::post('/', [SalesController::class, 'store'])
                ->middleware('check.access:Master,Master Sales,create')
                ->name('sales.store');

            Route::get('/{id}/edit', [SalesController::class, 'edit'])
                ->middleware('check.access:Master,Master Sales,edit')
                ->name('sales.edit');

            Route::put('/{id}', [SalesController::class, 'update'])
                ->middleware('check.access:Master,Master Sales,edit')
                ->name('sales.update');

            Route::delete('/{id}', [SalesController::class, 'destroy'])
                ->middleware('check.access:Master,Master Sales,delete')
                ->name('sales.destroy');
        });

    // =====================================================
    // 📈 DATA SALES
    // =====================================================
    Route::prefix('sales')
        ->middleware('check.access:Data,Data Sales')
        ->group(function () {
            Route::get('/data', [SalesController::class, 'data'])
                ->middleware('check.access:Data,Data Sales,access')
                ->name('sales.data');

            Route::get('/export-excel', [SalesController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Sales,print')
                ->name('sales.exportExcel');

            Route::get('/export-pdf', [SalesController::class, 'exportPDF'])
                ->middleware('check.access:Data,Data Sales,print')
                ->name('sales.exportPDF');
        });

    // =====================================================
    // ⚠️ MASTER & DATA KECURANGAN
    // =====================================================

Route::prefix('kecurangan')->group(function () {

    // ===============================
    // 📋 MASTER KECURANGAN
    // ===============================
    Route::middleware('check.access:Master,Master Kecurangan')->group(function () {

        Route::get('/', [KecuranganController::class, 'index'])
            ->middleware('check.access:Master,Master Kecurangan,access')
            ->name('kecurangan.index');

        Route::get('/create', [KecuranganController::class, 'create'])
            ->middleware('check.access:Master,Master Kecurangan,create')
            ->name('kecurangan.create');

        Route::get('/sales/{id_distributor}', [KecuranganController::class, 'getSales'])
            ->middleware('check.access:Master,Master Kecurangan,access')
            ->name('kecurangan.getSales');

        Route::get('/asisten-manager/{id}', [KecuranganController::class, 'getAsistenManager'])
            ->middleware('check.access:Master,Master Kecurangan,access')
            ->name('kecurangan.getAsistenManager');

        Route::get('/get-keterangan', [KecuranganController::class, 'getKeteranganByJenis'])
            ->middleware('check.access:Master,Master Kecurangan,access')
            ->name('kecurangan.getKeteranganByJenis');

        Route::post('/', [KecuranganController::class, 'store'])
            ->middleware('check.access:Master,Master Kecurangan,create')
            ->name('kecurangan.store');

        Route::get('/{id}/edit', [KecuranganController::class, 'edit'])
            ->middleware('check.access:Master,Master Kecurangan,edit')
            ->name('kecurangan.edit');

        Route::put('/{id}', [KecuranganController::class, 'update'])
            ->middleware('check.access:Master,Master Kecurangan,edit')
            ->name('kecurangan.update');

        Route::delete('/{id}', [KecuranganController::class, 'destroy'])
            ->middleware('check.access:Master,Master Kecurangan,delete')
            ->name('kecurangan.destroy');

        Route::post('/validasi/{id}', [KecuranganController::class, 'validasi'])
            ->middleware('check.access:Master,Master Kecurangan,edit')
            ->name('kecurangan.validasi');

        Route::post('/{id}/upload-bukti', [KecuranganController::class, 'uploadBukti'])
            ->middleware('check.access:Master,Master Kecurangan,create')
            ->name('kecurangan.uploadBukti');

        Route::delete('/bukti/{id}', [KecuranganController::class, 'hapusBukti'])
            ->middleware('check.access:Master,Master Kecurangan,delete')
            ->name('kecurangan.hapusBukti');

        Route::delete('/foto/{id}', [KecuranganController::class, 'hapusFoto'])
            ->middleware('check.access:Master,Master Kecurangan,delete')
            ->name('kecurangan.hapusFoto');
    });

    // ===============================
    // 📉 DATA KECURANGAN
    // ===============================
    Route::middleware('check.access:Data,Data Kecurangan')->group(function () {

        Route::get('/data', [KecuranganController::class, 'data'])
            ->middleware('check.access:Data,Data Kecurangan,access')
            ->name('kecurangan.data');

        Route::get('/export-excel', [KecuranganController::class, 'exportExcel'])
            ->middleware('check.access:Data,Data Kecurangan,print')
            ->name('kecurangan.exportExcel');

        Route::get('/export-pdf', [KecuranganController::class, 'exportPDF'])
            ->middleware('check.access:Data,Data Kecurangan,print')
            ->name('kecurangan.exportPDF');

        Route::get('/{id}/bukti', [KecuranganController::class, 'getBukti'])
            ->middleware('check.access:Data,Data Kecurangan,access')
            ->name('kecurangan.getBukti');

        
    });
});

    // =====================================================
    // 🧩 MENU MANAGEMENT
    // =====================================================
    Route::prefix('menus')
        ->middleware('check.access:Pengaturan,Menu Management')
        ->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('menus.index');
            Route::get('/create', [MenuController::class, 'create'])->name('menus.create');
            Route::post('/', [MenuController::class, 'store'])->name('menus.store');
            Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
            Route::put('/{id}', [MenuController::class, 'update'])->name('menus.update');
            Route::delete('/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');
        });

    // =====================================================
    // 🧑‍💼 MASTER & DATA ASISTEN MANAGER (ASS)
    // =====================================================
    Route::prefix('asisten_manager')
        ->middleware('check.access:Master,Master ASS')
        ->group(function () {
            Route::get('/', [AsistenManagerController::class, 'index'])
                ->middleware('check.access:Master,Master ASS,access')
                ->name('asisten_manager.index');

            Route::get('/create', [AsistenManagerController::class, 'create'])
                ->middleware('check.access:Master,Master ASS,create')
                ->name('asisten_manager.create');

            Route::post('/', [AsistenManagerController::class, 'store'])
                ->middleware('check.access:Master,Master ASS,create')
                ->name('asisten_manager.store');

            Route::get('/{id}/edit', [AsistenManagerController::class, 'edit'])
                ->middleware('check.access:Master,Master ASS,edit')
                ->name('asisten_manager.edit');

            Route::put('/{id}', [AsistenManagerController::class, 'update'])
                ->middleware('check.access:Master,Master ASS,edit')
                ->name('asisten_manager.update');

            Route::delete('/{id}', [AsistenManagerController::class, 'destroy'])
                ->middleware('check.access:Master,Master ASS,delete')
                ->name('asisten_manager.destroy');
        });

    Route::prefix('asisten_manager')
        ->middleware('check.access:Data,Data ASS')
        ->group(function () {
            Route::get('/data', [AsistenManagerController::class, 'data'])
                ->middleware('check.access:Data,Data ASS,access')
                ->name('asisten_manager.data');

            Route::get('/export-excel', [AsistenManagerController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data ASS,print')
                ->name('asisten_manager.exportExcel');

            Route::get('/export-pdf', [AsistenManagerController::class, 'exportPDF'])
                ->middleware('check.access:Data,Data ASS,print')
                ->name('asisten_manager.exportPDF');
        });


Route::prefix('sanksi')
    ->middleware('check.access:Master,Master Sanksi')
    ->group(function () {

        Route::get('/', [SanksiController::class, 'index'])
            ->middleware('check.access:Master,Master Sanksi,access')
            ->name('sanksi.index');

        Route::get('/create', [SanksiController::class, 'create'])
            ->middleware('check.access:Master,Master Sanksi,create')
            ->name('sanksi.create');

        Route::post('/', [SanksiController::class, 'store'])
            ->middleware('check.access:Master,Master Sanksi,create')
            ->name('sanksi.store');

        Route::get('/{id}/edit', [SanksiController::class, 'edit'])
            ->middleware('check.access:Master,Master Sanksi,edit')
            ->name('sanksi.edit');

        Route::put('/{id}', [SanksiController::class, 'update'])
            ->middleware('check.access:Master,Master Sanksi,edit')
            ->name('sanksi.update');

        Route::delete('/{id}', [SanksiController::class, 'destroy'])
            ->middleware('check.access:Master,Master Sanksi,delete')
            ->name('sanksi.destroy');

        Route::get('/deskripsi/{jenis}', [SanksiController::class, 'getDeskripsiByJenis'])
            ->name('sanksi.deskripsi');

        Route::get('/nilai/{jenis}/{deskripsi}', [SanksiController::class, 'getNilaiByDeskripsi'])
            ->where('deskripsi', '.*')
            ->name('sanksi.nilai');


    });


    // =====================================================
    // 📄 PAGE CONTROLLER
    // =====================================================
    Route::get('{page}', function ($page) {
        $allowedPages = ['dashboard'];
        if (!in_array($page, $allowedPages)) {
            abort(404, 'Halaman tidak ditemukan.');
        }
        return app(PageController::class)->index($page);
    })->name('page.index');
});