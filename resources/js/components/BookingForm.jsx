import { useState, useEffect } from 'react';

const API_URL = "/api";

// Variables de entorno — configura en tu .env:
// VITE_WHATSAPP_NUMBER=5219581072468
// VITE_BANK_CLABE=012345678901234567
// VITE_BANK_TITULAR=Palapa La Casona
const WA_NUMBER   = import.meta.env.VITE_WHATSAPP_NUMBER  || '521XXXXXXXXXX';
const BANK_CLABE  = import.meta.env.VITE_BANK_CLABE       || '000000000000000000';
const BANK_TITULAR = import.meta.env.VITE_BANK_TITULAR    || 'Palapa La Casona';

// Fecha mínima = hoy en formato YYYY-MM-DD (local, no UTC)
const today = () => {
  const d = new Date();
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
};

// Día siguiente a una fecha dada
const nextDay = (dateStr) => {
  if (!dateStr) return '';
  const d = new Date(dateStr + 'T00:00:00');
  d.setDate(d.getDate() + 1);
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
};

export default function BookingForm() {
  const [loading, setLoading]           = useState(false);
  const [checkingRooms, setCheckingRooms] = useState(false);
  const [response, setResponse]         = useState(null);
  const [rooms, setRooms]               = useState([]);

  const [checkIn, setCheckIn]   = useState('');
  const [checkOut, setCheckOut] = useState('');
  const [guests, setGuests]     = useState(1);

  // Consultar disponibilidad al cambiar fechas o huéspedes
  useEffect(() => {
    if (checkIn && checkOut) {
      setCheckingRooms(true);
      setRooms([]);

      fetch(`${API_URL}/check-availability`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ check_in: checkIn, check_out: checkOut, guests: parseInt(guests) })
      })
        .then(res => res.json())
        .then(data => { setRooms(data); setCheckingRooms(false); })
        .catch(() => setCheckingRooms(false));
    }
  }, [checkIn, checkOut, guests]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setResponse(null);

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    data.room_id = parseInt(data.room_id);

    try {
      const res = await fetch(`${API_URL}/reserve-room`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data),
      });

      const result = await res.json();
      if (res.ok) {
        setResponse({ success: true, ...result });
      } else {
        setResponse({ success: false, error: result.message || 'Error al procesar la reserva.' });
      }
    } catch {
      setResponse({ success: false, error: 'Error de conexión con el servidor.' });
    } finally {
      setLoading(false);
    }
  };

  // --- PANTALLA DE ÉXITO ---
  if (response?.success) {
    const formatDate = (d) => {
      const [y, m, day] = d.split('-');
      const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
      return `${parseInt(day)} ${meses[parseInt(m) - 1]} ${y}`;
    };

    const nights = response.check_in && response.check_out
      ? Math.round((new Date(response.check_out) - new Date(response.check_in)) / 86400000)
      : null;

    const waMessage = `Hola, envío el comprobante de pago para mi reserva en *${response.room_name}*.\nFolio: *${response.folio}*\nEntrada: ${formatDate(response.check_in)} | Salida: ${formatDate(response.check_out)}`;
    const waLink = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(waMessage)}`;

    return (
      <div className="bg-white border border-slate-200 p-8 rounded-2xl shadow-sm space-y-6">
        {/* Encabezado */}
        <div className="text-center">
          <div className="inline-flex items-center justify-center w-14 h-14 bg-emerald-100 rounded-full mb-3">
            <svg className="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}>
              <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h3 className="text-lg font-black text-slate-900 uppercase tracking-tight">¡Reserva recibida!</h3>
          <p className="text-xs text-slate-500 mt-1">Te hemos enviado los detalles a tu correo.</p>
        </div>

        {/* Folio */}
        <div className="bg-slate-50 border border-slate-100 rounded-xl p-4 text-center">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Número de folio</p>
          <p className="font-mono text-2xl font-bold text-emerald-700">{response.folio}</p>
        </div>

        {/* Resumen */}
        <div className="space-y-2 text-sm">
          <div className="flex justify-between text-slate-600 border-b border-slate-50 pb-2">
            <span className="font-semibold">Habitación</span>
            <span className="font-bold text-slate-900">{response.room_name}</span>
          </div>
          {response.check_in && (
            <div className="flex justify-between text-slate-600 border-b border-slate-50 pb-2">
              <span className="font-semibold">Entrada</span>
              <span className="font-bold text-slate-900">{formatDate(response.check_in)}</span>
            </div>
          )}
          {response.check_out && (
            <div className="flex justify-between text-slate-600 border-b border-slate-50 pb-2">
              <span className="font-semibold">Salida</span>
              <span className="font-bold text-slate-900">{formatDate(response.check_out)}</span>
            </div>
          )}
          {nights && (
            <div className="flex justify-between text-slate-600 border-b border-slate-50 pb-2">
              <span className="font-semibold">Noches</span>
              <span className="font-bold text-slate-900">{nights}</span>
            </div>
          )}
          <div className="flex justify-between text-slate-600">
            <span className="font-semibold">Total</span>
            <span className="font-bold text-emerald-700 text-base">${Number(response.total).toLocaleString('es-MX')} MXN</span>
          </div>
        </div>

        {/* Datos bancarios si es transferencia */}
        {response.payment === 'transfer' && (
          <div className="bg-emerald-50 border border-emerald-100 rounded-xl p-5">
            <p className="text-[10px] font-black text-emerald-800 uppercase tracking-widest mb-3">Datos para anticipo (50%)</p>
            <div className="space-y-2 text-sm">
              <div className="flex justify-between border-b border-emerald-100 pb-1">
                <span className="text-emerald-700">Banco</span>
                <strong className="text-emerald-900">BBVA</strong>
              </div>
              <div className="flex justify-between border-b border-emerald-100 pb-1">
                <span className="text-emerald-700">Titular</span>
                <strong className="text-emerald-900">{BANK_TITULAR}</strong>
              </div>
              <div className="flex justify-between border-b border-emerald-100 pb-1">
                <span className="text-emerald-700">CLABE</span>
                <strong className="font-mono text-emerald-900">{BANK_CLABE}</strong>
              </div>
              <div className="flex justify-between">
                <span className="text-emerald-700">Concepto</span>
                <strong className="text-emerald-900">{response.folio}</strong>
              </div>
            </div>

            <a href={waLink} target="_blank" rel="noopener noreferrer"
               className="mt-5 flex items-center justify-center gap-2 w-full py-3 bg-emerald-600 text-white text-xs font-black rounded-xl hover:bg-emerald-700 transition-colors uppercase tracking-widest shadow-md">
              <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.558 4.118 1.531 5.845L.057 23.486a.5.5 0 00.619.635l5.801-1.519A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.877 0-3.63-.502-5.145-1.38l-.368-.214-3.44.901.918-3.354-.23-.375A9.953 9.953 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
              </svg>
              Enviar comprobante por WhatsApp
            </a>
          </div>
        )}

        {response.payment === 'cash' && (
          <div className="bg-slate-50 border border-slate-100 rounded-xl p-4 text-sm text-slate-600">
            <p className="font-bold text-slate-900 mb-1">Pago en efectivo al llegar</p>
            <p>Presenta tu folio <strong className="text-emerald-700">{response.folio}</strong> al momento del check-in.</p>
            <a href={waLink} target="_blank" rel="noopener noreferrer"
               className="mt-4 flex items-center justify-center gap-2 w-full py-2.5 bg-slate-800 text-white text-xs font-black rounded-xl hover:bg-emerald-700 transition-colors uppercase tracking-widest">
              Confirmar por WhatsApp
            </a>
          </div>
        )}

        <button
          onClick={() => { setResponse(null); setCheckIn(''); setCheckOut(''); setGuests(1); }}
          className="w-full text-xs font-bold text-slate-400 hover:text-slate-700 transition-colors uppercase tracking-widest pt-2"
        >
          Nueva reservación
        </button>
      </div>
    );
  }

  // --- FORMULARIO ---
  return (
    <form onSubmit={handleSubmit} className="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 space-y-6">
      <header className="border-b border-slate-50 pb-4">
        <h2 className="text-xl font-bold text-slate-900 uppercase tracking-tight">Sistema de Reservas</h2>
        <p className="text-slate-400 text-xs mt-1 font-medium italic">Atención personalizada en La Casona</p>
      </header>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-1">
          <label className="text-[10px] font-bold uppercase text-slate-400">Entrada</label>
          <input name="check_in" type="date" required value={checkIn}
                 min={today()}
                 onChange={(e) => {
                   const val = e.target.value;
                   setCheckIn(val);
                   // Si el checkout ya elegido queda igual o antes, lo limpiamos
                   if (checkOut && checkOut <= val) {
                     setCheckOut('');
                     setRooms([]);
                   }
                 }}
                 className="w-full p-3 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500 outline-none text-sm" />
        </div>
        <div className="space-y-1">
          <label className="text-[10px] font-bold uppercase text-slate-400">Salida</label>
          <input name="check_out" type="date" required value={checkOut}
                 min={checkIn ? nextDay(checkIn) : today()}
                 onChange={(e) => setCheckOut(e.target.value)}
                 className="w-full p-3 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500 outline-none text-sm" />
        </div>
      </div>

      <div className="space-y-1">
        <label className="text-[10px] font-bold uppercase text-slate-400">Número de Huéspedes</label>
        <select name="guests" value={guests} onChange={(e) => setGuests(e.target.value)}
                className="w-full p-3 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500 outline-none text-sm bg-white">
          {[1, 2, 3, 4, 5, 6].map(num => (
            <option key={num} value={num}>{num} {num === 1 ? 'Persona' : 'Personas'}</option>
          ))}
        </select>
      </div>

      <div className="space-y-1">
        <label className="text-[10px] font-bold uppercase text-slate-400">Habitaciones Disponibles</label>
        <select name="room_id" required disabled={!checkIn || !checkOut || checkingRooms}
                className="w-full p-3 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500 outline-none transition-all disabled:bg-slate-50 text-sm">
          <option value="">
            {checkingRooms ? 'Consultando disponibilidad...' : (!checkIn ? 'Elige fechas para comenzar' : 'Selecciona una habitación')}
          </option>
          {rooms.map(room => (
            <option key={room.id} value={room.id}>{room.name} — ${Number(room.price_per_night).toLocaleString('es-MX')} / noche</option>
          ))}
        </select>
        {!checkingRooms && checkIn && checkOut && rooms.length === 0 && (
          <p className="text-[10px] text-red-600 font-bold uppercase mt-1 tracking-tighter">
            Sin disponibilidad para estas fechas o capacidad.
          </p>
        )}
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-50 pt-4">
        <input name="name" type="text" placeholder="Nombre completo" required
               className="w-full p-3 rounded-xl border border-slate-200 text-sm outline-none" />
        <input name="email" type="email" placeholder="Correo electrónico" required
               className="w-full p-3 rounded-xl border border-slate-200 text-sm outline-none" />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input name="phone" type="tel" placeholder="WhatsApp" required
               className="w-full p-3 rounded-xl border-2 border-slate-100 text-sm outline-none" />
        <select name="payment_method" required
                className="w-full p-3 rounded-xl border border-slate-200 text-sm outline-none bg-white font-medium">
          <option value="transfer">Transferencia</option>
          <option value="cash">Efectivo al llegar</option>
        </select>
      </div>

      {response?.error && (
        <div className="bg-red-50 border border-red-100 text-red-700 text-xs font-bold rounded-xl p-4 uppercase tracking-wide">
          {response.error}
        </div>
      )}

      <button type="submit" disabled={loading || !rooms.length}
              className="w-full py-4 rounded-xl font-bold text-white bg-slate-900 hover:bg-emerald-800 disabled:bg-slate-200 disabled:cursor-not-allowed transition-all shadow-md uppercase tracking-widest text-xs">
        {loading ? 'Confirmando...' : 'Confirmar Reservación'}
      </button>
    </form>
  );
}