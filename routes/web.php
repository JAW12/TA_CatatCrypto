<?php

use App\Events\AssetTransactionSuccess;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetTransactionController;
use App\Http\Controllers\AssetWalletController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Libraries\Binance;
use App\Models\AssetTransaction;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

Route::get('/storage', function () {
    Artisan::call('storage:link');
});


Route::get('', [HomeController::class, 'index'])->name('index');

// Route::get('/asset/init', [AssetController::class, 'init']);

// Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function() {
//     Route::get('masuk', [AuthController::class, 'signin'])->name('auth.signin');
//     Route::get('admin/masuk', [AuthController::class, 'admin_signin'])->name('auth.admin.signin');
//     Route::get('daftar', [AuthController::class, 'signup'])->name('auth.signup');
//     Route::get('konfirmasi_email', [AuthController::class, 'confirmmail'])->name('auth.confirmmail');
//     Route::get('kunci', [AuthController::class, 'lockscreen'])->name('auth.lockscreen');
//     Route::get('ubahpasword', [AuthController::class, 'recoverpw'])->name('auth.recoverpw');
//     Route::get('pengaturan_akun', [AuthController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
// });

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'profil'], function () {
        Route::get('/{id}', [UserController::class, 'show'])->name('user.profile');
        Route::patch('/{id}', [UserController::class, 'update'])->name('user.profile.edit');
        Route::get('/{id}/password', [UserController::class, 'password'])->name('user.password');
        Route::patch('/{id}/password', [UserController::class, 'password_update'])->name('user.password.edit');
        Route::get('/{id}/membership', [MembershipController::class, 'list'])->name('user.membership.list');
        Route::get('/{id}/transaksi', [TransactionController::class, 'list'])->name('user.transaction.list');
    });

    Route::group(['prefix' => 'dompet'], function () {
        Route::get('', [WalletController::class, 'index'])->name('user.wallet');
        Route::post('/tambah', [WalletController::class, 'store'])->name('user.wallet.add');
        Route::get('/demografi', [WalletController::class, 'demography'])->name('user.wallet.demography');

        Route::group(['prefix' => '{wallet}'], function () {
            Route::get('', [WalletController::class, 'show'])->name('user.wallet.detail')->withTrashed();
            Route::get('/load', [WalletController::class, 'load'])->name('user.wallet.detail.load')->withTrashed();
            Route::post('', [WalletController::class, 'update'])->name('user.wallet.update')->withTrashed();
            Route::delete('/nonaktifkan', [WalletController::class, 'destroy'])->name('user.wallet.delete');
            Route::post('/aktifkan', [WalletController::class, 'restore'])->name('user.wallet.restore')->withTrashed();

            Route::group(['prefix' => 'aset'], function () {
                Route::get('', [AssetController::class, 'index'])->name('user.wallet.asset.list');
                Route::post('/tambah', [AssetWalletController::class, 'add'])->name('user.wallet.asset.add');
                Route::get('/autocomplete', [AssetController::class, 'autocomplete'])->name('user.wallet.asset.list.autocomplete');
                Route::get('/load', [AssetController::class, 'load'])->name('user.wallet.asset.load');

                Route::group(['prefix' => '{asset}'], function () {
                    Route::get('', [AssetWalletController::class, 'show'])->name('user.wallet.asset.detail');
                    Route::delete('/hapus', [AssetWalletController::class, 'destroy'])->name('user.wallet.asset.delete');
                    Route::get('/load', [AssetWalletController::class, 'load'])->name('user.wallet.asset.detail.load');
                    Route::get('/info', [AssetWalletController::class, 'info'])->name('user.wallet.asset.detail.info');
                    Route::get('/checkSymbol/{symbol}', [AssetTransactionController::class, 'checkSymbol']);
                    Route::post('/ubah', [AssetTransactionController::class, 'add_update'])->name('user.wallet.asset.detail.add');
                    Route::delete('/hapus/{asset_transaction}', [AssetTransactionController::class, 'destroy'])->name('user.wallet.asset.detail.delete');
                });
            });
        });
    });

    Route::group(['prefix' => 'jurnal'], function () {
        Route::get('', [JournalController::class, 'index'])->name('user.journal');
        Route::post('/tambah', [JournalController::class, 'store'])->name('user.journal.add');
        Route::get('/metrik', [JournalController::class, 'metrics'])->name('user.journal.metrics');

        Route::group(['prefix' => '{journal}'], function () {
            Route::get('', [JournalController::class, 'show'])->name('user.journal.detail')->withTrashed();
            Route::post('', [JournalController::class, 'update'])->name('user.journal.update')->withTrashed();
            Route::delete('/nonaktifkan', [JournalController::class, 'destroy'])->name('user.journal.delete');
            Route::post('/aktifkan', [JournalController::class, 'restore'])->name('user.journal.restore')->withTrashed();
            Route::get('/tambah', [TradeController::class, 'add'])->name('user.journal.trade.add');
            Route::post('/tambah', [TradeController::class, 'store'])->name('user.journal.trade.store');
            Route::get('/autocomplete', [TradeController::class, 'autocomplete'])->name('user.journal.asset.autocomplete');

            Route::group(['prefix' => '/catatan/{trade}'], function () {
                Route::get('', [TradeController::class, 'show'])->name('user.journal.trade.edit')->withTrashed();
                Route::post('', [TradeController::class, 'update'])->name('user.journal.trade.update')->withTrashed();
                Route::post('/tambah', [TradeController::class, 'store_transaction'])->name('user.journal.trade.transaction.store');
                Route::delete('/hapus', [TradeController::class, 'destroy'])->name('user.journal.trade.delete');

                Route::get('/transaksi/{trade_transaction}', [TradeController::class, 'destroy_transaction'])->name('user.journal.trade.transaction.delete');
            });
        });
    });

    Route::group(['prefix' => 'membership'], function () {
        Route::get('/', [MembershipController::class, 'index'])->name('user.membership');
        Route::get('/{type}', [MembershipController::class, 'show'])->name('user.membership.payments');
        Route::post('/checkout', [TransactionController::class, 'checkout'])->name('user.membership.checkout');
        Route::post('/post_pembayaran', [TransactionController::class, 'payment_post'])->name('user.membership.payment_post');
    });

    // belum
    Route::group(['prefix' => 'pustaka'], function () {
        Route::get('', [LibraryController::class, 'index'])->name('user.library');
        Route::get('/tambah', [LibraryController::class, 'addPage'])->name('user.library.add');
        Route::get('/favorit', [LibraryController::class, 'favoritePage'])->name('user.library.favorite');
        Route::get('/laporan', [LibraryController::class, 'reports'])->name('user.library.reports');
    });
    Route::get('privacy-policy', [HomeController::class, 'privacypolicy'])->name('pages.privacy-policy');
    Route::get('terms-of-use', [HomeController::class, 'termsofuse'])->name('pages.term-of-use');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::group(['prefix' => 'membership'], function () {
        Route::get('/transaksi', [AdminController::class, 'transactions'])->name('admin.transactions');
        Route::get('/transaksi/{id_order}', [AdminController::class, 'transaction_detail'])->name('admin.transaction.detail');
        Route::get('/transaksi/{id_order}/terima', [AdminController::class, 'transaction_accept'])->name('admin.transaction.accept');
        Route::get('/transaksi/{id_order}/tolak', [AdminController::class, 'transaction_deny'])->name('admin.transaction.deny');
        Route::get('/laporan', [AdminController::class, 'transactions_report'])->name('admin.transactions.report');
    });


    Route::group(['prefix' => 'pengguna'], function () {
        Route::get('', [UserController::class, 'index'])->name('admin.users');
        Route::get('/demografi', [UserController::class, 'demography'])->name('admin.users.demography');
    });

    Route::group(['prefix' => 'pustaka'], function () {
        Route::get('', [LibraryController::class, 'index'])->name('admin.library');
        Route::get('/tambah', [LibraryController::class, 'addPage'])->name('admin.library.add');
        Route::get('/laporan', [LibraryController::class, 'reports'])->name('admin.library.reports');
    });
});
//UI Pages Routs
// Route::get('/uisheet', [HomeController::class, 'uisheet'])->name('uish0eet');

