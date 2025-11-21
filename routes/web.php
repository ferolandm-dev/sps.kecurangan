<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KecuranganController,
    DistributorController,
    SalesmanController,
    SpecialistManagerController,
    UserController,
    ProfileController,
    PageController,
    DashboardController,
    AsistenManagerController,
    MenuController,
    SanksiController
};

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // cek apakah user punya akses menu apa pun
    $hasAccess = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->exists();

    if ($hasAccess) {
        return redirect()->route('welcome');
    }

    // jika user tidak punya akses sama sekali
    return view('welcome');
});

// routes/web.php
Route::get('/apple-touch-icon.png', function () {
    return response()->file(public_path('apple-touch-icon.png'));
});
Route::get('/apple-touch-icon-precomposed.png', function () {
    return response()->file(public_path('apple-touch-icon.png'));
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
// 📌 MASTER DISTRIBUTOR
// =====================================================
Route::prefix('distributor')
    ->middleware('check.access:Master,Master Distributor')
    ->group(function () {

        // LIST & SEARCH
        Route::get('/', [DistributorController::class, 'index'])
            ->middleware('check.access:Master,Master Distributor,access')
            ->name('distributor.index');

        // CREATE FORM
        Route::get('/create', [DistributorController::class, 'create'])
            ->middleware('check.access:Master,Master Distributor,create')
            ->name('distributor.create');

        // SUBMIT CREATE
        Route::post('/', [DistributorController::class, 'store'])
            ->middleware('check.access:Master,Master Distributor,create')
            ->name('distributor.store');

        // EDIT FORM
        Route::get('/{ID_DISTRIBUTOR}/edit', [DistributorController::class, 'edit'])
            ->middleware('check.access:Master,Master Distributor,edit')
            ->name('distributor.edit');

        // SUBMIT UPDATE
        Route::put('/{ID_DISTRIBUTOR}', [DistributorController::class, 'update'])
            ->middleware('check.access:Master,Master Distributor,edit')
            ->name('distributor.update');

        // DELETE
        Route::delete('/{ID_DISTRIBUTOR}', [DistributorController::class, 'destroy'])
            ->middleware('check.access:Master,Master Distributor,delete')
            ->name('distributor.destroy');
    });


// =====================================================
// 📊 DATA DISTRIBUTOR
// =====================================================
Route::prefix('distributor')
    ->middleware('check.access:Data,Data Distributor')
    ->group(function () {

        // PAGE data distributor
        Route::get('/data', [DistributorController::class, 'data'])
            ->middleware('check.access:Data,Data Distributor,access')
            ->name('distributor.data');

        // EXPORT EXCEL
        Route::get('/export-excel', [DistributorController::class, 'exportExcel'])
            ->middleware('check.access:Data,Data Distributor,print')
            ->name('distributor.exportExcel');

        // EXPORT PDF
        Route::get('/export-pdf', [DistributorController::class, 'exportPdf'])
            ->middleware('check.access:Data,Data Distributor,print')
            ->name('distributor.exportPdf');
    });



// ==================================================================================
// DATA SALESMAN (HANYA DATA + EXPORT)
// ==================================================================================
Route::prefix('salesman')
    ->middleware('check.access:Data,Data Salesman')
    ->group(function () {

        // LIST
        Route::get('/data', [SalesmanController::class, 'data'])
            ->middleware('check.access:Data,Data Salesman,access')
            ->name('salesman.data');

        // AJAX — Ambil data kecurangan valid per salesman (untuk modal)
        Route::get('/get-kecurangan/{idSales}', [SalesmanController::class, 'getKecurangan'])
            ->middleware('check.access:Data,Data Salesman,access')
            ->name('salesman.getKecurangan');


        // Export Excel
        Route::get('/export-excel', [SalesmanController::class, 'exportExcel'])
            ->middleware('check.access:Data,Data Salesman,print')
            ->name('salesman.exportExcel');

        // Export PDF
        Route::get('/export-pdf', [SalesmanController::class, 'exportPdf'])
            ->middleware('check.access:Data,Data Salesman,print')
            ->name('salesman.exportPdf');
    });



Route::prefix('specialist-manager')
    ->middleware('check.access:Data,Data Specialist Manager')
    ->group(function () {

        Route::get('/data', [SpecialistManagerController::class, 'data'])
            ->middleware('check.access:Data,Data Specialist Manager,access')
            ->name('specialist_manager.data');

        Route::get('/export-excel', [SpecialistManagerController::class, 'exportExcel'])
            ->middleware('check.access:Data,Data Specialist Manager,print')
            ->name('specialist_manager.exportExcel');

        Route::get('/export-pdf', [SpecialistManagerController::class, 'exportPdf'])
            ->middleware('check.access:Data,Data Specialist Manager,print')
            ->name('specialist_manager.exportPdf');

        Route::get('/get-user/{namaManager}', [SpecialistManagerController::class, 'getUserByManager'])
            ->middleware('check.access:Data,Data Specialist Manager,access')
            ->name('specialist_manager.getUser');

    });




    // =====================================================
    // ⚠️ MASTER & DATA KECURANGAN
    // =====================================================

Route::prefix('kecurangan')->group(function () {

    // ===============================
    // 📋 MASTER KECURANGAN
    // ===============================
    Route::middleware('check.access:Master,Master Kecurangan')->group(function () {

    // INDEX
    Route::get('/', [KecuranganController::class, 'index'])
        ->middleware('check.access:Master,Master Kecurangan,access')
        ->name('kecurangan.index');

    // CREATE PAGE
    Route::get('/create', [KecuranganController::class, 'create'])
        ->middleware('check.access:Master,Master Kecurangan,create')
        ->name('kecurangan.create');

    // === GET SALES (AJAX) ===
    Route::get('/sales/{id}', [KecuranganController::class, 'getSales'])
        ->middleware('check.access:Master,Master Kecurangan,access')
        ->name('kecurangan.getSales');

    // === GET ASS LIST (AJAX, TYPE_SALESMAN = 7) ===
    Route::get('/ass/list', [KecuranganController::class, 'getAss'])
        ->middleware('check.access:Master,Master Kecurangan,access')
        ->name('kecurangan.getAss');

    // === GET ASS (AJAX) ===
    Route::get('/ass/{idSales}', [KecuranganController::class, 'getAss'])
        ->middleware('check.access:Master,Master Kecurangan,access')
        ->name('kecurangan.getAss');

    // === GET DESKRIPSI SANKSI (AJAX) ===
    Route::get('/get-keterangan', [KecuranganController::class, 'getKeteranganByJenis'])
        ->middleware('check.access:Master,Master Kecurangan,access')
        ->name('kecurangan.getKeteranganByJenis');

    // STORE
    Route::post('/', [KecuranganController::class, 'store'])
        ->middleware('check.access:Master,Master Kecurangan,create')
        ->name('kecurangan.store');

    // EDIT PAGE
    Route::get('/{id}/edit', [KecuranganController::class, 'edit'])
        ->middleware('check.access:Master,Master Kecurangan,edit')
        ->name('kecurangan.edit');

    // UPDATE
    Route::put('/{id}', [KecuranganController::class, 'update'])
        ->middleware('check.access:Master,Master Kecurangan,edit')
        ->name('kecurangan.update');

    // DELETE
    Route::delete('/{id}', [KecuranganController::class, 'destroy'])
        ->middleware('check.access:Master,Master Kecurangan,delete')
        ->name('kecurangan.destroy');

    // VALIDASI
    Route::post('/validasi/{id}', [KecuranganController::class, 'validasi'])
        ->middleware('check.access:Master,Master Kecurangan,edit')
        ->name('kecurangan.validasi');
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