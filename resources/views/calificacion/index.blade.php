<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calificación — Kiosco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { -webkit-tap-highlight-color: transparent; user-select: none; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            overflow: hidden;
            height: 100dvh;
        }

        /* Logo girando */
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: .5; transform: scale(1); }
            50%       { opacity: 1;  transform: scale(1.08); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pop {
            0%   { transform: scale(0.8); opacity: 0; }
            70%  { transform: scale(1.1); }
            100% { transform: scale(1);   opacity: 1; }
        }
        @keyframes ripple {
            0%   { transform: scale(0); opacity: .6; }
            100% { transform: scale(3); opacity: 0; }
        }
        @keyframes countdown {
            from { stroke-dashoffset: 0; }
            to   { stroke-dashoffset: 251; }
        }

        .spin-logo   { animation: spin-slow 8s linear infinite; }
        .pulse-ring  { animation: pulse-glow 2.5s ease-in-out infinite; }
        .float-el    { animation: float 3.5s ease-in-out infinite; }
        .fade-in-up  { animation: fade-in-up .5s ease forwards; }
        .pop         { animation: pop .4s cubic-bezier(.34,1.56,.64,1) forwards; }

        .face-btn {
            transition: transform .15s cubic-bezier(.34,1.56,.64,1), box-shadow .15s;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .face-btn:active { transform: scale(.88) !important; }

        .face-btn.selected {
            transform: scale(1.18);
            box-shadow: 0 0 0 4px rgba(59,130,246,.35);
        }

        /* Ripple on tap */
        .ripple-wrap { position: relative; overflow: hidden; }
        .ripple-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(255,255,255,.4) 0%, transparent 70%);
            transform: scale(0);
            border-radius: 50%;
            animation: none;
        }
        .ripple-wrap:active::after { animation: ripple .5s ease-out; }

        /* Timer ring */
        .timer-ring {
            transform-origin: center;
            transform: rotate(-90deg);
        }
        .timer-animate {
            animation: countdown linear forwards;
        }

        /* Screen transitions */
        .screen { position: absolute; inset: 0; transition: opacity .4s ease, transform .4s ease; }
        .screen.hidden-screen { opacity: 0; pointer-events: none; transform: scale(.97); }
        .screen.active-screen { opacity: 1; pointer-events: all; transform: scale(1); }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center">

    {{-- ════════════════════════════════════════
         PANTALLA 1: REPOSO — logo girando
    ════════════════════════════════════════ --}}
    <div id="screen-sleep" class="screen active-screen flex flex-col items-center justify-center gap-8 p-8 text-center">

        {{-- Anillos de fondo --}}
        <div class="relative flex items-center justify-center">
            <div class="pulse-ring absolute w-52 h-52 rounded-full bg-blue-100 opacity-40"></div>
            <div class="pulse-ring absolute w-40 h-40 rounded-full bg-blue-200 opacity-30" style="animation-delay:.8s"></div>

            {{-- Logo girando --}}
            <div class="float-el relative w-28 h-28">
                <div class="spin-logo w-full h-full rounded-3xl bg-white shadow-xl border border-blue-100 flex items-center justify-center">
                    {{-- Reemplaza con tu logo: <img src="{{ asset('img/logo.png') }}" class="w-16 h-16 object-contain"> --}}
                    <svg class="w-16 h-16 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Vales agregados</h1>
            <p class="text-gray-400 text-sm font-medium">Gracias por su preferencia</p>
        </div>

        {{-- Dots animados --}}
        <div class="flex gap-2">
            <div class="w-2 h-2 rounded-full bg-blue-300 animate-bounce" style="animation-delay:0s"></div>
            <div class="w-2 h-2 rounded-full bg-blue-400 animate-bounce" style="animation-delay:.15s"></div>
            <div class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay:.3s"></div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         PANTALLA 2: CALIFICAR
    ════════════════════════════════════════ --}}
    <div id="screen-rate" class="screen hidden-screen flex flex-col items-center justify-between p-6 gap-4" style="max-width:480px; margin:auto;">

        {{-- Header --}}
        <div class="w-full fade-in-up text-center pt-2" style="animation-delay:.05s; opacity:0;">
            <div class="inline-flex items-center gap-2 bg-blue-600 text-white text-xs font-black px-4 py-1.5 rounded-full mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Venta registrada
            </div>
            <h2 class="text-xl font-black text-gray-800">¡Listo, <span id="client-name">cliente</span>!</h2>
            <p class="text-gray-400 text-sm mt-1">Tu pedido está confirmado</p>
        </div>

        {{-- Resumen de venta --}}
        <div class="w-full fade-in-up" style="animation-delay:.15s; opacity:0;">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="bg-blue-600 px-5 py-3 flex items-center justify-between">
                    <span class="text-xs font-black text-blue-200 uppercase tracking-widest">Folio</span>
                    <span class="text-base font-black text-white font-mono" id="sale-folio">#—</span>
                </div>
                <div class="p-4 grid grid-cols-3 gap-3 text-center">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Subtotal</p>
                        <p class="text-base font-black text-gray-700">$<span id="sale-subtotal">0.00</span></p>
                    </div>
                    <div class="border-x border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">IVA</p>
                        <p class="text-base font-black text-gray-700">$<span id="sale-iva">0.00</span></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</p>
                        <p class="text-lg font-black text-blue-600">$<span id="sale-total">0.00</span></p>
                    </div>
                </div>
                <div class="px-4 pb-3 flex items-center justify-between border-t border-gray-50 pt-2">
                    <span class="text-[11px] font-bold text-gray-400" id="sale-tipo"></span>
                    <span class="text-[11px] font-black text-blue-500 bg-blue-50 px-2 py-0.5 rounded-full" id="sale-vales"></span>
                </div>
            </div>
        </div>

        {{-- Pregunta + caritas --}}
        <div class="w-full fade-in-up text-center" style="animation-delay:.25s; opacity:0;">
            <p class="text-base font-black text-gray-700 mb-1">¿Cómo fue tu experiencia?</p>
            <p class="text-xs text-gray-400 mb-5">Toca una carita para calificar</p>

            <div class="flex items-end justify-center gap-3 sm:gap-5">
                {{-- 1 - Muy malo --}}
                <button class="face-btn ripple-wrap flex flex-col items-center gap-1.5" onclick="rate(1)">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-red-50 border-2 border-red-100 flex items-center justify-center text-3xl sm:text-4xl hover:border-red-300 transition-colors">
                        😡
                    </div>
                    <span class="text-[9px] font-black text-red-400 uppercase tracking-wide">Pésimo</span>
                </button>

                {{-- 2 - Malo --}}
                <button class="face-btn ripple-wrap flex flex-col items-center gap-1.5" onclick="rate(2)">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-orange-50 border-2 border-orange-100 flex items-center justify-center text-3xl sm:text-4xl hover:border-orange-300 transition-colors">
                        😕
                    </div>
                    <span class="text-[9px] font-black text-orange-400 uppercase tracking-wide">Malo</span>
                </button>

                {{-- 3 - Regular --}}
                <button class="face-btn ripple-wrap flex flex-col items-center gap-1.5" onclick="rate(3)">
                    <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl bg-yellow-50 border-2 border-yellow-200 flex items-center justify-center text-4xl sm:text-5xl hover:border-yellow-400 transition-colors" style="width:4.5rem;height:4.5rem;">
                        😐
                    </div>
                    <span class="text-[9px] font-black text-yellow-500 uppercase tracking-wide">Regular</span>
                </button>

                {{-- 4 - Bueno --}}
                <button class="face-btn ripple-wrap flex flex-col items-center gap-1.5" onclick="rate(4)">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-lime-50 border-2 border-lime-100 flex items-center justify-center text-3xl sm:text-4xl hover:border-lime-400 transition-colors">
                        😊
                    </div>
                    <span class="text-[9px] font-black text-lime-500 uppercase tracking-wide">Bueno</span>
                </button>

                {{-- 5 - Excelente --}}
                <button class="face-btn ripple-wrap flex flex-col items-center gap-1.5" onclick="rate(5)">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-green-50 border-2 border-green-100 flex items-center justify-center text-3xl sm:text-4xl hover:border-green-300 transition-colors">
                        🤩
                    </div>
                    <span class="text-[9px] font-black text-green-500 uppercase tracking-wide">¡Excelente!</span>
                </button>
            </div>
        </div>

        {{-- Timer ring --}}
        <div class="fade-in-up flex flex-col items-center gap-2" style="animation-delay:.35s; opacity:0;">
            <div class="relative w-12 h-12">
                <svg class="w-12 h-12 -rotate-90" viewBox="0 0 44 44">
                    <circle cx="22" cy="22" r="18" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                    <circle id="timer-circle" cx="22" cy="22" r="18" fill="none"
                            stroke="#3b82f6" stroke-width="3"
                            stroke-dasharray="113"
                            stroke-dashoffset="0"
                            stroke-linecap="round"/>
                </svg>
                <span id="timer-text" class="absolute inset-0 flex items-center justify-center text-sm font-black text-blue-500">120</span>
            </div>
            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">seg restantes</p>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         PANTALLA 3: GRACIAS
    ════════════════════════════════════════ --}}
    <div id="screen-thanks" class="screen hidden-screen flex flex-col items-center justify-center gap-6 p-8 text-center">
        <div class="pop w-24 h-24 rounded-3xl bg-green-50 border-2 border-green-100 flex items-center justify-center text-6xl" style="opacity:0;">
            🎉
        </div>
        <div class="fade-in-up space-y-2" style="animation-delay:.2s; opacity:0;">
            <h2 class="text-2xl font-black text-gray-800">¡Gracias por tu opinión!</h2>
            <p class="text-gray-400 text-sm">Tu calificación nos ayuda a mejorar</p>
        </div>
        <div class="fade-in-up flex gap-1" style="animation-delay:.4s; opacity:0;" id="stars-display"></div>
        <div class="fade-in-up" style="animation-delay:.6s; opacity:0;">
            <p class="text-blue-600 font-black text-sm" id="thanks-message">Hasta pronto</p>
        </div>
    </div>

    <script>
    // ── Estado ──────────────────────────────────────────────────────────────
    const state = {
        currentSaleId : null,
        timerInterval : null,
        timerSeconds  : 120,
        pollInterval  : null,
        csrfToken     : document.querySelector('meta[name="csrf-token"]').content,
    };

    const THANKS_MESSAGES = {
        1: 'Lamentamos tu experiencia. Mejoraremos.',
        2: 'Gracias, trabajaremos para mejorar.',
        3: 'Gracias por tu comentario.',
        4: 'Nos alegra que haya sido una buena experiencia.',
        5: '¡Nos encanta saber que quedaste satisfecho!',
    };

    // ── Helpers de pantalla ──────────────────────────────────────────────────
    function showScreen(id) {
        ['screen-sleep','screen-rate','screen-thanks'].forEach(s => {
            const el = document.getElementById(s);
            el.classList.toggle('hidden-screen', s !== id);
            el.classList.toggle('active-screen', s === id);
        });

        // Re-trigger animations en la pantalla activa
        document.querySelectorAll(`#${id} .fade-in-up, #${id} .pop`).forEach(el => {
            el.style.opacity = '0';
            el.style.animation = 'none';
            requestAnimationFrame(() => {
                el.style.animation = '';
            });
        });
    }

    // ── Polling — consulta cada 4s si hay venta pendiente ───────────────────
    function startPolling() {
        state.pollInterval = setInterval(async () => {
            try {
                const res  = await fetch('/calificacion/check');
                const data = await res.json();
                if (data.pending && data.sale_id !== state.currentSaleId) {
                    loadSale(data);
                }
            } catch (e) { /* sin conexión, sigue esperando */ }
        }, 4000);
    }

    function loadSale(data) {
        state.currentSaleId = data.sale_id;

        // Rellenar datos
        document.getElementById('client-name').textContent = data.client;
        document.getElementById('sale-folio').textContent  = '#' + data.folio;
        document.getElementById('sale-subtotal').textContent = data.subtotal;
        document.getElementById('sale-iva').textContent      = data.iva;
        document.getElementById('sale-total').textContent    = data.total;
        document.getElementById('sale-tipo').textContent     = data.tipo_venta === 'Credito' ? '📋 Crédito 15 días' : '💳 Contado';
        document.getElementById('sale-vales').textContent    = data.vales + ' vale(s)';

        showScreen('screen-rate');
        startTimer();
    }

    // ── Timer ────────────────────────────────────────────────────────────────
    function startTimer() {
        clearInterval(state.timerInterval);
        state.timerSeconds = 120;
        updateTimerUI(120);

        state.timerInterval = setInterval(() => {
            state.timerSeconds--;
            updateTimerUI(state.timerSeconds);
            if (state.timerSeconds <= 0) {
                clearInterval(state.timerInterval);
                skipRating();
            }
        }, 1000);
    }

    function updateTimerUI(seconds) {
        document.getElementById('timer-text').textContent = seconds;
        // dash offset: 113 = circunferencia completa
        const offset = 113 - (113 * seconds / 120);
        document.getElementById('timer-circle').style.strokeDashoffset = offset;

        // Color cambia a rojo en los últimos 20s
        document.getElementById('timer-circle').style.stroke = seconds <= 20 ? '#ef4444' : '#3b82f6';
        document.getElementById('timer-text').style.color    = seconds <= 20 ? '#ef4444' : '#3b82f6';
    }

    function stopTimer() {
        clearInterval(state.timerInterval);
    }

    // ── Calificar ────────────────────────────────────────────────────────────
    async function rate(score) {
        if (!state.currentSaleId) return;
        stopTimer();

        // Feedback visual en el botón
        document.querySelectorAll('.face-btn').forEach((btn, i) => {
            btn.style.transform = i + 1 === score ? 'scale(1.25)' : 'scale(0.85)';
            btn.style.opacity   = i + 1 === score ? '1' : '0.3';
        });

        try {
            await fetch('/calificacion/rate', {
                method : 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': state.csrfToken },
                body   : JSON.stringify({ sale_id: state.currentSaleId, calificacion: score }),
            });
        } catch(e) { /* continúa aunque falle la red */ }

        showThanks(score);
    }

    async function skipRating() {
        if (!state.currentSaleId) return;
        try {
            await fetch('/calificacion/skip', {
                method : 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': state.csrfToken },
                body   : JSON.stringify({ sale_id: state.currentSaleId }),
            });
        } catch(e) {}
        goToSleep();
    }

    // ── Pantalla de gracias ──────────────────────────────────────────────────
    function showThanks(score) {
        const stars = document.getElementById('stars-display');
        stars.innerHTML = '';
        for (let i = 1; i <= 5; i++) {
            const s = document.createElement('span');
            s.className = 'text-2xl transition-all';
            s.style.opacity = i <= score ? '1' : '0.2';
            s.textContent = '⭐';
            stars.appendChild(s);
        }
        document.getElementById('thanks-message').textContent = THANKS_MESSAGES[score] || 'Hasta pronto';

        showScreen('screen-thanks');
        state.currentSaleId = null;

        // Volver al reposo en 4s
        setTimeout(goToSleep, 4000);
    }

    function goToSleep() {
        stopTimer();
        state.currentSaleId = null;
        // Resetear botones
        document.querySelectorAll('.face-btn').forEach(btn => {
            btn.style.transform = '';
            btn.style.opacity   = '';
        });
        showScreen('screen-sleep');
    }

    // ── Init ─────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        showScreen('screen-sleep');
        startPolling();

        // Mantener pantalla encendida (si el navegador lo soporta)
        if ('wakeLock' in navigator) {
            navigator.wakeLock.request('screen').catch(() => {});
        }
    });
    </script>
</body>
</html>