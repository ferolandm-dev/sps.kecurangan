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
   DATA: DISTRIBUTOR
   ============================================================ */

    Route::prefix('distributor')
        ->middleware('check.access:Master,Master Distributor,access')
        ->group(function () {

            Route::get('/data', [DistributorController::class, 'data'])
                ->name('distributor.data');

            Route::get('/export-excel', [DistributorController::class, 'exportExcel'])
                ->name('distributor.exportExcel');

            Route::get('/export-pdf', [DistributorController::class, 'exportPdf'])
                ->name('distributor.exportPdf');
        });



/* ============================================================
   DATA: SALESMAN
   ============================================================ */

    Route::prefix('salesman')
        ->middleware('check.access:Master,Master Salesman,access')
        ->group(function () {

            Route::get('/data', [SalesmanController::class, 'data'])
                ->name('salesman.data');

            Route::get('/get-kecurangan/{idSales}', [SalesmanController::class, 'getKecurangan'])
                ->name('salesman.getKecurangan');

            Route::get('/export-excel', [SalesmanController::class, 'exportExcel'])
                ->name('salesman.exportExcel');

            Route::get('/export-pdf', [SalesmanController::class, 'exportPdf'])
                ->name('salesman.exportPdf');
        });



    /* ============================================================
   DATA: ASS
   ============================================================ */

    Route::prefix('ass')
        ->middleware('check.access:Master,Master ASS')
        ->group(function () {

            Route::get('/data', [AssController::class, 'data'])
                ->name('ass.data');

            Route::get('/get-kecurangan/{idSales}', [AssController::class, 'getKecurangan'])
                ->name('ass.getKecurangan');

            Route::get('/export-excel', [AssController::class, 'exportExcel'])
                ->name('ass.exportExcel');

            Route::get('/export-pdf', [AssController::class, 'exportPdf'])
                ->name('ass.exportPdf');
        });

/* ============================================================
   MASTER: SANKSI
   ============================================================ */

    Route::prefix('sanksi')
        ->middleware('check.access:Master,Master Sanksi')
        ->group(function () {

            Route::get('/', [SanksiController::class, 'index'])
                ->name('sanksi.index');

            Route::get('/create', [SanksiController::class, 'create'])
                ->name('sanksi.create');

            Route::post('/', [SanksiController::class, 'store'])
                ->name('sanksi.store');

            Route::get('/{id}/edit', [SanksiController::class, 'edit'])
                ->name('sanksi.edit');

            Route::put('/{id}', [SanksiController::class, 'update'])
                ->name('sanksi.update');

            Route::delete('/{id}', [SanksiController::class, 'destroy'])
                ->name('sanksi.destroy');

            Route::get('/deskripsi/{jenis}', [SanksiController::class, 'getDeskripsiByJenis'])
                ->name('sanksi.deskripsi');

            Route::get('/nilai/{jenis}/{deskripsi}', [SanksiController::class, 'getNilaiByDeskripsi'])
                ->where('deskripsi', '.*')
                ->name('sanksi.nilai');
        });


/* ============================================================
   DATA: TRANSAKSI KASUS
   ============================================================ */

    Route::prefix('kecurangan')
        ->middleware('check.access:Transaksi,Transaksi Kasus,access')
        ->group(function () {

            // INDEX
            Route::get('/', [KecuranganController::class, 'index'])
                ->name('kecurangan.index');

            // CREATE
            Route::get('/create', [KecuranganController::class, 'create'])
                ->name('kecurangan.create');

            // GET SALES
            Route::get('/sales/{id}', [KecuranganController::class, 'getSales'])
                ->name('kecurangan.getSales');

            // GET ASS
            Route::get('/ass/list', [KecuranganController::class, 'getAss'])
                ->name('kecurangan.getAss');

            Route::get('/ass/{idSales}', [KecuranganController::class, 'getAss'])
                ->name('kecurangan.getAss');

            // GET NILAI
            Route::get('/nilai/{jenis}/{deskripsi}', [KecuranganController::class, 'getNilai'])
                ->name('kecurangan.getNilai');

            // STORE
            Route::post('/', [KecuranganController::class, 'store'])
                ->name('kecurangan.store');

            // EDIT
            Route::get('/{id}/edit', [KecuranganController::class, 'edit'])
                ->name('kecurangan.edit');

            // UPDATE
            Route::put('/{id}', [KecuranganController::class, 'update'])
                ->name('kecurangan.update');

            // DELETE
            Route::delete('/{id}', [KecuranganController::class, 'destroy'])
                ->name('kecurangan.destroy');

            // VALIDASI
            Route::post('/validasi/{id}', [KecuranganController::class, 'validasi'])
                ->name('kecurangan.validasi');

            // GET CUSTOMER
            Route::get('/customer/{idSales}', [KecuranganController::class, 'getCustomer'])
                ->name('kecurangan.getCustomer');
        });


/* ============================================================
   REPORT: KASUS
   ============================================================ */

    Route::prefix('kecurangan')
        ->middleware('check.access:Report,Report Kasus')
        ->group(function () {

            Route::get('/data', [KecuranganController::class, 'data'])
                ->name('kecurangan.data');

            Route::get('/export-excel', [KecuranganController::class, 'exportExcel'])
                ->name('kecurangan.exportExcel');

            Route::get('/export-pdf', [KecuranganController::class, 'exportPDF'])
                ->name('kecurangan.exportPDF');

            Route::get('/{id}/bukti', [KecuranganController::class, 'getBukti'])
                ->name('kecurangan.getBukti');

            Route::get('/get-keterangan/{jenis}', [KecuranganController::class, 'getKeteranganByJenis'])
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