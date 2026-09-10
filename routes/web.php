<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CMS\AuthController;
use App\Http\Controllers\CMS\DashboardController;
use App\Http\Controllers\CMS\Configuration\GroupMenuController;
use App\Http\Controllers\CMS\Configuration\MenuController;
use App\Http\Controllers\CMS\User\UserController;
use App\Http\Controllers\CMS\User\RoleController;
use App\Http\Controllers\CMS\Master\RoleController as RoleMaster;
use App\Http\Controllers\CMS\Master\WisataController;
use App\Http\Controllers\CMS\Master\BudayaController;
use App\Http\Controllers\CMS\Master\UmkmController;
use App\Http\Controllers\CMS\Master\ProdukUmkmController;
use App\Http\Controllers\CMS\Master\KulinerController;
use App\Http\Controllers\CMS\Master\SejarahController;
use App\Http\Controllers\CMS\Master\EventController;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckPriviledge;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/check', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'index');
    Route::post('login', 'doLogin');
});

// start checking auth
Route::middleware([CheckAuth::class])->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('logout', 'logout');

        Route::get('profile', 'profile');
        Route::post('profile', 'profileUpdate');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('group-menu')->group(function () {
        Route::controller(GroupMenuController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
            Route::get('fn-get-data', 'fnGetData');
        });
    });

    Route::prefix('menu')->group(function () {
        Route::controller(MenuController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
            Route::get('fn-get-data', 'fnGetData');
        });
    });

    Route::prefix('master')->group(function () {
        Route::controller(RoleMaster::class)->group(function () {
            Route::get('role', 'index');
        });
    });

// start checking priviledge
Route::middleware([CheckPriviledge::class])->group(function () {

    Route::prefix('users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('detail/{id}', 'detail');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
            Route::get('fn-get-data', 'fnGetData');
        });
    });

    Route::prefix('roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
            Route::get('fn-get-data', 'fnGetData');
        });
    });

    // =========================
    // MANAGE WISATA
    // =========================
    Route::prefix('admin/wisata')->group(function () {
        Route::controller(WisataController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
        });
    });

    Route::prefix('admin/budaya')->group(function () {
        Route::controller(BudayaController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
        });
    });

    Route::prefix('admin/umkm')->group(function () {
        Route::controller(UmkmController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
        });
    });

    Route::prefix('admin/kuliner')->group(function () {
        Route::controller(KulinerController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
        });
    });

    Route::prefix('admin/sejarah')->group(function () {
        Route::controller(SejarahController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('store', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('update/{id}', 'update');
            Route::get('delete/{id}', 'delete');
        });
    });

// =========================
// MANAGE EVENT
// =========================
Route::prefix('admin/event')->group(function () {
    Route::controller(EventController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('create', 'create');
        Route::post('store', 'store');
        Route::get('edit/{id}', 'edit');
        Route::post('update/{id}', 'update');
        Route::get('delete/{id}', 'delete');
    });
});
Route::prefix('admin')->group(function () {

    Route::get(
        'umkm/{umkm}/produk',
        [ProdukUmkmController::class, 'index']
    )->name('admin.umkm.produk.index');

    Route::get(
        'umkm/{umkm}/produk/create',
        [ProdukUmkmController::class, 'create']
    )->name('admin.umkm.produk.create');

    Route::post(
        'umkm/{umkm}/produk',
        [ProdukUmkmController::class, 'store']
    )->name('admin.umkm.produk.store');

    Route::get(
        'umkm/{umkm}/produk/{produk}/edit',
        [ProdukUmkmController::class, 'edit']
    )->name('admin.umkm.produk.edit');

    Route::put(
        'umkm/{umkm}/produk/{produk}',
        [ProdukUmkmController::class, 'update']
    )->name('admin.umkm.produk.update');

    Route::delete(
        'umkm/{umkm}/produk/{produk}',
        [ProdukUmkmController::class, 'destroy']
    )->name('admin.umkm.produk.destroy');

});
});
// end checking priviledge
});
// end checking auth