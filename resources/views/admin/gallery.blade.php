@extends('layouts.admin') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Galería de La Casona</h1>
    </div>

    {{-- Alertas de Éxito --}}
    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- FORMULARIO PARA SUBIR FOTO --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 h-fit">
            <h2 class="text-xl font-bold mb-4">Subir Nueva Foto</h2>
            
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Imagen</label>
                    <input type="file" name="image" required class="w-full border border-slate-300 rounded-lg p-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Título / Texto Alternativo</label>
                    <input type="text" name="alt" placeholder="Ej. Boda en la palapa" required class="w-full border border-slate-300 rounded-lg p-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                    <input type="text" name="category" placeholder="Ej. Eventos, Alberca, Jardín" class="w-full border border-slate-300 rounded-lg p-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tamaño en el Mosaico</label>
                    <select name="cols" class="w-full border border-slate-300 rounded-lg p-2 mb-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Ancho Normal (1 Cuadro)</option>
                        <option value="md:col-span-2">Doble Ancho (2 Cuadros horizontales)</option>
                    </select>
                    <select name="rows" class="w-full border border-slate-300 rounded-lg p-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Alto Normal (1 Cuadro)</option>
                        <option value="md:row-span-2">Doble Alto (2 Cuadros verticales)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-emerald-700 transition-colors">
                    Guardar Imagen
                </button>
            </form>
        </div>

        {{-- TABLA / LISTA DE IMÁGENES ACTUALES --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <th class="p-4 border-b">Preview</th>
                        <th class="p-4 border-b">Detalles</th>
                        <th class="p-4 border-b">Tamaño</th>
                        <th class="p-4 border-b text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($images as $img)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 w-24">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->alt }}" class="w-16 h-16 object-cover rounded-lg">
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $img->alt }}</p>
                                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full">{{ $img->category ?? 'Sin categoría' }}</span>
                            </td>
                            <td class="p-4 text-sm text-slate-600">
                                {{ $img->cols ? 'Ancho ' . str_replace('md:col-span-', '', $img->cols) : 'Ancho 1' }} x 
                                {{ $img->rows ? 'Alto ' . str_replace('md:row-span-', '', $img->rows) : 'Alto 1' }}
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.gallery.destroy', $img->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta imagen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">No hay imágenes en la galería aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection