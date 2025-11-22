<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    PageController,
    ProfileController,
    UserController,
    MenuController,
    DistributorController,
    SalesmanController,
    AssController,
    SpecialistManagerController,
    AsistenManagerController,
    SanksiController,
    KecuranganController
};

/* ============================================================
   ROOT & GLOBAL
   ============================================================ */

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $hasAccess = DB::table('user_access')
        ->where('user_id', auth()->id())
        ->exists();

    return $hasAccess
        ? redirect()->route('welcome')
        : view('welcome');
});

// Apple icons
Route::get('/apple-touch-icon.png', fn () => response()->file(public_path('apple-touch-icon.png')));
Route::get('/apple-touch-icon-precomposed.png', fn () => response()->file(public_path('apple-touch-icon.png')));

Auth::routes();

Route::get('/welcome', fn () => view('welcome'))
    ->middleware('auth')
    ->name('welcome');


/* ============================================================
   DASHBOARD
   ============================================================ */

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'check.access:Dashboard,null,access'])
    ->name('dashboard');


/* ============================================================
   AUTH GROUP
   ============================================================ */

Route::middleware('auth')->group(function () {

/* ============================================================
   PROFILE (SELALU BOLEH)
   ============================================================ */

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');


/* ============================================================
   USER MANAGEMENT
   ============================================================ */

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


/* ============================================================
   MENU MANAGEMENT
   ============================================================ */

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


/* ============================================================
   MASTER: DISTRIBUTOR
   ============================================================ */

    Route::prefix('distributor')
        ->middleware('check.access:Master,Master Distributor')
        ->group(function () {

            Route::get('/', [DistributorController::class, 'index'])
                ->middleware('check.access:Master,Master Distributor,access')
                ->name('distributor.index');

            Route::get('/create', [DistributorController::class, 'create'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributor.create');

            Route::post('/', [DistributorController::class, 'store'])
                ->middleware('check.access:Master,Master Distributor,create')
                ->name('distributor.store');

            Route::get('/{ID_DISTRIBUTOR}/edit', [DistributorController::class, 'edit'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributor.edit');

            Route::put('/{ID_DISTRIBUTOR}', [DistributorController::class, 'update'])
                ->middleware('check.access:Master,Master Distributor,edit')
                ->name('distributor.update');

            Route::delete('/{ID_DISTRIBUTOR}', [DistributorController::class, 'destroy'])
                ->middleware('check.access:Master,Master Distributor,delete')
                ->name('distributor.destroy');
        });


/* ============================================================
   DATA: DISTRIBUTOR
   ============================================================ */

    Route::prefix('distributor')
        ->middleware('check.access:Data,Data Distributor')
        ->group(function () {

            Route::get('/data', [DistributorController::class, 'data'])
                ->middleware('check.access:Data,Data Distributor,access')
                ->name('distributor.data');

            Route::get('/export-excel', [DistributorController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Distributor,print')
                ->name('distributor.exportExcel');

            Route::get('/export-pdf', [DistributorController::class, 'exportPdf'])
                ->middleware('check.access:Data,Data Distributor,print')
                ->name('distributor.exportPdf');
        });


/* ============================================================
   DATA: SALESMAN
   ============================================================ */

    Route::prefix('salesman')
        ->middleware('check.access:Data,Data Salesman')
        ->group(function () {

            Route::get('/data', [SalesmanController::class, 'data'])
                ->middleware('check.access:Data,Data Salesman,access')
                ->name('salesman.data');

            Route::get('/get-kecurangan/{idSales}', [SalesmanController::class, 'getKecurangan'])
                ->middleware('check.access:Data,Data Salesman,access')
                ->name('salesman.getKecurangan');

            Route::get('/export-excel', [SalesmanController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data Salesman,print')
                ->name('salesman.exportExcel');

            Route::get('/export-pdf', [SalesmanController::class, 'exportPdf'])
                ->middleware('check.access:Data,Data Salesman,print')
                ->name('salesman.exportPdf');
        });


    /* ============================================================
   DATA: ASS
   ============================================================ */

    Route::prefix('ass')
        ->middleware('check.access:Data,Data ASS')
        ->group(function () {

            Route::get('/data', [AssController::class, 'data'])
                ->middleware('check.access:Data,Data ASS,access')
                ->name('ass.data');

            Route::get('/get-kecurangan/{idSales}', [AssController::class, 'getKecurangan'])
                ->middleware('check.access:Data,Data ASS,access')
                ->name('ass.getKecurangan');

            Route::get('/export-excel', [AssController::class, 'exportExcel'])
                ->middleware('check.access:Data,Data ASS,print')
                ->name('ass.exportExcel');

            Route::get('/export-pdf', [AssController::class, 'exportPdf'])
                ->middleware('check.access:Data,Data ASS,print')
                ->name('ass.exportPdf');
        });

/* ============================================================
   MASTER: SANKSI
   ============================================================ */

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


/* ============================================================
   MASTER: KECURANGAN
   ============================================================ */

    Route::prefix('kecurangan')
        ->middleware('check.access:Master,Master Kecurangan')
        ->group(function () {

            Route::get('/', [KecuranganController::class, 'index'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.index');

            Route::get('/create', [KecuranganController::class, 'create'])
                ->middleware('check.access:Master,Master Kecurangan,create')
                ->name('kecurangan.create');

            Route::get('/sales/{id}', [KecuranganController::class, 'getSales'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.getSales');

            Route::get('/ass/list', [KecuranganController::class, 'getAss'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.getAss');

            Route::get('/ass/{idSales}', [KecuranganController::class, 'getAss'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.getAss');

            Route::get('/nilai/{jenis}/{deskripsi}', [KecuranganController::class, 'getNilai'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.getNilai');

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

            Route::get('/customer/{idSales}', [KecuranganController::class, 'getCustomer'])
                ->middleware('check.access:Master,Master Kecurangan,access')
                ->name('kecurangan.getCustomer');
        });


/* ============================================================
   DATA: KECURANGAN
   ============================================================ */

    Route::prefix('kecurangan')
        ->middleware('check.access:Data,Data Kecurangan')
        ->group(function () {

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

            // ❗ HANYA DI SINI — AJAX dropdown
            Route::get('/get-keterangan/{jenis}', [KecuranganController::class, 'getKeteranganByJenis'])
                ->middleware('check.access:Data,Data Kecurangan,access')
                ->name('kecurangan.getKeteranganByJenis');
        });

});


/* ============================================================
   DYNAMIC PAGE (LAST)
   ============================================================ */

Route::get('{page}', function ($page) {
    $allowedPages = ['dashboard'];

    abort_unless(in_array($page, $allowedPages), 404, 'Halaman tidak ditemukan.');

    return app(PageController::class)->index($page);
})->name('page.index');