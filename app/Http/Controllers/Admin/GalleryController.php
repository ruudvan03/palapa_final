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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'cols' => 'nullable|string',
            'rows' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        GalleryImage::create([
            'image_path' => $path,
            'alt' => $request->alt,
            'category' => $request->category,
            'cols' => $request->cols,
            'rows' => $request->rows,
            'order' => GalleryImage::max('order') + 1,
        ]);

        return back()->with('success', 'Imagen subida exitosamente a La Casona.');
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