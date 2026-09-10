<?php

use App\Http\Controllers\API\AdminDashboardController;
use App\Http\Controllers\API\AdminPengajuanController;
use App\Http\Controllers\API\AdminRoleController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BudayaController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\KulinerController;
use App\Http\Controllers\API\PengajuanUmkmController;
use App\Http\Controllers\API\PengajuanWisataController;
use App\Http\Controllers\API\PesananTiketController;
use App\Http\Controllers\API\ProdukUmkmController;
use App\Http\Controllers\API\SejarahController;
use App\Http\Controllers\API\TiketWisataController;
use App\Http\Controllers\API\UmkmController;
use App\Http\Controllers\API\WisataController;
use App\Http\Middleware\CheckAuthFrontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// =====================================================
// PUBLIC
// =====================================================

Route::get('/', function (Request $request) {
    return 'Laravel ' . app()->version();
});

Route::get('/health-check', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'OK',
    ], 200);
});


// =====================================================
// AUTH
// =====================================================

Route::post('login', [AuthController::class, 'doLogin']);
Route::post('register', [AuthController::class, 'register']);


// =====================================================
// PUBLIC CONTENT
// =====================================================

Route::get('wisata', [WisataController::class, 'index']);
Route::get('wisata/{slug}', [WisataController::class, 'show']);

Route::get('budaya', [BudayaController::class, 'index']);
Route::get('budaya/{slug}', [BudayaController::class, 'show']);

Route::get('umkm', [UmkmController::class, 'index']);
Route::get('umkm/{slug}', [UmkmController::class, 'show']);


Route::get(
    'umkm/{slug}/produk',
    [ProdukUmkmController::class, 'index']
);

Route::get(
    'umkm/{slug}/produk/{produk}',
    [ProdukUmkmController::class, 'show']
);

Route::get('kuliner', [KulinerController::class, 'index']);
Route::get('kuliner/{slug}', [KulinerController::class, 'show']);

Route::get('sejarah', [SejarahController::class, 'index']);
Route::get('sejarah/{slug}', [SejarahController::class, 'show']);

Route::get('event', [EventController::class, 'index']);
Route::get('event/{slug}', [EventController::class, 'show']);
// =====================================================
// AUTHENTICATED API
// =====================================================

