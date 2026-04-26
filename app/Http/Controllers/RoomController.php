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
        // Ordenamos las habitaciones por su campo sort_order en el panel de admin también
        $rooms = Room::with('images')->orderBy('sort_order', 'asc')->get();
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
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer', // <-- Validamos el orden
            'images' => 'required|array', 
            // Límite de 5MB para evitar errores silenciosos
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', 
        ]);

        // 1. Crear la habitación (inicialmente sin imagen de portada)
        $room = Room::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'capacity_label' => $request->capacity . ' Personas', // Etiqueta automática
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0, // <-- Guardamos el orden
            'is_available' => true,
        ]);

        // 2. Procesar y guardar cada imagen en la galería
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                // Guardar archivo físico
                $path = $file->store('rooms', 'public');
                
                // Crear registro en tabla room_images
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path,
                    'sort_order' => $key // Guardamos un orden inicial para las fotos
                ]);

                // --- MAGIA: La primera imagen se convierte en la PORTADA ---
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
        // Traemos las imágenes ordenadas
        $room->load(['images' => function($query) {
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
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'delete_images' => 'nullable|array', // <-- Validamos el arreglo de fotos a borrar
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // 1. Actualizar datos principales de la habitación
        $room->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'capacity_label' => $request->capacity . ' Personas',
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0, // <-- Actualizamos el orden
        ]);

        // 2. Lógica para ELIMINAR las imágenes que marcaste con el botecito de basura
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = RoomImage::find($imageId);
                if ($image) {
                    // Borrar el archivo físico del servidor
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }
                    // Borrar de la base de datos
                    $image->delete();
                }
            }
        }

        // 3. Lógica para SUBIR NUEVAS imágenes
        if ($request->hasFile('images')) {
            $maxOrder = $room->images()->max('sort_order') ?? 0;
            
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('rooms', 'public');
                
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path,
                    'sort_order' => $maxOrder + $key + 1 // Las ponemos al final
                ]);

                // Si la habitación se quedó sin portada (porque la borraste), usamos esta nueva
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
        // Borramos todas las imágenes de la galería del disco
        foreach ($room->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        // También borramos la imagen de portada si existe y es diferente
        if ($room->image_path && Storage::disk('public')->exists($room->image_path)) {
            Storage::disk('public')->delete($room->image_path);
        }
        
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Habitación y sus fotos eliminadas.');
    }

    /**
     * Eliminar una sola imagen de la galería (Por si tienes un botón de borrado directo).
     */
    public function deleteImage($id)
    {
        $image = RoomImage::findOrFail($id);
        
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        
        $image->delete();

        return back()->with('success', 'Imagen eliminada de la galería.');
    }

    /**
     * Busca habitaciones disponibles para el frontend (React/Astro)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guests = $request->guests;

        // Buscamos habitaciones: 1. Activas, 2. Con capacidad, 3. SIN reservas cruzadas
        $availableRooms = \App\Models\Room::where('is_available', true)
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('reservations', function ($query) use ($checkIn, $checkOut) {
                // Una reserva choca si su inicio es antes del nuevo fin y su fin es después del nuevo inicio
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn)
                      ->where('status', '!=', 'cancelled'); // Ignoramos las canceladas
            })
            ->orderBy('sort_order', 'asc') // Las mandamos ordenadas al React también
            ->get();

        return response()->json($availableRooms);
    }
}