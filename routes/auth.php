<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/daftar', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('/daftar', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::get('/masuk', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/masuk', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

Route::get('/admin/masuk', [AuthenticatedSessionController::class, 'create_admin'])
                ->middleware('guest')
                ->name('admin.login');

Route::post('/admin/masuk', [AuthenticatedSessionController::class, 'store_admin'])
                ->middleware('guest');

Route::get('/lupa-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/lupa-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/ubah-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

Route::post('/ubah-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verifikasi-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verifikasi-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed'])
                ->name('verification.verify');

Route::get('/email/notifikasi-verifikasi', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth'])
                ->name('verification.send');

// Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
//                 ->middleware('auth')
//                 ->name('password.confirm');

// Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
//                 ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
