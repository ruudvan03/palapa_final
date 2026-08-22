<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('room')->latest()->get();

        $events = $reservations->map(function($res) {
            $color = match($res->status) {
                'confirmed' => '#10b981',
                'pending'   => '#f59e0b',
                'cancelled' => '#ef4444',
                default     => '#64748b',
            };

            return [
                'id'    => $res->id,
                'title' => $res->room->name . ' - ' . $res->customer_name,
                'start' => $res->check_in,
                'end'   => Carbon::parse($res->check_out)->addDay()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'customer' => $res->customer_name,
                    'phone'    => $res->customer_phone,
                    'status'   => $res->status
                ]
            ];
        });

        return view('admin.reservations.index', compact('reservations', 'events'));
    }

    public function updateStatus(Reservation $reservation, $status)
    {
        if (in_array($status, ['confirmed', 'pending', 'cancelled'])) {
            $reservation->update(['status' => $status]);

            $estadoTexto = match($status) {
                'confirmed' => 'CONFIRMADA',
                'pending'   => 'PENDIENTE',
                'cancelled' => 'CANCELADA',
            };

            return back()->with('success', "Folio {$reservation->folio} actualizado a: {$estadoTexto}");
        }

        return back()->with('error', 'El estado solicitado no es válido.');
    }

    public function downloadContract(Reservation $reservation)
    {
        $reservation->load('room');

        $logoPath = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . $logoData;
        }

        $data = [
            'logo'           => $logoBase64,
            'reservation'    => $reservation,
            'check_in'       => Carbon::parse($reservation->check_in),
            'check_out'      => Carbon::parse($reservation->check_out),
            'establishment'  => 'Palapa "La Casona"',
            'representative' => 'María Magdalena Cruz García',
            'city'           => 'San Pedro Pochutla, Oaxaca'
        ];

        $pdf = \Pdf::loadView('admin.reservations.contract', $data)
                    ->setPaper('letter', 'portrait');

        return $pdf->download('Contrato_' . $reservation->folio . '.pdf');
    }

    public function checkAvailability()
    {
        return view('admin.reservations.check');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'check_in'  => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests'    => 'required|integer|min:1',
        ]);

        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;
        $guests   = $request->guests;

        $availableRooms = Room::with('images')
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where('status', '!=', 'cancelled')
                      ->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })
            ->get();

        return view('admin.reservations.check', compact('availableRooms', 'checkIn', 'checkOut', 'guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'check_in'       => 'required|date',
            'check_out'      => 'required|date|after:check_in',
        ]);

        // Verificar disponibilidad antes de crear
        $conflicto = Reservation::overlapping(
            $request->room_id,
            $request->check_in,
            $request->check_out
        )->first();

        if ($conflicto) {
            return back()
                ->withErrors(['check_in' => 'La habitación ya tiene una reserva en esas fechas.'])
                ->withInput();
        }

        $room     = Room::findOrFail($request->room_id);
        $checkIn  = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $dias     = $checkIn->diffInDays($checkOut);
        $total    = $room->price_per_night * ($dias ?: 1);

        Reservation::create([
            'room_id'        => $request->room_id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'check_in'       => $request->check_in,
            'check_out'      => $request->check_out,
            'payment_method' => $request->payment_method,
            'total_price'    => $total,
            'status'         => 'pending',
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva registrada. Estado: PENDIENTE (Verificar pago para confirmar).');
    }

    public function edit(Reservation $reservation)
    {
        $rooms = Room::all();
        return view('admin.reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'status'         => 'required|in:pending,confirmed,cancelled',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Datos actualizados.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reserva eliminada.');
    }
}