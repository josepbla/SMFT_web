<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PastoralController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\SiteImagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicContentController::class, 'home'])->name('home');
Route::get('/servicios', [PublicContentController::class, 'services'])->name('services');
Route::get('/contacto', [PublicContentController::class, 'contact'])->name('contact');
Route::get('/pastoral', [PastoralController::class, 'show'])->name('pastoral');

Route::get('/noticias', [PublicContentController::class, 'news'])->name('public.news');
Route::get('/noticias/{slug}', [PublicContentController::class, 'newsShow'])->name('public.news.show');
Route::get('/paginas', [PublicContentController::class, 'pages'])->name('public.pages');
Route::get('/paginas/{slug}', [PublicContentController::class, 'page'])->name('public.page');
Route::get('/comunicados', [PublicContentController::class, 'announcements'])->name('public.announcements');
Route::get('/documentos', [PublicContentController::class, 'documents'])->name('public.documents');
Route::get('/personal', [PublicContentController::class, 'staff'])->name('public.staff');
Route::get('/enlaces', [PublicContentController::class, 'links'])->name('public.links');
Route::get('/galerias', [PublicContentController::class, 'galleries'])->name('public.galleries');

Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware('admin')->prefix('admin')->group(function (): void {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/modules/{module}', [AdminDataController::class, 'index'])->name('admin.module.index');
    Route::get('/modules/{module}/create', [AdminDataController::class, 'create'])->name('admin.module.create');
    Route::post('/modules/{module}', [AdminDataController::class, 'store'])->name('admin.module.store');
    Route::get('/modules/{module}/{id}/edit', [AdminDataController::class, 'edit'])->name('admin.module.edit');
    Route::put('/modules/{module}/{id}', [AdminDataController::class, 'update'])->name('admin.module.update');
    Route::delete('/modules/{module}/{id}', [AdminDataController::class, 'destroy'])->name('admin.module.destroy');

    Route::get('/site-images', [SiteImagesController::class, 'edit'])->name('site-images.edit');
    Route::post('/site-images', [SiteImagesController::class, 'update'])->name('site-images.update');
    Route::get('/pastoral-images', [PastoralController::class, 'edit'])->name('pastoral.edit');
    Route::post('/pastoral-images', [PastoralController::class, 'update'])->name('pastoral.update');
});