// // Dashboard Routes
// Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Route::group(['middleware' => 'auth'], function () {
//     // Permission Module
//     Route::get('/role-permission',[RolePermission::class, 'index'])->name('role.permission.list');
//     Route::resource('permission',PermissionController::class);
//     Route::resource('role', RoleController::class);

//     // Users Module
//     Route::resource('users', UserController::class);
// });

// //App Details Page => 'Dashboard'], function() {
// Route::group(['prefix' => 'menu-style'], function() {
//     //MenuStyle Page Routs
//     Route::get('horizontal', [HomeController::class, 'horizontal'])->name('menu-style.horizontal');
//     Route::get('dual-horizontal', [HomeController::class, 'dualhorizontal'])->name('menu-style.dualhorizontal');
//     Route::get('dual-compact', [HomeController::class, 'dualcompact'])->name('menu-style.dualcompact');
//     Route::get('boxed', [HomeController::class, 'boxed'])->name('menu-style.boxed');
//     Route::get('boxed-fancy', [HomeController::class, 'boxedfancy'])->name('menu-style.boxedfancy');
// });

// //App Details Page => 'special-pages'], function() {
// Route::group(['prefix' => 'special-pages'], function() {
//     //Example Page Routs
//     Route::get('billing', [HomeController::class, 'billing'])->name('special-pages.billing');
//     Route::get('calender', [HomeController::class, 'calender'])->name('special-pages.calender');
//     Route::get('kanban', [HomeController::class, 'kanban'])->name('special-pages.kanban');
//     Route::get('pricing', [HomeController::class, 'pricing'])->name('special-pages.pricing');
//     Route::get('rtl-support', [HomeController::class, 'rtlsupport'])->name('special-pages.rtlsupport');
//     Route::get('timeline', [HomeController::class, 'timeline'])->name('special-pages.timeline');
// });