Route::middleware('auth.frontend')->group(function () {

    // =================================================
    // PROFILE
    // =================================================

    Route::get(
        'profile',
        [AuthController::class, 'profile']
    );

    Route::post(
        'profile',
        [AuthController::class, 'profileUpdate']
    );

    Route::post(
        'profile/change-password',
        [AuthController::class, 'changePassword']
    );


    // =================================================
    // ADMIN DASHBOARD
    // =================================================

    Route::get(
        'admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->middleware('api.role:1,2');


    // =================================================
    // ADMIN USER MANAGEMENT
    // Menu ID = 2
    // =================================================

    Route::get(
        'admin/users',
        [AdminUserController::class, 'index']
    )->middleware('api.privilege:2,view');

    Route::get(
        'admin/users/{user}',
        [AdminUserController::class, 'show']
    )->middleware('api.privilege:2,view');

    Route::post(
        'admin/users',
        [AdminUserController::class, 'store']
    )->middleware('api.privilege:2,add');

    Route::put(
        'admin/users/{user}',
        [AdminUserController::class, 'update']
    )->middleware('api.privilege:2,edit');

    Route::delete(
        'admin/users/{user}',
        [AdminUserController::class, 'destroy']
    )->middleware('api.privilege:2,delete');

    // =================================================
// ADMIN ROLE MANAGEMENT
// Menu ID = 1
// =================================================

Route::get(
    'admin/roles',
    [AdminRoleController::class, 'index']
)->middleware('api.privilege:1,view');

Route::get(
    'admin/roles/{role}',
    [AdminRoleController::class, 'show']
)->middleware('api.privilege:1,view');

Route::post(
    'admin/roles',
    [AdminRoleController::class, 'store']
)->middleware('api.privilege:1,add');

Route::put(
    'admin/roles/{role}',
    [AdminRoleController::class, 'update']
)->middleware('api.privilege:1,edit');

Route::delete(
    'admin/roles/{role}',
    [AdminRoleController::class, 'destroy']
)->middleware('api.privilege:1,delete');

    // =================================================
    // ADMIN PENGAJUAN
    // =================================================

    Route::get(
        'admin/pengajuan',
        [AdminPengajuanController::class, 'index']
    )->middleware('api.role:1,2');


    // =================================================
    // PENGAJUAN WISATA - USER
    // =================================================

    Route::post(
        'pengajuan/wisata',
        [PengajuanWisataController::class, 'store']
    );

    Route::get(
        'pengajuan/wisata',
        [PengajuanWisataController::class, 'index']
    );

    Route::get(
        'pengajuan/wisata/{pengajuanWisata}',
        [PengajuanWisataController::class, 'show']
    );


    // =================================================
    // PENGAJUAN WISATA - ADMIN / SUPERADMIN
    // =================================================

    Route::put(
        'pengajuan/wisata/{pengajuanWisata}/status',
        [PengajuanWisataController::class, 'updateStatus']
    )->middleware('api.role:1,2');

    Route::delete(
        'pengajuan/wisata/{pengajuanWisata}',
        [PengajuanWisataController::class, 'destroy']
    )->middleware('api.role:1,2');


    // =================================================
    // PENGAJUAN UMKM - USER
    // =================================================

    Route::post(
        'pengajuan/umkm',
        [PengajuanUmkmController::class, 'store']
    );

    Route::get(
        'pengajuan/umkm',
        [PengajuanUmkmController::class, 'index']
    );

    Route::get(
        'pengajuan/umkm/{pengajuanUmkm}',
        [PengajuanUmkmController::class,
        'show']
    );


    // =================================================
    // PENGAJUAN UMKM - ADMIN / SUPERADMIN
    // =================================================

    Route::put(
        'pengajuan/umkm/{pengajuanUmkm}/status',
        [PengajuanUmkmController::class, 'updateStatus']
    )->middleware('api.role:1,2');

    Route::delete(
        'pengajuan/umkm/{pengajuanUmkm}',
        [PengajuanUmkmController::class, 'destroy']
    )->middleware('api.role:1,2');


    // =================================================
    // TIKET WISATA - PENGELOLA WISATA / ADMIN
    // -------------------------------------------------
    // Kepemilikan (wisata->user_id vs userData->id) dan
    // status is_tiket_aktif dicek langsung di dalam
    // TiketWisataController, karena "pengelola wisata"
    // bukan role admin (1,2) melainkan pemilik data itu
    // sendiri.
    // =================================================

    Route::post(
        'wisata/{wisata}/tiket',
        [TiketWisataController::class, 'store']
    );

    Route::put(
        'wisata/{wisata}/tiket/{tiket}',
        [TiketWisataController::class, 'update']
    );

    Route::delete(
        'wisata/{wisata}/tiket/{tiket}',
        [TiketWisataController::class, 'destroy']
    );


    // =================================================
    // PESANAN TIKET - PENGUNJUNG
    // =================================================

    Route::post(
        'wisata/{slug}/pesanan-tiket',
        [PesananTiketController::class, 'store']
    );

    Route::get(
        'pesanan-tiket',
        [PesananTiketController::class, 'index']
    );

    Route::get(
        'pesanan-tiket/{kodePesanan}',
        [PesananTiketController::class, 'show']
    );


    // =================================================
    // CONTENT CRUD - ADMIN / SUPERADMIN
    // =================================================

    // -------------------------
    // WISATA
    // -------------------------

    Route::post(
        'wisata',
        [WisataController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'wisata/{wisata}',
        [WisataController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'wisata/{wisata}',
        [WisataController::class, 'destroy']
    )->middleware('api.role:1,2');



    // =================================================
    // CONTENT CRUD - ADMIN / SUPERADMIN
    // =================================================

    // -------------------------
    // WISATA
    // -------------------------

    Route::post(
        'wisata',
        [WisataController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'wisata/{wisata}',
        [WisataController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'wisata/{wisata}',
        [WisataController::class, 'destroy']
    )->middleware('api.role:1,2');


    // -------------------------
    // BUDAYA
    // -------------------------

    Route::post(
        'budaya',
        [BudayaController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'budaya/{budaya}',
        [BudayaController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'budaya/{budaya}',
        [BudayaController::class, 'destroy']
    )->middleware('api.role:1,2');


    // -------------------------
    // UMKM
    // -------------------------

    Route::post(
        'umkm',
        [UmkmController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'umkm/{umkm}',
        [UmkmController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'umkm/{umkm}',
        [UmkmController::class, 'destroy']
    )->middleware('api.role:1,2');


    // -------------------------
    // KULINER
    // -------------------------

    Route::post(
        'kuliner',
        [KulinerController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'kuliner/{kuliner}',
        [KulinerController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'kuliner/{kuliner}',
        [KulinerController::class, 'destroy']
    )->middleware('api.role:1,2');


    // -------------------------
    // SEJARAH
    // -------------------------

    Route::post(
        'sejarah',
        [SejarahController::class, 'store']
    )->middleware('api.role:1,2');

    Route::put(
        'sejarah/{sejarah}',
        [SejarahController::class, 'update']
    )->middleware('api.role:1,2');

    Route::delete(
        'sejarah/{sejarah}',
        [SejarahController::class, 'destroy']
    )->middleware('api.role:1,2');

});
