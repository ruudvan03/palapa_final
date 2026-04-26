@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900">Editar Habitación</h2>
        <p class="text-slate-500">Actualiza la información, el orden o la galería de fotos.</p>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="p-10">
            <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ $room->name }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Precio por Noche</label>
                        <input type="number" name="price_per_night" value="{{ $room->price_per_night }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Capacidad</label>
                        <input type="number" name="capacity" value="{{ $room->capacity }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Posición en Landing (Ej. 1, 2, 3...)</label>
                        <input type="number" name="sort_order" value="{{ $room->sort_order }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">
                        <p class="text-xs text-slate-400 mt-2">Define en qué lugar aparecerá esta habitación en la página principal.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Descripción</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition">{{ $room->description }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Fotos Actuales <span class="text-emerald-500 normal-case tracking-normal font-normal">(Haz clic en una para eliminarla)</span></label>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            @foreach($room->images as $image)
                                <div class="relative h-28 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                    
                                    <label class="absolute inset-0 cursor-pointer group/label">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="peer hidden">
                                        
                                        <div class="absolute inset-0 bg-red-900/40 opacity-0 group-hover/label:opacity-100 peer-checked:opacity-0 transition-all flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </div>
                                        
                                        <div class="absolute inset-0 bg-red-500/90 hidden peer-checked:flex items-center justify-center transition-all">
                                            <span class="text-white font-bold text-[10px] uppercase tracking-widest flex flex-col items-center gap-1">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                A eliminar
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Añadir nuevas fotos</label>
                        <div class="relative group">
                            <input type="file" name="images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl px-5 py-6 flex flex-col items-center justify-center group-hover:border-emerald-400 transition">
                                <p class="text-xs font-bold text-slate-400 uppercase">Seleccionar archivos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-black uppercase tracking-widest py-4 rounded-2xl shadow-lg hover:bg-emerald-700 transition">
                        Actualizar Habitación
                    </button>
                    <a href="{{ route('rooms.index') }}" class="px-8 py-4 bg-slate-100 text-slate-500 font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition text-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection