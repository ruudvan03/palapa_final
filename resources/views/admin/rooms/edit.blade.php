@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900">Editar Habitación</h2>
        <p class="text-slate-500">Actualiza la información o la galería de fotos.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="p-10">
            <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ $room->name }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio por Noche</label>
                        <input type="number" name="price_per_night" value="{{ $room->price_per_night }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Capacidad</label>
                        <input type="number" name="capacity" value="{{ $room->capacity }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">{{ $room->description }}</textarea>
                    </div>

                    {{-- FOTOS ACTUALES --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Fotos Actuales</label>

                        @if($room->images->count() > 0)
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                @foreach($room->images as $image)
                                    @php $isCover = $room->image_path === $image->path; @endphp
                                    <div class="rounded-xl overflow-hidden border-2 {{ $isCover ? 'border-emerald-400' : 'border-slate-200' }}">

                                        {{-- Imagen --}}
                                        <div class="h-28 relative">
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                 class="w-full h-full object-cover">
                                            @if($isCover)
                                                <span class="absolute top-2 left-2 bg-emerald-500 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-lg">
                                                    Portada
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Botones --}}
                                        <div class="flex border-t border-slate-100">

                                            {{-- Hacer portada --}}
                                            @if(!$isCover)
                                                <form action="{{ route('rooms.images.cover', $image->id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full py-2 text-[11px] font-black uppercase text-emerald-600 hover:bg-emerald-50 transition">
                                                        ★ Portada
                                                    </button>
                                                </form>
                                            @else
                                                <div class="flex-1 py-2 text-center text-[11px] font-black uppercase text-emerald-400">
                                                    ★ Portada
                                                </div>
                                            @endif

                                            <div class="w-px bg-slate-100"></div>

                                            {{-- Eliminar --}}
                                            <form action="{{ route('rooms.images.destroy', $image->id) }}" method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('¿Eliminar esta imagen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full py-2 text-[11px] font-black uppercase text-red-500 hover:bg-red-50 transition">
                                                    Eliminar
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-400 mb-6">Esta habitación no tiene fotos todavía.</p>
                        @endif

                        {{-- SUBIR NUEVAS FOTOS --}}
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Añadir más fotos</label>
                        <div class="relative">
                            <input type="file" name="images[]" multiple accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-5 py-8 flex flex-col items-center justify-center hover:border-emerald-400 transition">
                                <p class="text-xs font-bold text-slate-400 uppercase">Seleccionar archivos</p>
                                <p class="text-xs text-slate-300 mt-1">JPG, PNG o WEBP · Máx. 5MB por foto</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="submit"
                            class="flex-1 bg-emerald-600 text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-lg hover:bg-emerald-700 transition">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('rooms.index') }}"
                       class="px-8 py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition text-center">
                        Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection