<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Palapa La Casona | Experiencia Exclusiva en la Costa de Oaxaca</title>
    <meta name="description" content="Hospedaje premium y habitaciones con alma en el corazón de la costa oaxaqueña. Llano Grande, Tonameca.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800|playfair-display:700,900italic" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }

        /* Nav scroll */
        #navbar { transition: all .35s ease; }
        #navbar.scrolled { height: 5rem !important; box-shadow: 0 1px 24px rgba(0,0,0,.07); }
        #navbar.scrolled img { height: 3.5rem !important; }

        /* Swiper */
        .swiper-pagination-bullet-active { background: #10b981 !important; }
        .swiper-button-next, .swiper-button-prev {
            color: white !important;
            background: rgba(0,0,0,.3);
            width: 40px !important; height: 40px !important;
            border-radius: 50%;
            backdrop-filter: blur(8px);
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 14px !important; font-weight: 800; }

        /* Mobile menu */
        #mobile-menu { transition: opacity .25s, transform .25s; }
        #mobile-menu.hidden { opacity:0; pointer-events:none; transform:translateY(-8px); display:none; }
        #mobile-menu.open  { opacity:1; pointer-events:auto; transform:translateY(0);  display:block; }

        /* Scroll indicator */
        @keyframes bounce-slow { 0%,100%{transform:translateY(0)} 50%{transform:translateY(8px)} }
        .bounce-slow { animation: bounce-slow 2s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased bg-[#FDFDFC] text-slate-900 overflow-x-hidden">

    {{-- ===================== NAV ===================== --}}
    <nav id="navbar" class="fixed w-full z-[100] bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-32 flex items-center justify-between">

            <a href="#" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Palapa La Casona"
                     class="h-24 w-auto object-contain transition-all duration-300 hover:scale-105">
            </a>

            {{-- Links desktop --}}
            <div class="hidden lg:flex items-center gap-10 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">
                <a href="#inicio"       class="hover:text-emerald-600 transition-colors">Inicio</a>
                <a href="#amenidades"   class="hover:text-emerald-600 transition-colors">Amenidades</a>
                <a href="#habitaciones" class="hover:text-emerald-600 transition-colors">Habitaciones</a>
                <a href="#galeria"      class="hover:text-emerald-600 transition-colors">Galería</a>
                <a href="#ubicacion"    class="hover:text-emerald-600 transition-colors">Ubicación</a>
                <a href="#faq"          class="hover:text-emerald-600 transition-colors">FAQ</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex bg-slate-900 text-white px-6 py-3 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg">
                    Admin
                </a>
                {{-- Hamburguesa --}}
                <button id="menu-btn" class="lg:hidden p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition" aria-label="Menú">
                    <svg id="icon-open"  class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 px-6 py-4 space-y-1">
            <a href="#inicio"       class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">Inicio</a>
            <a href="#amenidades"   class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">Amenidades</a>
            <a href="#habitaciones" class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">Habitaciones</a>
            <a href="#galeria"      class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">Galería</a>
            <a href="#ubicacion"    class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">Ubicación</a>
            <a href="#faq"          class="block py-3 text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-emerald-600 border-b border-slate-50">FAQ</a>
            <a href="{{ route('login') }}" class="block mt-3 py-3 text-center text-sm font-black uppercase tracking-widest text-white bg-slate-900 rounded-2xl hover:bg-emerald-600 transition">Admin</a>
        </div>
    </nav>

    <main>
        {{-- ===================== HERO ===================== --}}
        <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-slate-900">
            <video autoplay muted loop playsinline
                   class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover opacity-50">
                <source src="/hero-bg.mp4" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-transparent to-[#FDFDFC] z-10"></div>

            <div class="relative z-20 w-full max-w-7xl mx-auto px-6 pt-36 pb-20
                        flex flex-col lg:grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                {{-- Texto --}}
                <div class="text-white space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Disponible 2026</span>
                    </div>
                    <h1 class="text-6xl sm:text-7xl md:text-8xl font-black uppercase tracking-tighter leading-[0.85]">
                        Escape <br><span class="font-serif italic text-emerald-400 lowercase font-normal">tropical</span>.
                    </h1>
                    <p class="text-lg sm:text-xl text-slate-200 font-light max-w-md mx-auto lg:mx-0">
                        Hospedaje premium y habitaciones con alma en el corazón de la costa de Oaxaca.
                    </p>
                    <div class="hidden lg:flex items-center gap-6 text-[10px] font-black uppercase tracking-widest text-white/40">
                        <span class="flex items-center gap-2"><span class="w-5 h-px bg-white/20"></span>Scroll</span>
                        <svg class="w-4 h-4 bounce-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Formulario --}}
                <div id="react-booking-form" class="w-full max-w-md mx-auto lg:ml-auto bg-white rounded-[2rem] shadow-2xl overflow-hidden"></div>
            </div>
        </section>

        {{-- ===================== AMENIDADES ===================== --}}
        <section id="amenidades" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 mb-4">
                        <span class="w-8 h-[2px] bg-emerald-500"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Lo que incluye</span>
                        <span class="w-8 h-[2px] bg-emerald-500"></span>
                    </div>
                    <h2 class="text-5xl font-black uppercase tracking-tighter">Todo lo que <span class="font-serif italic font-normal lowercase text-emerald-600">necesitas</span></h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @php
                    $amenidades = [
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>', 'label' => 'Alberca'],
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819"/>', 'label' => 'Palapa'],
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>', 'label' => 'Jardín'],
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/>', 'label' => 'WiFi'],
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 4a2 2 0 0 1 2 2v2H7V6a2 2 0 0 1 2-2Zm4 0a2 2 0 0 1 2 2v2h-2V6a2 2 0 0 1 2-2Zm4 0a2 2 0 0 1 2 2v2h-2V6a2 2 0 0 1 2-2ZM4 10h16v2a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2Zm3 4v5m4-5v5m4-5v5"/>', 'label' => 'Cocina'],
                        ['icono' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>', 'label' => 'Estacionamiento'],
                    ];
                    @endphp

                    @foreach($amenidades as $a)
                    <div class="group flex flex-col items-center gap-3 p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100 hover:bg-emerald-50 hover:border-emerald-100 transition-all duration-300 cursor-default">
                        <div class="p-3 bg-white rounded-2xl shadow-sm group-hover:bg-emerald-600 group-hover:text-white transition-all text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">{!! $a['icono'] !!}</svg>
                        </div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-600 group-hover:text-emerald-700 transition-colors text-center">{{ $a['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== HABITACIONES ===================== --}}
        <section id="habitaciones" class="py-32 bg-[#FDFDFC]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-4">
                            <span class="w-8 h-[2px] bg-emerald-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Hospedaje</span>
                        </div>
                        <h2 class="text-5xl sm:text-6xl font-black uppercase tracking-tighter">Nuestros <span class="text-emerald-600">Espacios</span></h2>
                    </div>
                    <a href="#inicio" class="shrink-0 text-[10px] font-black uppercase tracking-widest text-slate-500 border border-slate-200 px-6 py-3 rounded-full hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                        Reservar ahora →
                    </a>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($rooms as $room)
                    <div class="group bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col">

                        {{-- Imagen --}}
                        <div class="swiper room-swiper h-[320px] sm:h-[360px] w-full relative">
                            <div class="swiper-wrapper">
                                @if($room->images && $room->images->count() > 0)
                                    @foreach($room->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $room->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    @endforeach
                                @elseif($room->image_path)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="swiper-slide flex items-center justify-center bg-slate-100 text-slate-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="swiper-pagination"></div>

                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
                                <span class="bg-white/95 backdrop-blur px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg text-emerald-700">
                                    ${{ number_format($room->price_per_night, 0) }} / noche
                                </span>
                                @if(!$room->is_available)
                                <span class="bg-red-500 text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow">
                                    No disponible
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-8 flex-grow flex flex-col">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <h3 class="text-xl font-black uppercase tracking-tight text-slate-900 leading-tight">{{ $room->name }}</h3>
                                <span class="shrink-0 flex items-center gap-1 text-[10px] font-black uppercase tracking-tight text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-full">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                    {{ $room->capacity }}p
                                </span>
                            </div>
                            <p class="text-slate-400 text-sm leading-relaxed line-clamp-2 flex-grow mb-6">
                                {{ $room->description ?? 'Experiencia única en la costa oaxaqueña.' }}
                            </p>
                            <a href="{{ $room->is_available ? '#inicio' : '#' }}"
                               class="w-full py-3 rounded-2xl text-xs font-black uppercase tracking-widest text-center transition-all
                                      {{ $room->is_available
                                         ? 'bg-slate-900 text-white hover:bg-emerald-600 shadow-sm'
                                         : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                                {{ $room->is_available ? 'Reservar esta habitación →' : 'No disponible' }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== GALERÍA ===================== --}}
        <section id="galeria" class="py-32 bg-slate-50 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 mb-12">
                <div class="inline-flex items-center gap-2 mb-4">
                    <span class="w-8 h-[2px] bg-emerald-500"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Conoce el espacio</span>
                </div>
                <h2 class="text-5xl font-black uppercase tracking-tighter">Nuestra <span class="font-serif italic font-normal lowercase text-emerald-600">Galería</span></h2>
                <p class="text-slate-500 mt-3 text-lg max-w-xl">Descubre la esencia de La Casona a través de nuestra lente.</p>
            </div>

            <div class="max-w-7xl mx-auto px-6">
                <div class="group flex max-md:flex-col justify-center gap-2">
                    @foreach($gallery->take(5) as $img)
                    <article class="group/article relative w-full rounded-2xl overflow-hidden
                                    md:group-hover:[&:not(:hover)]:w-[20%]
                                    md:group-focus-within:[&:not(:focus-within):not(:hover)]:w-[20%]
                                    transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.15)]
                                    before:absolute before:inset-x-0 before:bottom-0 before:h-1/3
                                    before:bg-gradient-to-t before:from-black/50 before:transition-opacity
                                    md:before:opacity-0 md:hover:before:opacity-100 focus-within:before:opacity-100
                                    after:opacity-0
                                    md:group-hover:[&:not(:hover)]:after:opacity-100
                                    md:group-focus-within:[&:not(:focus-within):not(:hover)]:after:opacity-100
                                    after:absolute after:inset-0 after:bg-white/30 after:backdrop-blur after:transition-all
                                    focus-within:ring focus-within:ring-emerald-300">
                        <a class="absolute inset-0 text-white z-10" href="#galeria">
                            <span class="absolute inset-x-0 bottom-0 text-lg font-medium p-6
                                         md:px-10 md:py-8 md:whitespace-nowrap md:truncate
                                         md:opacity-0 group-hover/article:opacity-100 group-focus-within/article:opacity-100
                                         md:translate-y-2 group-hover/article:translate-y-0 group-focus-within/article:translate-y-0
                                         transition duration-200 ease-[cubic-bezier(.5,.85,.25,1.8)]
                                         group-hover/article:delay-300 group-focus-within/article:delay-300">
                                {{ $img->alt ?? 'Rincón de La Casona' }}
                            </span>
                        </a>
                        <img class="object-cover h-64 md:h-[480px] w-full"
                             src="{{ asset('storage/' . $img->image_path) }}"
                             alt="{{ $img->alt ?? 'Foto Galería' }}"
                             loading="lazy">
                    </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== UBICACIÓN ===================== --}}
        <section id="ubicacion" class="py-32 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-emerald-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Cómo llegar</span>
                        </div>
                        <h2 class="text-5xl font-black uppercase tracking-tighter mb-8">Nuestra <span class="font-serif italic font-normal lowercase text-emerald-600">Ubicación</span></h2>
                        <p class="text-slate-500 text-lg leading-relaxed mb-12 max-w-md">
                            Estamos en Llano Grande, Tonameca — un oasis privado a pocos minutos de las mejores playas de la costa oaxaqueña.
                        </p>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4 group">
                                <div class="bg-slate-50 p-4 rounded-2xl text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 transition-all shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0116 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-1">Dirección</h4>
                                    <p class="text-slate-500 text-sm">Domicilio Conocido, Llano Grande<br>Santa María Tonameca, Oaxaca, C.P. 70946</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 group">
                                <div class="bg-slate-50 p-4 rounded-2xl text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 transition-all shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 015.13 12.5a19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-1">Contacto</h4>
                                    <p class="text-slate-500 text-sm">+52 (958) 107 2468<br>palapalacasona01@gmail.com</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 group">
                                <div class="bg-slate-50 p-4 rounded-2xl text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 transition-all shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-1">Check-in / Check-out</h4>
                                    <p class="text-slate-500 text-sm">Entrada: 2:00 pm · Salida: 12:00 pm</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full h-[420px] lg:h-[500px] rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-shadow duration-500">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15360.643493236696!2d-96.54631655!3d15.742626650000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d7a645836183%3A0xaa2fd69b1b5b584d!2sPalapa%20La%20Casona!5e0!3m2!1ses-419!2smx!4v1777178942899!5m2!1ses-419!2smx"
                            width="100%" height="100%" style="border:0;" allowfullscreen loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale hover:grayscale-0 transition-all duration-1000">
                        </iframe>
                        <div class="absolute bottom-6 left-6 z-10 pointer-events-none">
                            <span class="bg-slate-900/90 backdrop-blur text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Google Maps
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== FAQ ===================== --}}
        <section id="faq" class="py-32 bg-slate-50">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 mb-4">
                        <span class="w-8 h-[2px] bg-emerald-500"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Resolvemos tus dudas</span>
                        <span class="w-8 h-[2px] bg-emerald-500"></span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-black uppercase tracking-tighter">Preguntas <span class="font-serif italic font-normal lowercase text-emerald-600">frecuentes</span></h2>
                </div>

                @php
                $faqs = [
                    ['p' => '¿Cómo confirmo mi reservación?',
                     'r' => 'Llena el formulario en la parte superior y recibirás un correo con tu folio y los datos bancarios. Tu fecha queda garantizada una vez que envíes el comprobante del anticipo (50%) por WhatsApp.'],
                    ['p' => '¿Qué incluye la estancia?',
                     'r' => 'Todas las habitaciones incluyen acceso a la alberca, jardín, palapa, estacionamiento gratuito y WiFi. La cocina equipada está disponible para uso compartido.'],
                    ['p' => '¿Cuáles son los horarios de check-in y check-out?',
                     'r' => 'El check-in es a partir de las 2:00 pm y el check-out es antes de las 12:00 pm. Si necesitas horarios especiales, contáctanos con anticipación y haremos lo posible por acomodarte.'],
                    ['p' => '¿Puedo cancelar o modificar mi reserva?',
                     'r' => 'Las cancelaciones con más de 7 días de anticipación reciben reembolso del anticipo. Cancelaciones con menos de 7 días no son reembolsables. Para modificar fechas, contáctanos directamente.'],
                    ['p' => '¿Aceptan mascotas?',
                     'r' => 'Por el momento no tenemos política de mascotas habilitada. Te recomendamos contactarnos directamente para consultar disponibilidad según el caso.'],
                    ['p' => '¿Hacen eventos como bodas o XV años?',
                     'r' => 'Sí, contamos con espacios para eventos privados. Puedes cotizar directamente desde el formulario de reserva seleccionando la opción de evento, o escribirnos por WhatsApp para un presupuesto personalizado.'],
                ];
                @endphp

                <div class="space-y-3">
                    @foreach($faqs as $faq)
                    <details class="group border border-slate-200 rounded-[1.5rem] bg-white [&_summary::-webkit-details-marker]:hidden hover:border-emerald-200 transition-colors">
                        <summary class="flex items-center justify-between gap-4 cursor-pointer p-6">
                            <h3 class="font-bold text-sm tracking-wide text-slate-800">{{ $faq['p'] }}</h3>
                            <span class="shrink-0 transition-transform duration-300 group-open:rotate-180 bg-slate-100 group-hover:bg-emerald-100 group-open:bg-emerald-100 text-slate-500 group-hover:text-emerald-600 group-open:text-emerald-600 p-2 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </span>
                        </summary>
                        <p class="px-6 pb-6 text-slate-500 text-sm leading-relaxed border-t border-slate-100 pt-4">{{ $faq['r'] }}</p>
                    </details>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== CTA ===================== --}}
        <section class="py-24 bg-slate-900 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-emerald-400 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-3xl mx-auto px-6 text-center">
                <p class="text-emerald-400 text-[10px] font-black uppercase tracking-widest mb-4">¿Listo para escapar?</p>
                <h2 class="text-4xl sm:text-5xl font-black uppercase tracking-tighter text-white mb-6">
                    Tu descanso <span class="font-serif italic font-normal lowercase text-emerald-400">perfecto</span> te espera
                </h2>
                <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto">
                    Reserva en minutos. Sin comisiones, sin intermediarios — directo con nosotros.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#inicio"
                       class="w-full sm:w-auto px-10 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/40">
                        Reservar ahora
                    </a>
                    <a href="https://wa.me/5219581072468" target="_blank" rel="noopener noreferrer"
                       class="w-full sm:w-auto px-10 py-4 bg-white/10 border border-white/20 text-white text-xs font-black uppercase tracking-widest rounded-full hover:bg-white hover:text-slate-900 transition-all backdrop-blur">
                        Contactar por WhatsApp
                    </a>
                </div>
            </div>
        </section>
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-slate-900 border-t border-white/5 pt-20 pb-10 text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12 pb-16 border-b border-white/10">
            <div class="md:col-span-2">
                <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto mb-6 brightness-0 invert" alt="Logo">
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    Tu refugio privado en Llano Grande, Tonameca. Calidad premium para eventos inolvidables y descanso reparador en la costa de Oaxaca.
                </p>
                <div class="flex gap-3 mt-6">
                    <a href="https://www.facebook.com/LaCasonaPalapa" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-xs font-black hover:bg-emerald-600 hover:border-emerald-600 transition-all">FB</a>
                    <a href="https://www.instagram.com/palapa.lacasona/" target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-xs font-black hover:bg-emerald-600 hover:border-emerald-600 transition-all">IG</a>
                </div>
            </div>
            <div>
                <h4 class="font-black uppercase text-[10px] tracking-[0.2em] mb-5 text-emerald-500">Navegación</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="#inicio"       class="hover:text-white transition-colors">Inicio</a></li>
                    <li><a href="#amenidades"   class="hover:text-white transition-colors">Amenidades</a></li>
                    <li><a href="#habitaciones" class="hover:text-white transition-colors">Habitaciones</a></li>
                    <li><a href="#galeria"      class="hover:text-white transition-colors">Galería</a></li>
                    <li><a href="#ubicacion"    class="hover:text-white transition-colors">Ubicación</a></li>
                    <li><a href="#faq"          class="hover:text-white transition-colors">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-black uppercase text-[10px] tracking-[0.2em] mb-5 text-emerald-500">Contacto</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li>+52 (958) 107 2468</li>
                    <li>palapalacasona01@gmail.com</li>
                    <li class="pt-2 text-slate-500">Llano Grande, Tonameca<br>Oaxaca, C.P. 70946</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-10 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-slate-600">
            <p>© {{ date('Y') }} Palapa La Casona. Hecho en Oaxaca.</p>
            <p>Designed by Ruter Digital Solutions MX</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- NAV: scroll shrink ---
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        });

        // --- NAV: menú hamburguesa ---
        const menuBtn    = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen   = document.getElementById('icon-open');
        const iconClose  = document.getElementById('icon-close');

        menuBtn.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('open');
            mobileMenu.classList.toggle('open',   !isOpen);
            mobileMenu.classList.toggle('hidden',  isOpen);
            iconOpen.classList.toggle('hidden',   !isOpen);
            iconClose.classList.toggle('hidden',   isOpen);
        });

        // Cerrar menú al hacer clic en un link
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                mobileMenu.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            });
        });

        // --- SWIPER: habitaciones ---
        document.querySelectorAll('.room-swiper').forEach(el => {
            new Swiper(el, {
                loop: true,
                speed: 700,
                effect: 'fade',
                fadeEffect: { crossFade: true },
                pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
                navigation: {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev'),
                },
            });
        });
    });
    </script>
</body>
</html>