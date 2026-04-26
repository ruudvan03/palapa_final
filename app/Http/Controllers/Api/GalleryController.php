<?php

namespace App\Http\Controllers\Api; // <-- Fíjate que aquí dice Api

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;

class GalleryController extends Controller
{
    public function index()
    {
        // Traemos solo las fotos activas y ordenadas para la landing
        $images = GalleryImage::where('is_active', true)
                              ->orderBy('order', 'asc')
                              ->get();
        
        return response()->json($images);
    }
}