<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KecuranganController,
    DistributorController,
    SalesController,
    UserController,
    ProfileController,
    PageController,
    HomeController,
    MenuController
};

// ===============================
// 🏠 Halaman awal
// ===============================
Route::get('/', fn() => view('auth.login'));
Auth::routes();

// ===============================
// 🏠 Home
// ===============================
Route::get('/home', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home');

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

        // Lihat data user
        Route::get('/', [UserController::class, 'index'])
            ->middleware('check.access:Pengaturan,User Management,access')
            ->name('user.index');

        // Tambah user
        Route::get('/create', [UserController::class, 'create'])
            ->middleware('check.access:Pengaturan,User Management,create')
            ->name('user.create');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('check.access:Pengaturan,User Management,create')
            ->name('user.store');

        // Edit user
        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->middleware('check.access:Pengaturan,User Management,edit')
            ->name('user.edit');

        Route::put('/{id}', [UserController::class, 'update'])
            ->middleware('check.access:Pengaturan,User Management,edit')
            ->name('user.update');

        // Hapus user
        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->middleware('check.access:Pengaturan,User Management,delete')
            ->name('user.delete');

        // Atur akses user (dipisah dari edit)
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

            // Hanya lihat data (akses halaman utama)
            Route::get('/', [DistributorController::class, 'index'])
                ->middleware('check.access:Master,Master Distributor,access')
                ->name('distributors.index');

            // Tambah data
            Route::get('/create', [DistributorController::class, 'create'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributors.create');

            Route::post('/', [DistributorController::class, 'store'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributors.store');

            // Edit data
            Route::get('/{id}/edit', [DistributorController::class, 'edit'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributors.edit');

            Route::put('/{id}', [DistributorController::class, 'update'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributors.update');

            // Hapus data
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
            // Lihat data
            Route::get('/data', [DistributorController::class, 'data'])
                ->middleware('check.access:Data,Data Distributor,access')
                ->name('distributors.data');

            // Export Excel
            Route::get('/export-excel', [DistributorController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Distributor,print')
                ->name('distributors.exportExcel');

            // Export PDF
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

            // Lihat halaman utama Sales
            Route::get('/', [SalesController::class, 'index'])
                ->middleware('check.access:Master,Master Sales,access')
                ->name('sales.index');

            // Tambah data Sales
            Route::get('/create', [SalesController::class, 'create'])
                ->middleware('check.access:Master,Master Sales,create')
                ->name('sales.create');

            Route::post('/', [SalesController::class, 'store'])
                ->middleware('check.access:Master,Master Sales,create')
                ->name('sales.store');

            // Edit data Sales
            Route::get('/{id}/edit', [SalesController::class, 'edit'])
                ->middleware('check.access:Master,Master Sales,edit')
                ->name('sales.edit');

            Route::put('/{id}', [SalesController::class, 'update'])
                ->middleware('check.access:Master,Master Sales,edit')
                ->name('sales.update');

            // Hapus data Sales
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

            // Lihat data
            Route::get('/data', [SalesController::class, 'data'])
                ->middleware('check.access:Data,Data Sales,access')
                ->name('sales.data');

            // Export Excel
            Route::get('/export-excel', [SalesController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Sales,print')
                ->name('sales.exportExcel');

            // Export PDF
            Route::get('/export-pdf', [SalesController::class, 'exportPDF'])
                ->middleware('check.access:Data,Data Sales,print')
                ->name('sales.exportPDF');
        });


    // =====================================================
    // ⚠️ MASTER KECURANGAN
    // =====================================================
    Route::prefix('kecurangan')
        ->middleware('check.access:Master,Master Kecurangan')
        ->group(function () {
            Route::get('/', [KecuranganController::class, 'index'])->name('kecurangan.index');
            Route::post('/', [KecuranganController::class, 'store'])->name('kecurangan.store');
            Route::delete('/{id}', [KecuranganController::class, 'destroy'])->name('kecurangan.destroy');
            Route::post('/validasi/{id}', [KecuranganController::class, 'validasi'])->name('kecurangan.validasi');
        });

    // =====================================================
    // 📉 DATA KECURANGAN
    // =====================================================
    Route::prefix('kecurangan')
        ->middleware('check.access:Data,Data Kecurangan')
        ->group(function () {
            Route::get('/data', [KecuranganController::class, 'data'])->name('kecurangan.data');
            Route::get('/sales/{id}', [KecuranganController::class, 'getSales'])->name('kecurangan.getSales');
            Route::get('/export-excel', [KecuranganController::class, 'exportExcel'])->name('kecurangan.exportExcel');
            Route::get('/export-pdf', [KecuranganController::class, 'exportPDF'])->name('kecurangan.exportPDF');
        });

    // =====================================================
    // 🧩 MENU MANAGEMENT (Pindah ke atas dari route {page})
    // =====================================================

    Route::prefix('menus')
        ->middleware('check.access:Pengaturan,Menu Management')
        ->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('menus.index');
            Route::get('/create', [MenuController::class, 'create'])->name('menus.create');
            Route::post('/', [MenuController::class, 'store'])->name('menus.store');

            // ✅ Tambahkan dua baris ini:
            Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
            Route::put('/{id}', [MenuController::class, 'update'])->name('menus.update');

            Route::delete('/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');
        });


    // =====================================================
    // 📄 PAGE CONTROLLER (paling bawah)
    // =====================================================
    Route::get('{page}', function ($page) {
        $allowedPages = ['dashboard'];
        if (!in_array($page, $allowedPages)) {
            abort(404, 'Halaman tidak ditemukan.');
        }
        return app(PageController::class)->index($page);
    })->name('page.index');
    
});