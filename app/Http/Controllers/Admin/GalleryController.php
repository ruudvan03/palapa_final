<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('order', 'asc')->get();
        return view('admin.gallery', compact('images')); 
    }

    public function store(Request $request)
{
    // 1. Validamos usando los nombres exactos de tu formulario ('image', 'alt', etc.)
    $request->validate([
        'image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        'alt'      => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'cols'     => 'nullable|string',
        'rows'     => 'nullable|string',
    ]);

    // 2. Buscamos el archivo con el nombre 'image'
    if ($request->hasFile('image')) {
        
        // Guardamos el archivo en storage/app/public/gallery
        $path = $request->file('image')->store('gallery', 'public');

        // 3. Registramos todos los datos en la base de datos
        GalleryImage::create([
            'image_path' => $path, 
            'alt'        => $request->alt, 
            'category'   => $request->category,
            'cols'       => $request->cols,
            'rows'       => $request->rows,
        ]);

        return back()->with('success', '¡Foto guardada exitosamente en el mosaico!');
    }

    return back()->with('error', 'No se detectó ninguna imagen. Intenta de nuevo.');
}

    public function destroy(GalleryImage $gallery)
    {
        if(Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        return back()->with('success', 'Imagen eliminada de la galería.');
    }
}