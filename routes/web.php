<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GalleryController;

use App\Models\Room;
use App\Models\GalleryImage;

// --- PÁGINA PÚBLICA (LANDING DE LA CASONA) ---
Route::get('/', function () {
    $rooms = Room::where('is_available', true)
                 ->with(['images' => fn($q) => $q->orderBy('sort_order', 'asc')])
                 ->orderBy('sort_order', 'asc')
                 ->get();

    $gallery = GalleryImage::orderBy('order', 'asc')->get();

    return view('welcome', compact('rooms', 'gallery'));
})->name('home');


// --- AUTENTICACIÓN ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- RUTAS PROTEGIDAS ---
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Habitaciones
    Route::resource('admin/rooms', RoomController::class)->names('rooms');
    Route::patch('/admin/rooms/{room}/toggle', [RoomController::class, 'toggleAvailability'])->name('rooms.toggle');
    Route::delete('/admin/rooms/images/{id}', [RoomController::class, 'deleteImage'])->name('rooms.images.destroy');
    Route::post('/admin/rooms/images/{id}/cover', [RoomController::class, 'setCover'])->name('rooms.images.cover');

    // Reservaciones
    Route::get('/admin/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/admin/reservations/check', [ReservationController::class, 'checkAvailability'])->name('reservations.check');
    Route::post('/admin/reservations/verify', [ReservationController::class, 'verify'])->name('reservations.verify');
    Route::post('/admin/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::patch('/admin/reservations/{reservation}/status/{status}', [ReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::get('/admin/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/admin/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/admin/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/admin/reservations/{reservation}/pdf', [ReservationController::class, 'downloadContract'])->name('reservations.pdf');

    // Eventos
    Route::get('/admin/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/admin/events/store', [EventController::class, 'store'])->name('events.store');
    Route::patch('/admin/events/{event}/status/{status}', [EventController::class, 'updateStatus'])->name('events.status');
    Route::delete('/admin/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/admin/events/{event}/pdf', [EventController::class, 'downloadContract'])->name('events.pdf');

    // Galería
    Route::get('/admin/gallery', [GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::post('/admin/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::delete('/admin/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

});