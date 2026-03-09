<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Scanner Logística</title>
    <link rel="icon" type="image/png" href="{{ asset('img/vale-regalo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { font-family: 'Inter', sans-serif; }

        #qr-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @keyframes scan-line {
            0%   { top: 8%;  opacity: 1; }
            50%  { top: 82%; opacity: 0.5; }
            100% { top: 8%;  opacity: 1; }
        }
        .scan-line { animation: scan-line 2s ease-in-out infinite; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-enter { animation: fadeSlideUp .3s ease forwards; }

        [data-hide] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ── HEADER ── --}}
    <div class="bg-white h-16 flex justify-between items-center px-4 shadow-sm z-20 sticky top-0 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow-sm" style="background:#121f48;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-sm text-gray-900 leading-tight" style="font-weight:800;">Caseta Visor</h1>
                <div class="flex items-center gap-1.5">
                    <div id="status-dot" class="w-1.5 h-1.5 rounded-full bg-red-400 transition-colors"></div>
                    <span id="status-label" class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">EN PAUSA</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- ── CONTENIDO ── --}}
    <div class="flex-grow flex flex-col items-center pt-5 px-4 gap-4 overflow-y-auto pb-8">

        {{-- ── VISOR CÁMARA ── --}}
        <div class="relative w-full max-w-sm bg-black rounded-2xl overflow-hidden shadow-lg border border-gray-200 shrink-0" style="aspect-ratio:16/9;">

            <video id="qr-video" autoplay playsinline muted></video>

            {{-- Marco de escaneo --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-40 h-40 relative">
                    <div class="absolute top-0 left-0 w-5 h-5 border-t-2 border-l-2 border-green-400 rounded-tl-sm"></div>
                    <div class="absolute top-0 right-0 w-5 h-5 border-t-2 border-r-2 border-green-400 rounded-tr-sm"></div>
                    <div class="absolute bottom-0 left-0 w-5 h-5 border-b-2 border-l-2 border-green-400 rounded-bl-sm"></div>
                    <div class="absolute bottom-0 right-0 w-5 h-5 border-b-2 border-r-2 border-green-400 rounded-br-sm"></div>
                    <div class="absolute left-1 right-1 h-0.5 bg-green-400/70 rounded-full scan-line"></div>
                </div>
            </div>

            {{-- Cooldown overlay --}}
            <div id="cooldown-overlay" data-hide class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-2 pointer-events-none">
                <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-white text-xs" style="font-weight:700;">Espera <span id="cooldown-secs">7</span>s</span>
            </div>

            {{-- Barra de cooldown --}}
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/30">
                <div id="cooldown-bar" class="h-full" style="width:100%; background:#eef1f8;"></div>
            </div>

            {{-- Etiqueta guía --}}
            <div class="absolute bottom-3 left-0 w-full text-center pointer-events-none">
                <span id="guia-label" class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur" style="font-weight:700;">Escanea el vale</span>
            </div>
        </div>

        {{-- ── INPUT MANUAL ── --}}
        <div id="manual-section" class="w-full max-w-sm flex gap-2">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
                </svg>
                <input id="input-manual" type="text"
                       class="w-full bg-white border border-gray-200 rounded-xl pl-9 pr-3 py-2.5 text-sm outline-none uppercase shadow-sm"
                       style="font-weight:500;"
                       placeholder="Código manual...">
            </div>
            <button id="btn-manual"
                    class="text-white px-4 rounded-xl shadow-sm flex items-center justify-center"
                    style="background:#121f48;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        {{-- ── CARD DEL VALE ── --}}
        <div id="vale-card" data-hide class="w-full max-w-sm bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-enter">

            <div class="text-white px-5 pt-5 pb-6 relative overflow-hidden" style="background:#121f48;">
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white/5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                </svg>
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1" style="font-weight:800;">Unidad autorizada</p>
                        <h2 id="card-placa" class="text-3xl text-white tracking-tight uppercase" style="font-weight:800;">—</h2>
                    </div>
                    <button id="btn-reset"
                            class="w-7 h-7 flex items-center justify-center rounded-xl bg-white/10 hover:bg-red-500/80 text-white/60 hover:text-white transition-colors shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full">
                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>
                    </svg>
                    <span id="card-folio" class="text-xs text-white/80 font-mono" style="font-weight:600;">—</span>
                </div>
            </div>

            <div class="p-4 grid grid-cols-2 gap-4 border-b border-gray-100">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-2" style="font-weight:800;">Carga / Material</p>
                    <p id="card-material" class="text-sm text-gray-800 leading-tight" style="font-weight:700;">—</p>
                    <p class="text-xs mt-0.5" style="font-weight:600; color:#121f48;">
                        <span id="card-cantidad">—</span> <span id="card-unit">—</span>
                    </p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-2" style="font-weight:800;">Cliente destino</p>
                    <p id="card-cliente" class="text-sm text-gray-800 leading-tight truncate" style="font-weight:700;">—</p>
                </div>
            </div>

            <div class="p-4">
                <div id="panel-entrada" data-hide class="rounded-xl p-4 text-center border border-yellow-200 bg-yellow-50">
                    <p class="text-[9px] text-yellow-600 uppercase tracking-widest mb-2" style="font-weight:800;">Paso 2 · Validación</p>
                    <div class="flex items-center justify-center gap-2 text-yellow-700 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-base uppercase" style="font-weight:800;">Escanear Camión</span>
                    </div>
                    <p class="text-xs text-yellow-600" style="font-weight:500;">Apunta al QR pegado en la unidad</p>
                </div>

                <div id="panel-salida" data-hide class="rounded-xl p-4 border border-green-200 bg-green-50">
                    <p class="text-[9px] text-green-600 uppercase tracking-widest mb-3 text-center" style="font-weight:800;">Paso Final · Confirmar Salida</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button id="btn-surtido"
                                class="text-white text-xs py-2.5 rounded-xl shadow-sm"
                                style="background:#121f48; font-weight:700;">
                            Surtido
                        </button>
                        <button id="btn-vacio"
                                class="bg-white text-gray-600 text-xs py-2.5 rounded-xl border border-gray-200 shadow-sm"
                                style="font-weight:700;">
                            Vacío
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

{{-- ═══════════════════════════════════════════════════════════════
     qr-scanner de Nimiq — la única librería que funciona
     correctamente en iOS Safari + Android Chrome sin hacks
     ═══════════════════════════════════════════════════════════════ --}}
<script type="module">
import QrScanner from 'https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.min.js';

// ── Estado ──
const S = {
    vale: null, context: 'inicio',
    procesando: false, cooldown: false,
    COOLDOWN: 7,
    _cTimer: null, _cInterval: null,
};

// ── DOM ──
const $  = id => document.getElementById(id);
const show = el => el.removeAttribute('data-hide');
const hide = el => el.setAttribute('data-hide', '');

// ── Scanner ──
const scanner = new QrScanner(
    $('qr-video'),
    result => onScan(result.data ?? result),
    {
        preferredCamera:          'environment',
        highlightScanRegion:      false,
        highlightCodeOutline:     false,
        returnDetailedScanResult: true,
    }
);

scanner.start()
    .then(() => {
        $('status-dot').classList.replace('bg-red-400', 'bg-green-400');
        $('status-dot').classList.add('animate-pulse');
        $('status-label').textContent = 'CÁMARA ACTIVA';
    })
    .catch(err => {
        console.error(err);
        alertError('No se pudo abrir la cámara. Verifica permisos y que uses HTTPS.');
        $('guia-label').textContent = 'Usa el código manual';
    });

// ── Eventos ──
$('btn-manual').addEventListener('click', () => {
    const v = $('input-manual').value.trim();
    if (!v) return;
    $('input-manual').value = '';
    onScan(v);
});
$('input-manual').addEventListener('keydown', e => { if (e.key === 'Enter') $('btn-manual').click(); });
$('btn-reset').addEventListener('click',   resetTodo);
$('btn-surtido').addEventListener('click', () => onScan('CMD_SURTIDO'));
$('btn-vacio').addEventListener('click',   () => onScan('CMD_VACIO'));

// ── Lógica ──
async function onScan(codigo) {
    const esCmd = ['CMD_SURTIDO','CMD_VACIO'].includes(codigo.toUpperCase());
    if ((S.cooldown || S.procesando) && !esCmd) return;
    S.procesando = true;
    playSound('beep');
    startCooldown();

    if (!S.vale)                          await lookupVale(codigo);
    else if (S.context === 'entrada')     await confirmarEntrada(codigo);
    else if (S.context === 'salida')      await procesarComando(codigo);
}

async function lookupVale(codigo) {
    try {
        const res  = await post('/operations/lookup', { code: codigo });
        const data = await res.json();
        if (!res.ok || data.status !== 'success') {
            S.procesando = false;
            return alertError(data?.message ?? `Error ${res.status}`);
        }
        S.vale    = data.data;
        S.context = data.context;
        renderCard();
        $('guia-label').textContent = S.context === 'entrada' ? 'Escanea QR de la unidad' : 'Confirma la salida';
    } catch {
        S.procesando = false;
        alertError('Sin conexión. Verifica tu red.');
    }
}

async function confirmarEntrada(qr) {
    await enviarServidor('confirmar_entrada', limpiarCodigo(qr));
}

async function procesarComando(codigo) {
    const c = codigo.toUpperCase();
    if      (c.includes('SURTIDO')) await enviarServidor('salida_surtido');
    else if (c.includes('VACIO'))   await enviarServidor('salida_vacio');
    else { S.procesando = false; alertError('Código inválido. Usa Surtido / Vacío.'); }
}

async function enviarServidor(accion, unitCode = null) {
    try {
        const res  = await post('/operations/register', { vale_id: S.vale.id, accion, unit_code: unitCode });
        const data = await res.json();
        if (!res.ok || data.status !== 'success') {
            S.procesando = false;
            return alertError(data?.message ?? `Error ${res.status}`);
        }
        playSound('success');
        await Swal.fire({
            icon: 'success', title: '¡Listo!', text: data.message,
            timer: 2000, showConfirmButton: false,
            background: '#f0fdf4', iconColor: '#16a34a',
            customClass: { popup: 'rounded-2xl shadow-xl' }
        });
        resetTodo();
    } catch {
        S.procesando = false;
        alertError('Sin conexión. No se pudo guardar.');
    }
}

function renderCard() {
    const v = S.vale;
    $('card-placa').textContent    = v?.unit?.placa          ?? 'EXTERNA';
    $('card-folio').textContent    = v?.folio_vale            ?? '—';
    $('card-material').textContent = v?.material?.name        ?? '—';
    $('card-cantidad').textContent = v?.cantidad              ?? '—';
    $('card-unit').textContent     = v?.material?.unit        ?? '';
    $('card-cliente').textContent  = v?.sale?.client?.name    ?? '—';
    hide($('manual-section')); show($('vale-card'));
    hide($('panel-entrada'));  hide($('panel-salida'));
    if (S.context === 'entrada') show($('panel-entrada'));
    if (S.context === 'salida')  show($('panel-salida'));
}

function resetTodo() {
    S.vale = null; S.context = 'inicio'; S.procesando = false;
    hide($('vale-card')); show($('manual-section'));
    hide($('panel-entrada')); hide($('panel-salida'));
    $('guia-label').textContent = 'Escanea el vale';
}

function startCooldown() {
    S.cooldown = true;
    let secs = S.COOLDOWN;
    $('cooldown-secs').textContent = secs;
    show($('cooldown-overlay'));

    const bar = $('cooldown-bar');
    bar.style.transition = 'none';
    bar.style.width = '100%';
    requestAnimationFrame(() => requestAnimationFrame(() => {
        bar.style.transition = `width ${S.COOLDOWN}s linear`;
        bar.style.width = '0%';
    }));

    clearInterval(S._cInterval);
    S._cInterval = setInterval(() => {
        $('cooldown-secs').textContent = --secs;
        if (secs <= 0) clearInterval(S._cInterval);
    }, 1000);

    clearTimeout(S._cTimer);
    S._cTimer = setTimeout(() => {
        S.cooldown = S.procesando = false;
        hide($('cooldown-overlay'));
        bar.style.transition = 'none';
        bar.style.width = '100%';
    }, S.COOLDOWN * 1000);
}

// ── Utils ──
function post(url, body) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(body),
    });
}

function limpiarCodigo(c) {
    if (!c) return '';
    if (c.includes('http') || c.includes('/')) return c.split('/').pop();
    return c;
}

function alertError(msg) {
    playSound('error');
    return Swal.fire({
        icon: 'error', title: 'Atención', text: msg,
        confirmButtonText: 'Entendido', confirmButtonColor: '#121f48',
        customClass: { popup: 'rounded-2xl shadow-xl', confirmButton: 'rounded-xl' }
    });
}

function playSound(type) {
    const s = { beep:'https://www.soundjay.com/button/beep-07.mp3', success:'https://www.soundjay.com/misc/sounds/magic-chime-01.mp3', error:'https://www.soundjay.com/button/button-10.mp3' };
    new Audio(s[type]).play().catch(()=>{});
}
</script>
</body>
</html>