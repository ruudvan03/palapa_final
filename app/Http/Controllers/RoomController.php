<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Muestra el listado de habitaciones con su galería cargada.
     */
    public function index()
    {
        $rooms = Room::with(['images' => fn($q) => $q->orderBy('sort_order', 'asc')])->orderBy('sort_order', 'asc')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva habitación.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Almacena una nueva habitación y su galería de imágenes.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity'        => 'required|integer',
            'description'     => 'nullable|string',
            'sort_order'      => 'nullable|integer',
            'images'          => 'required|array',
            'images.*'        => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $room = Room::create([
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity'        => $request->capacity,
            'capacity_label'  => $request->capacity . ' Personas',
            'description'     => $request->description,
            'sort_order'      => $request->sort_order ?? 0,
            'is_available'    => true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('rooms', 'public');

                RoomImage::create([
                    'room_id'    => $room->id,
                    'path'       => $path,
                    'sort_order' => $key,
                ]);

                if ($key === 0) {
                    $room->update(['image_path' => $path]);
                }
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación creada con portada y galería.');
    }

    /**
     * Muestra el formulario para editar.
     */
    public function edit(Room $room)
    {
        $room->load(['images' => function ($query) {
            $query->orderBy('sort_order', 'asc');
        }]);
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Actualiza los datos, maneja el nuevo orden, elimina fotos seleccionadas y añade nuevas.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity'        => 'required|integer',
            'description'     => 'nullable|string',
            'sort_order'      => 'nullable|integer',
            'delete_images'   => 'nullable|array',
            'images.*'        => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $updateData = [
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity'        => $request->capacity,
            'capacity_label'  => $request->capacity . ' Personas',
            'description'     => $request->description,
            'sort_order'      => $request->sort_order ?? 0,
        ];

        // FIX: limpiar portada huérfana si se solicitó desde el blade
        if ($request->boolean('clear_image_path')) {
            if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
                Storage::disk('public')->delete($room->image_path);
            }
            $updateData['image_path'] = null;
        }

        $room->update($updateData);

        // Eliminar imágenes marcadas con checkbox/botón en el blade
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = RoomImage::find($imageId);
                if ($image) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    // FIX: si era la portada, limpiarla también del cuarto
                    if ($room->image_path === $image->path) {
                        $room->update(['image_path' => null]);
                    }
                    $image->delete();
                }
            }
        }

        // Subir nuevas imágenes
        if ($request->hasFile('images')) {
            $maxOrder = $room->images()->max('sort_order') ?? 0;

            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('rooms', 'public');

                RoomImage::create([
                    'room_id'    => $room->id,
                    'path'       => $path,
                    'sort_order' => $maxOrder + $key + 1,
                ]);

                // Si la habitación se quedó sin portada, usamos la primera nueva
                $room->refresh();
                if (!$room->image_path && $key === 0) {
                    $room->update(['image_path' => $path]);
                }
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Elimina la habitación y todos sus archivos físicos.
     */
    public function destroy(Room $room)
    {
        foreach ($room->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }

        if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
            Storage::disk('public')->delete($room->image_path);
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Habitación y sus fotos eliminadas.');
    }

    /**
     * FIX: Eliminar una sola imagen de la galería — ahora también limpia image_path si era portada.
     */
    public function deleteImage($id)
    {
        $image = RoomImage::findOrFail($id);
        $room  = Room::findOrFail($image->room_id);

        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        // Si esta imagen era la portada, asignar la siguiente disponible o dejar vacía
        if ($room->image_path === $image->path) {
            $nextImage = RoomImage::where('room_id', $room->id)
                                  ->where('id', '!=', $image->id)
                                  ->orderBy('sort_order', 'asc')
                                  ->first();
            $room->update(['image_path' => $nextImage?->path]);
        }

        $image->delete();

        return back()->with('success', 'Imagen eliminada de la galería.');
    }

    /**
     * Alterna la disponibilidad de la habitación (disponible ↔ no disponible).
     */
    public function toggleAvailability(Room $room)
    {
        $room->update(['is_available' => !$room->is_available]);

        $msg = $room->is_available ? 'Habitación marcada como disponible.' : 'Habitación marcada como no disponible.';
        return back()->with('success', $msg);
    }

    /**
     * Establece una imagen como portada de la habitación.
     */
    public function setCover($id)
    {
        $image = RoomImage::findOrFail($id);
        $room  = Room::findOrFail($image->room_id);

        // Intercambiar sort_order con la imagen que actualmente ocupa el primer lugar
        // para que la portada elegida aparezca primero en los carruseles
        $currentFirst = RoomImage::where('room_id', $room->id)
                                 ->orderBy('sort_order', 'asc')
                                 ->first();

        if ($currentFirst && $currentFirst->id !== $image->id) {
            $oldOrder = $image->sort_order;
            $image->update(['sort_order' => $currentFirst->sort_order]);
            $currentFirst->update(['sort_order' => $oldOrder]);
        }

        // Actualizar la portada en la tabla rooms
        $room->update(['image_path' => $image->path]);

        return back()->with('success', 'Portada actualizada.');
    }

    /**
     * Busca habitaciones disponibles para el frontend.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in'  => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests'    => 'required|integer|min:1',
        ]);

        $checkIn  = $request->check_in;
        $checkOut = $request->check_out;
        $guests   = $request->guests;

        $availableRooms = Room::where('is_available', true)
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn)
                      ->where('status', '!=', 'cancelled');
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json($availableRooms);
    }
}