// //Widget Routs
// Route::group(['prefix' => 'widget'], function() {
//     Route::get('widget-basic', [HomeController::class, 'widgetbasic'])->name('widget.widgetbasic');
//     Route::get('widget-chart', [HomeController::class, 'widgetchart'])->name('widget.widgetchart');
//     Route::get('widget-card', [HomeController::class, 'widgetcard'])->name('widget.widgetcard');
// });

// //Maps Routs
// Route::group(['prefix' => 'maps'], function() {
//     Route::get('google', [HomeController::class, 'google'])->name('maps.google');
//     Route::get('vector', [HomeController::class, 'vector'])->name('maps.vector');
// });

// //Auth pages Routs
// Route::group(['prefix' => 'auth'], function() {
//     Route::get('signin', [HomeController::class, 'signin'])->name('auth.signin');
//     Route::get('signup', [HomeController::class, 'signup'])->name('auth.signup');
//     Route::get('confirmmail', [HomeController::class, 'confirmmail'])->name('auth.confirmmail');
//     Route::get('lockscreen', [HomeController::class, 'lockscreen'])->name('auth.lockscreen');
//     Route::get('recoverpw', [HomeController::class, 'recoverpw'])->name('auth.recoverpw');
//     Route::get('userprivacysetting', [HomeController::class, 'userprivacysetting'])->name('auth.userprivacysetting');
// });

// //Error Page Route
// Route::group(['prefix' => 'errors'], function() {
//     Route::get('error404', [HomeController::class, 'error404'])->name('errors.error404');
//     Route::get('error500', [HomeController::class, 'error500'])->name('errors.error500');
//     Route::get('maintenance', [HomeController::class, 'maintenance'])->name('errors.maintenance');
// });


// //Forms Pages Routs
// Route::group(['prefix' => 'forms'], function() {
//     Route::get('element', [HomeController::class, 'element'])->name('forms.element');
//     Route::get('wizard', [HomeController::class, 'wizard'])->name('forms.wizard');
//     Route::get('validation', [HomeController::class, 'validation'])->name('forms.validation');
// });


// //Table Page Routs
// Route::group(['prefix' => 'table'], function() {
//     Route::get('bootstraptable', [HomeController::class, 'bootstraptable'])->name('table.bootstraptable');
//     Route::get('datatable', [HomeController::class, 'datatable'])->name('table.datatable');
// });

// //Icons Page Routs
// Route::group(['prefix' => 'icons'], function() {
//     Route::get('solid', [HomeController::class, 'solid'])->name('icons.solid');
//     Route::get('outline', [HomeController::class, 'outline'])->name('icons.outline');
//     Route::get('dualtone', [HomeController::class, 'dualtone'])->name('icons.dualtone');
//     Route::get('colored', [HomeController::class, 'colored'])->name('icons.colored');
// });
// //Extra Page Routs
