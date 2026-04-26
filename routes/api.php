<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\RoomReserved;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\GalleryImage;
use Carbon\Carbon;
use App\Http\Controllers\Api\GalleryController;

/**
 * RUTAS DE API - PALAPA LA CASONA
 */

// Obtener todas las habitaciones
Route::get('/rooms', function () {
    return response()->json(Room::where('is_available', true)->get());
});

// Checar disponibilidad de fechas (CORREGIDO)
Route::post('/check-availability', function (Request $request) {
    $request->validate([
        'check_in'  => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'guests'    => 'nullable|integer|min:1' 
    ]);

    $start = $request->check_in;
    $end = $request->check_out;
    $guests = $request->guests;

    // LÓGICA DE FECHAS CRUZADAS CORREGIDA
    $availableRooms = Room::where('is_available', true)
        ->whereDoesntHave('reservations', function ($query) use ($start, $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->where('check_in', '<', $end)
                  ->where('check_out', '>', $start);
            })->where('status', '!=', 'cancelled'); // Opcional: Si tienes estados, ignora las canceladas
        })
        ->when($guests, function ($query) use ($guests) {
            return $query->where('capacity', '>=', $guests);
        })
        ->get();

    return response()->json($availableRooms);
});

// Crear una nueva reservación (CORREGIDO EL OVERLAPPING)
Route::post('/reserve-room', function (Request $request) {
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email',
        'phone'          => 'required|string',
        'room_id'        => 'required|exists:rooms,id',
        'check_in'       => 'required|date|after_or_equal:today',
        'check_out'      => 'required|date|after:check_in',
        'payment_method' => 'required|in:transfer,cash',
    ]);

    $start = $request->check_in;
    $end = $request->check_out;

    // Validación de disponibilidad real CORREGIDA
    $isOccupied = Reservation::where('room_id', $request->room_id)
        ->where('check_in', '<', $end)
        ->where('check_out', '>', $start)
        ->where('status', '!=', 'cancelled')
        ->exists();

    if ($isOccupied) {
        return response()->json(['success' => false, 'message' => 'Fechas no disponibles para esta habitación.'], 422);
    }

    try {
        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $days = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * ($days ?: 1);

        // Crear registro en la base de datos
        $reservation = Reservation::create([
            'room_id'        => $request->room_id,
            'customer_name'  => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'total_price'    => $totalPrice,
            'payment_method' => $request->payment_method,
            'status'         => 'pending',
        ]);

        // ENVÍO DE CORREO
        try {
            Mail::to($reservation->customer_email)->send(new RoomReserved($reservation));
        } catch (\Exception $e) {
            \Log::error("Error Mail: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'folio'   => $reservation->folio,
            'email'   => $reservation->customer_email,
            'total'   => $totalPrice,
            'payment' => $request->payment_method
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
});

// Obtener las fotos de la galería dinámicamente
Route::get('/gallery', [GalleryController::class, 'index']);