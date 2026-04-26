<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Palapa La Casona | Eventos y Hospedaje Premium</title>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="antialiased bg-white text-slate-900">

    <main class="pt-24 overflow-x-hidden">
        
        <section id="reservas" class="max-w-7xl mx-auto px-6 py-20 grid lg:grid-cols-2 gap-16 items-center">
            
            <div class="space-y-6">
                <div>
                    <span class="inline-block bg-emerald-50 text-emerald-700 font-black uppercase tracking-[0.2em] text-xs px-4 py-2 rounded-full border border-emerald-100 shadow-sm">
                        Costa de Oaxaca
                    </span>
                </div>
                
                <h1 class="text-6xl md:text-7xl font-black text-slate-900 leading-[1.1] mt-4 uppercase">
                    Tu evento en <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">el paraíso</span>.
                </h1>
                
                <p class="text-xl text-slate-600 leading-relaxed max-w-lg">
                    Palapa, alberca y descanso exclusivo en Llano Grande, Santa María Tonameca.
                </p>
            </div>

            <div class="relative">
                <div class="absolute -top-10 -right-10 w-72 h-72 bg-emerald-200 rounded-full blur-3xl opacity-40 -z-10 mix-blend-multiply"></div>
                <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-teal-200 rounded-full blur-3xl opacity-40 -z-10 mix-blend-multiply"></div>
                
                <div id="react-booking-form"></div>
            </div>
        </section>

        </main>

    </body>
</html>