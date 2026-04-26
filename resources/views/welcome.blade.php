<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Palapa La Casona | Experiencia Exclusiva en la Costa</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700,800|playfair-display:700,900italic" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        .swiper-pagination-bullet-active { background: #10b981 !important; }
        .swiper-button-next, .swiper-button-prev { 
            color: white !important; 
            background: rgba(0,0,0,0.3); 
            width: 44px !important; 
            height: 44px !important; 
            border-radius: 50%; 
            backdrop-filter: blur(8px);
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px !important; font-weight: 800; }
    </style>
</head>
<body class="antialiased bg-[#FDFDFC] text-slate-900 overflow-x-hidden">

    <nav class="fixed w-full z-[100] transition-all duration-300 bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-32 flex items-center justify-between">
            
            <a href="#" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Palapa La Casona" class="h-24 md:h-32 w-auto object-contain transition-transform hover:scale-105 duration-300">
            </a>

            <div class="hidden lg:flex items-center gap-10 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">
                <a href="#inicio" class="hover:text-emerald-600 transition-colors">Inicio</a>
                <a href="#habitaciones" class="hover:text-emerald-600 transition-colors">Habitaciones</a>
                <a href="#galeria" class="hover:text-emerald-600 transition-colors">Galería</a>
                <a href="#ubicacion" class="hover:text-emerald-600 transition-colors">Ubicación</a> 
                <a href="#faq" class="hover:text-emerald-600 transition-colors">Dudas</a>
            </div>
            
            <a href="{{ route('login') }}" class="bg-slate-900 text-white px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg">
                Admin
            </a>
        </div>
    </nav>

    <main>
        <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-slate-900">
            <video autoplay muted loop playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover opacity-50">
                <source src="/hero-bg.mp4" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-transparent to-[#FDFDFC] z-10"></div>

            <div class="relative z-20 max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center pt-32">
                <div class="text-white space-y-8">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400">Disponible 2026</span>
                    </div>
                    <h1 class="text-7xl md:text-8xl font-black uppercase tracking-tighter leading-[0.85]">
                        Escape <br><span class="font-serif italic text-emerald-400 lowercase font-normal">tropical</span>.
                    </h1>
                    <p class="text-xl text-slate-200 font-light max-w-md">
                        Hospedaje premium y habitaciones con alma en el corazón de la costa de Oaxaca.
                    </p>
                </div>
                <div id="react-booking-form" class="lg:ml-auto w-full max-w-md bg-white p-2 rounded-[2.5rem] shadow-2xl"></div>
            </div>
        </section>

        <section id="habitaciones" class="py-32 container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-6xl font-black uppercase tracking-tighter">Nuestros <br><span class="text-emerald-600">Espacios</span></h2>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($rooms as $room)
                <div class="group relative bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 hover:shadow-2xl transition-all duration-500 flex flex-col">
                    <div class="swiper room-swiper h-[400px] w-full relative">
                        <div class="swiper-wrapper">
                            @if($room->images && $room->images->count() > 0)
                                @foreach($room->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                </div>
                                @endforeach
                            @elseif($room->image_path)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $room->image_path) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                        <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="swiper-pagination"></div>
                        <div class="absolute top-6 left-6 z-10">
                            <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                                ${{ number_format($room->price_per_night, 0) }} / Noche
                            </span>
                        </div>
                    </div>

                    <div class="p-10 flex-grow flex flex-col">
                        <h3 class="text-2xl font-black uppercase tracking-tight text-slate-900 mb-4">{{ $room->name }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-8 flex-grow">
                            {{ $room->description }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <div class="flex gap-4 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"/></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 4a2 2 0 0 1 2 2v2H7V6a2 2 0 0 1 2-2Z"/><path d="M11 4a2 2 0 0 1 2 2v2h-2V6a2 2 0 0 1 2-2Z"/><path d="M15 4a2 2 0 0 1 2 2v2h-2V6a2 2 0 0 1 2-2Z"/><path d="M20 10H4v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2Z"/><path d="M7 14v5"/><path d="M11 14v5"/><path d="M15 14v5"/><path d="M19 14v5"/></svg>
                            </div>
                            <a href="#inicio" class="text-[10px] font-black uppercase tracking-widest text-slate-900 flex items-center gap-2 group/btn">
                                Reservar <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section id="galeria" class="py-32 bg-slate-50 overflow-hidden font-inter antialiased">
            <div class="max-w-7xl mx-auto px-6 mb-12">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 text-center md:text-left">
                    <div class="w-full">
                        <div class="inline-flex items-center gap-2 mb-6">
                            <span class="w-8 h-[2px] bg-emerald-500"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Conoce el espacio</span>
                        </div>
                        <h2 class="text-5xl font-black uppercase tracking-tighter">Nuestra <span class="font-serif italic font-normal lowercase text-emerald-600">Galería</span></h2>
                        <p class="text-slate-500 mt-4 text-lg max-w-xl mx-auto md:mx-0">Descubre la esencia de La Casona a través de nuestra lente.</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6">
                <div class="group flex max-md:flex-col justify-center gap-2">
                    
                    {{-- Limitamos a 5 fotos para que el acordeón tenga espacio de lucirse --}}
                    @foreach($gallery->take(5) as $img)
                    <article class="group/article relative w-full rounded-2xl overflow-hidden md:group-hover:[&:not(:hover)]:w-[20%] md:group-focus-within:[&:not(:focus-within):not(:hover)]:w-[20%] transition-all duration-300 ease-[cubic-bezier(.5,.85,.25,1.15)] before:absolute before:inset-x-0 before:bottom-0 before:h-1/3 before:bg-gradient-to-t before:from-black/50 before:transition-opacity md:before:opacity-0 md:hover:before:opacity-100 focus-within:before:opacity-100 after:opacity-0 md:group-hover:[&:not(:hover)]:after:opacity-100 md:group-focus-within:[&:not(:focus-within):not(:hover)]:after:opacity-100 after:absolute after:inset-0 after:bg-white/30 after:backdrop-blur after:transition-all focus-within:ring focus-within:ring-emerald-300">
                        <a class="absolute inset-0 text-white z-10" href="#galeria">
                            <span class="absolute inset-x-0 bottom-0 text-lg font-medium p-6 md:px-12 md:py-8 md:whitespace-nowrap md:truncate md:opacity-0 group-hover/article:opacity-100 group-focus-within/article:opacity-100 md:translate-y-2 group-hover/article:translate-y-0 group-focus-within/article:translate-y-0 transition duration-200 ease-[cubic-bezier(.5,.85,.25,1.8)] group-hover/article:delay-300 group-focus-within/article:delay-300">
                                {{ $img->alt ?? 'Rincón de La Casona' }}
                            </span>
                        </a>
                        <img class="object-cover h-72 md:h-[500px] w-full" 
                             src="{{ asset('storage/' . $img->image_path) }}" 
                             alt="{{ $img->alt ?? 'Foto Galería' }}">
                    </article>
                    @endforeach

                </div>
            </div>
        </section>

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
                            Estamos ubicados en Llano Grande, Tonameca. Un oasis privado y tranquilo, a pocos minutos de las mejores playas de la costa oaxaqueña.
                        </p>

                        <div class="space-y-8">
                            <div class="flex items-start gap-4 group">
                                <div class="bg-slate-50 p-4 rounded-2xl text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-900 mb-1">Dirección</h4>
                                    <p class="text-slate-500 text-sm">Domicilio Conocido, Llano Grande<br>Santa María Tonameca, Oaxaca, C.P. 70946</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4 group">
                                <div class="bg-slate-50 p-4 rounded-2xl text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-900 mb-1">Contacto Directo</h4>
                                    <p class="text-slate-500 text-sm">+52 (958) 107 2468<br>palapalacasona01@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full h-[500px] rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-shadow duration-500">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15360.643493236696!2d-96.54631655!3d15.742626650000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85b8d7a645836183%3A0xaa2fd69b1b5b584d!2sPalapa%20La%20Casona!5e0!3m2!1ses-419!2smx!4v1777178942899!5m2!1ses-419!2smx" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale hover:grayscale-0 transition-all duration-1000 object-cover">
                        </iframe>
                        
                        <div class="absolute bottom-6 left-6 z-10 pointer-events-none">
                            <span class="bg-slate-900/90 backdrop-blur text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Abrir en Google Maps
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="faq" class="py-32 bg-white">
            <div class="max-w-4xl mx-auto px-6">
                <h2 class="text-4xl font-black uppercase tracking-tighter text-center mb-16">Preguntas frecuentes</h2>
                <div class="space-y-4">
                    <details class="group border border-slate-100 rounded-[2rem] p-8 [&_summary::-webkit-details-marker]:hidden bg-slate-50/50">
                        <summary class="flex items-center justify-between cursor-pointer">
                            <h3 class="font-bold uppercase text-sm tracking-widest">¿Cómo confirmo mi reservación?</h3>
                            <span class="transition group-open:rotate-180 bg-emerald-100 text-emerald-600 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </summary>
                        <p class="mt-6 text-slate-500 text-sm leading-relaxed border-t border-slate-200 pt-6">Tras llenar el formulario de arriba, recibirás un correo con los datos bancarios. Tu fecha queda garantizada una vez enviado el comprobante de anticipo.</p>
                    </details>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 pt-24 pb-12 text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-12 border-b border-white/10 pb-16 text-center md:text-left">
            <div class="col-span-2 flex flex-col items-center md:items-start">
                <img src="{{ asset('images/logo.png') }}" class="h-20 w-auto mb-6 brightness-0 invert" alt="Logo Footer">
                <p class="text-slate-400 max-w-sm">Tu refugio privado en Llano Grande, Tonameca. Calidad premium para eventos inolvidables y descanso reparador.</p>
            </div>
            <div>
                <h4 class="font-bold uppercase text-[10px] tracking-[0.2em] mb-6 text-emerald-500">Navegación</h4>
                <ul class="space-y-4 text-sm text-slate-300">
                    <li><a href="#habitaciones" class="hover:text-white transition-colors">Habitaciones</a></li>
                    <li><a href="#galeria" class="hover:text-white transition-colors">Galería</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold uppercase text-[10px] tracking-[0.2em] mb-6 text-emerald-500">Sígannos</h4>
                <div class="flex gap-4 justify-center md:justify-start">
                    <a href="https://www.facebook.com/LaCasonaPalapa" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center hover:bg-emerald-600 transition-all text-xs font-black">FB</a>
                    <a href="https://www.instagram.com/palapa.lacasona/" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center hover:bg-emerald-600 transition-all text-xs font-black">IG</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-12 flex flex-col md:flex-row justify-between text-[10px] font-bold uppercase tracking-widest text-slate-500 items-center gap-4">
            <p>© 2026 PALAPA LA CASONA. HECHO EN OAXACA.</p>
            <p>DESIGNED BY RUTER DIGITAL SOLUTIONS MX</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicialización de las Habitaciones
            const roomSwipers = new Swiper('.room-swiper', {
                loop: true,
                speed: 800,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                effect: 'fade',
                fadeEffect: { crossFade: true }
            });

            // Inicialización de la Galería Automática
            
        });
    </script>
</body>
</html>