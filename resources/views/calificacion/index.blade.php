<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vales Agregados - Ranking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { -webkit-tap-highlight-color: transparent; user-select: none; box-sizing: border-box; }
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: linear-gradient(135deg, #eef1f8 0%, #ffffff 50%, #eef1f8 100%); }

        @keyframes pulse-glow { 0%,100%{opacity:.4;transform:scale(1)} 50%{opacity:.8;transform:scale(1.1)} }
        @keyframes fade-up    { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pop-in     { 0%{transform:scale(.75);opacity:0} 70%{transform:scale(1.08)} 100%{transform:scale(1);opacity:1} }
        @keyframes sheet-up   { from{transform:translateY(100%)} to{transform:translateY(0)} }
        @keyframes fade-in    { from{opacity:0} to{opacity:1} }
        @keyframes logo-breathe {
            0%,100% { transform:scale(1);    box-shadow:0 8px 40px rgba(18,31,72,.15); }
            50%      { transform:scale(1.04); box-shadow:0 16px 56px rgba(18,31,72,.28); }
        }
        @keyframes ring-spin { to { transform:rotate(360deg); } }
        @keyframes star-pop  { 0%{transform:scale(0) rotate(-30deg);opacity:0} 70%{transform:scale(1.2)} 100%{transform:scale(1);opacity:1} }

        .pulse   { animation: pulse-glow 2.8s ease-in-out infinite; }
        .fade-up { animation: fade-up .45s ease forwards; opacity: 0; }
        .pop     { animation: pop-in .4s cubic-bezier(.34,1.56,.64,1) forwards; opacity: 0; }

        .logo-card {
            animation: logo-breathe 3.8s ease-in-out infinite;
            width:5rem; height:5rem; border-radius:20px;
            background:#fff; box-shadow:0 8px 40px rgba(18,31,72,.18);
            border:1px solid rgba(18,31,72,.12);
            display:flex; align-items:center; justify-content:center;
            overflow:hidden; padding:12px;
        }
        .logo-card img { width:100%; height:100%; object-fit:contain; }
        .ring-outer {
            position:absolute; width:160px; height:160px; border-radius:50%;
            border:2px dashed rgba(18,31,72,.2);
            animation:ring-spin 18s linear infinite;
        }
        .ring-inner {
            position:absolute; width:130px; height:130px; border-radius:50%;
            border:2px dashed rgba(18,31,72,.12);
            animation:ring-spin 12s linear infinite reverse;
        }

        .screen { position:fixed; inset:0; display:flex; transition:opacity .35s ease, transform .35s ease; }
        .screen.off { opacity:0; pointer-events:none; transform:scale(.96); }
        .screen.on  { opacity:1; pointer-events:all;  transform:scale(1); }

        #backdrop {
            position:fixed; inset:0; z-index:50;
            background:rgba(10,18,40,.55); backdrop-filter:blur(10px);
            display:none; align-items:flex-end; animation:fade-in .2s ease;
        }
        #backdrop.open { display:flex; }
        #sheet {
            width:100%; max-height:88dvh; background:#fff;
            border-radius:26px 26px 0 0;
            display:flex; flex-direction:column; overflow:hidden;
            animation:sheet-up .3s cubic-bezier(.34,1.1,.64,1);
        }

        .scroll::-webkit-scrollbar { width:3px; }
        .scroll::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:10px; }

        .rate-portrait  { display:flex; }
        .rate-landscape { display:none; }
        @media (orientation:landscape) and (max-height:600px) {
            .rate-portrait  { display:none; }
            .rate-landscape { display:flex; }
        }

        .bg-brand    { background-color:#121f48; }
        .text-brand  { color:#121f48; }

        /* ── Botón de calificación ── */
        .rate-opt {
            display:flex; flex-direction:column; align-items:center; gap:8px;
            cursor:pointer; border:none; background:none; padding:0;
            transition:transform .18s cubic-bezier(.34,1.56,.64,1), opacity .18s;
        }
        .rate-opt:active { transform:scale(.82) !important; }
        .rate-box {
            border-radius:18px; border:2px solid;
            display:flex; align-items:center; justify-content:center;
            flex-direction:column; gap:3px;
            transition:border-color .2s, box-shadow .2s;
            padding:8px 10px;
        }
        .rate-opt:hover .rate-box { box-shadow:0 6px 24px rgba(245,158,11,.3); }

        /* Estrella SVG animada en pantalla gracias */
        .ty-star { animation: star-pop .4s cubic-bezier(.34,1.56,.64,1) both; opacity:0; }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════
         PANTALLA 1 — REPOSO
    ══════════════════════════════════════ --}}
    <div id="s-sleep" class="screen on flex-col items-center justify-center gap-6 p-8 text-center">
        <div class="relative flex items-center justify-center" style="width:200px;height:200px;">
            <div class="pulse absolute rounded-full" style="width:200px;height:200px;background:rgba(18,31,72,.07);"></div>
            <div class="pulse absolute rounded-full" style="width:150px;height:150px;background:rgba(18,31,72,.1);animation-delay:.7s;"></div>
            <div class="ring-outer"></div>
            <div class="ring-inner"></div>
            <div class="logo-card relative z-10">
                <img src="{{ asset('img/logo-solo.png') }}" alt="Logo">
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Vales Agregados</h1>
            <p class="text-gray-400 text-sm mt-1">Gracias por su preferencia</p>
        </div>
        <div class="flex gap-2.5">
            <div class="w-2 h-2 rounded-full animate-bounce" style="background:#121f48;opacity:.3;animation-delay:0s"></div>
            <div class="w-2 h-2 rounded-full animate-bounce" style="background:#121f48;opacity:.5;animation-delay:.18s"></div>
            <div class="w-2 h-2 rounded-full animate-bounce" style="background:#121f48;opacity:.8;animation-delay:.36s"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         PANTALLA 2 — CALIFICAR
    ══════════════════════════════════════ --}}
    <div id="s-rate" class="screen off">

        {{-- PORTRAIT --}}
        <div class="rate-portrait w-full h-full flex-col items-center justify-between p-5 overflow-y-auto scroll"
             style="max-width:480px;margin:auto;">

            <div class="fade-up w-full text-center pt-1" style="animation-delay:.05s">
                <div class="inline-flex items-center gap-2 text-white text-xs font-black px-4 py-1.5 rounded-full mb-2 bg-brand">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Venta registrada
                </div>
                <h2 class="text-lg font-black text-gray-800">¡Listo, <span class="js-client">cliente</span>!</h2>
                <p class="text-gray-400 text-xs mt-0.5">Tu pedido está confirmado</p>
            </div>

            <div class="fade-up w-full" style="animation-delay:.12s">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 flex items-center justify-between bg-brand">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.5);">Folio</p>
                            <p class="text-base font-black text-white font-mono js-folio">#—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.5);">Total</p>
                            <p class="text-xl font-black text-white">$<span class="js-total">—</span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 divide-x divide-gray-50 border-b border-gray-50">
                        <div class="p-3 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Subtotal</p>
                            <p class="text-sm font-black text-gray-700">$<span class="js-subtotal">—</span></p>
                        </div>
                        <div class="p-3 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">IVA</p>
                            <p class="text-sm font-black text-gray-700">$<span class="js-iva">—</span></p>
                        </div>
                        <div class="p-3 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Vales</p>
                            <p class="text-sm font-black js-vales text-brand">—</p>
                        </div>
                    </div>
                    <div class="px-4 py-2.5 flex items-center justify-between">
                        <span class="text-[11px] font-bold text-gray-400 js-tipo"></span>
                        <button onclick="openDetalles()"
                                class="flex items-center gap-1.5 font-black text-xs px-3 py-1.5 rounded-xl transition-all active:scale-95"
                                style="color:#121f48;background:#eef1f8;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver detalles →
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── 3 OPCIONES ESTRELLA portrait ── --}}
            <div class="fade-up w-full text-center" style="animation-delay:.2s">
                <p class="text-sm font-black text-gray-800 mb-0.5">¿Cómo fue tu experiencia?</p>
                <p class="text-xs text-gray-400 mb-5">Toca para calificar</p>
                <div class="flex items-end justify-center gap-5">

                    <button class="rate-opt" onclick="rate(1)" data-score="1">
                        <div class="rate-box" style="background:#fef2f2;border-color:#fecaca;width:3.6rem;height:3.6rem;">
                            <div id="p-opt-1" class="flex gap-0.5"></div>
                        </div>
                        <span class="text-[9px] font-black text-red-400 uppercase tracking-wide">Malo</span>
                    </button>

                    <button class="rate-opt" onclick="rate(2)" data-score="2">
                        <div class="rate-box" style="background:#fffbeb;border-color:#fde68a;width:4.2rem;height:4.2rem;">
                            <div id="p-opt-2" class="flex gap-0.5"></div>
                        </div>
                        <span class="text-[9px] font-black text-yellow-500 uppercase tracking-wide">Regular</span>
                    </button>

                    <button class="rate-opt" onclick="rate(3)" data-score="3">
                        <div class="rate-box" style="background:#f0fdf4;border-color:#bbf7d0;width:3.6rem;height:3.6rem;">
                            <div id="p-opt-3" class="flex gap-0.5"></div>
                        </div>
                        <span class="text-[9px] font-black text-green-500 uppercase tracking-wide">¡Genial!</span>
                    </button>

                </div>
            </div>

            <div class="fade-up flex flex-col items-center gap-1 pb-1" style="animation-delay:.28s">
                <div class="relative" style="width:42px;height:42px;">
                    <svg style="width:42px;height:42px;" class="-rotate-90" viewBox="0 0 44 44">
                        <circle cx="22" cy="22" r="18" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <circle id="tc" cx="22" cy="22" r="18" fill="none" stroke="#121f48" stroke-width="3"
                                stroke-dasharray="113" stroke-dashoffset="0" stroke-linecap="round"/>
                    </svg>
                    <span id="tt" class="absolute inset-0 flex items-center justify-center text-xs font-black" style="color:#121f48;">120</span>
                </div>
                <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest">seg</p>
            </div>
        </div>

        {{-- LANDSCAPE --}}
        <div class="rate-landscape w-full h-full items-stretch p-3 gap-3 overflow-hidden"
             style="max-width:900px;margin:auto;">

            <div class="flex flex-col justify-center gap-3 flex-1 min-w-0">
                <div class="fade-up flex items-center gap-3" style="animation-delay:.05s">
                    <div class="inline-flex items-center gap-1.5 text-white text-[10px] font-black px-3 py-1.5 rounded-full shrink-0 bg-brand">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Venta registrada
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-800 leading-tight">¡Listo, <span class="js-client">cliente</span>!</h2>
                        <p class="text-gray-400 text-[11px]">Tu pedido está confirmado</p>
                    </div>
                </div>

                <div class="fade-up bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" style="animation-delay:.12s">
                    <div class="px-4 py-2.5 flex items-center justify-between bg-brand">
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.5);">Folio</p>
                            <p class="text-sm font-black text-white font-mono js-folio">#—</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[8px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.5);">Total</p>
                            <p class="text-lg font-black text-white">$<span class="js-total">—</span></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 divide-x divide-gray-50 border-b border-gray-50">
                        <div class="py-2 px-3 text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Subtotal</p>
                            <p class="text-xs font-black text-gray-700">$<span class="js-subtotal">—</span></p>
                        </div>
                        <div class="py-2 px-3 text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">IVA</p>
                            <p class="text-xs font-black text-gray-700">$<span class="js-iva">—</span></p>
                        </div>
                        <div class="py-2 px-3 text-center">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Vales</p>
                            <p class="text-xs font-black js-vales text-brand">—</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 js-tipo"></span>
                        <button onclick="openDetalles()"
                                class="flex items-center gap-1 font-black text-[10px] px-2.5 py-1.5 rounded-xl transition-all active:scale-95"
                                style="color:#121f48;background:#eef1f8;">
                            Ver detalles →
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-px bg-gray-100 self-stretch mx-1 shrink-0"></div>

            <div class="flex flex-col justify-center items-center gap-4 flex-1 min-w-0">
                <div class="fade-up text-center" style="animation-delay:.18s">
                    <p class="text-sm font-black text-gray-800 mb-0.5">¿Cómo fue tu experiencia?</p>
                    <p class="text-[11px] text-gray-400 mb-4">Toca para calificar</p>
                    <div class="flex items-end justify-center gap-4">

                        <button class="rate-opt" onclick="rate(1)" data-score="1">
                            <div class="rate-box" style="background:#fef2f2;border-color:#fecaca;width:3.2rem;height:3.2rem;">
                                <div id="l-opt-1" class="flex gap-0.5"></div>
                            </div>
                            <span class="text-[8px] font-black text-red-400 uppercase">Malo</span>
                        </button>

                        <button class="rate-opt" onclick="rate(2)" data-score="2">
                            <div class="rate-box" style="background:#fffbeb;border-color:#fde68a;width:3.8rem;height:3.8rem;">
                                <div id="l-opt-2" class="flex gap-0.5"></div>
                            </div>
                            <span class="text-[8px] font-black text-yellow-500 uppercase">Regular</span>
                        </button>

                        <button class="rate-opt" onclick="rate(3)" data-score="3">
                            <div class="rate-box" style="background:#f0fdf4;border-color:#bbf7d0;width:3.2rem;height:3.2rem;">
                                <div id="l-opt-3" class="flex gap-0.5"></div>
                            </div>
                            <span class="text-[8px] font-black text-green-500 uppercase">¡Genial!</span>
                        </button>

                    </div>
                </div>

                <div class="fade-up flex items-center gap-3 bg-white rounded-2xl px-5 py-3 border border-gray-100 shadow-sm" style="animation-delay:.25s">
                    <div class="relative" style="width:44px;height:44px;">
                        <svg style="width:44px;height:44px;" class="-rotate-90" viewBox="0 0 44 44">
                            <circle cx="22" cy="22" r="18" fill="none" stroke="#e5e7eb" stroke-width="3.5"/>
                            <circle id="tc2" cx="22" cy="22" r="18" fill="none" stroke="#121f48" stroke-width="3.5"
                                    stroke-dasharray="113" stroke-dashoffset="0" stroke-linecap="round"/>
                        </svg>
                        <span id="tt2" class="absolute inset-0 flex items-center justify-center text-xs font-black" style="color:#121f48;">120</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-700 leading-none" id="tt2-label" style="color:#121f48;">120</p>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-wide mt-0.5">seg restantes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         PANTALLA 3 — GRACIAS
    ══════════════════════════════════════ --}}
    <div id="s-thanks" class="screen off flex-col items-center justify-center gap-6 p-8 text-center">
        <div class="pop w-24 h-24 rounded-3xl bg-green-50 border-2 border-green-100 flex items-center justify-center text-6xl" style="animation-delay:.05s">🎉</div>
        <div class="fade-up space-y-1.5" style="animation-delay:.2s">
            <h2 class="text-2xl font-black text-gray-800">¡Gracias por tu opinión!</h2>
            <p class="text-gray-400 text-sm">Tu calificación nos ayuda a mejorar</p>
        </div>
        {{-- Estrellas grandes animadas --}}
        <div id="thanks-stars" class="flex justify-center gap-2" style="animation-delay:.3s;"></div>
        <div class="fade-up" style="animation-delay:.45s">
            <p class="font-black text-sm text-brand" id="thanks-msg">Hasta pronto</p>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         MODAL — DETALLES
    ══════════════════════════════════════ --}}
    <div id="backdrop">
        <div id="sheet">
            <div class="flex justify-center pt-3 pb-1 shrink-0">
                <div class="w-9 h-1 rounded-full bg-gray-200"></div>
            </div>

            <div class="shrink-0 mx-4 mb-3 rounded-2xl px-5 py-4 flex items-start justify-between gap-4 bg-brand">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest mb-0.5" style="color:rgba(255,255,255,.5);">Detalle completo</p>
                    <p class="text-base font-black text-white font-mono" id="d-folio">#—</p>
                    <p class="text-xs font-bold mt-0.5" style="color:rgba(255,255,255,.6);" id="d-client">—</p>
                </div>
                <button onclick="closeDetalles()"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-white transition-all shrink-0 active:scale-90"
                        style="background:rgba(255,255,255,.15);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto scroll px-4 pb-4 space-y-4">
                <div class="grid grid-cols-2 gap-2.5">
                    <div class="col-span-2 rounded-2xl px-5 py-3 flex items-center justify-between bg-brand">
                        <p class="text-xs font-black" style="color:rgba(255,255,255,.6);">Total a pagar</p>
                        <p class="text-2xl font-black text-white">$<span id="d-total">—</span></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Subtotal</p>
                        <p class="text-base font-black text-gray-700">$<span id="d-subtotal">—</span></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">IVA 16%</p>
                        <p class="text-base font-black text-gray-700">$<span id="d-iva">—</span></p>
                    </div>
                </div>

                <div class="flex gap-2.5">
                    <div class="flex-1 bg-gray-50 rounded-xl px-4 py-2.5 border border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-black text-gray-400 uppercase">Pago</span>
                        <span class="text-xs font-black text-brand" id="d-tipo">—</span>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-xl px-4 py-2.5 border border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-black text-gray-400 uppercase">Descuento</span>
                        <span class="text-xs font-black text-red-500" id="d-desc">$0.00</span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2.5">
                        <div class="w-1 h-4 rounded-full bg-brand"></div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Materiales del pedido</p>
                    </div>
                    <div id="d-mats" class="space-y-2"></div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2.5">
                        <div class="w-1 h-4 rounded-full bg-brand"></div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Vales generados</p>
                    </div>
                    <div id="d-vales" class="space-y-2"></div>
                </div>
            </div>

            <div class="shrink-0 px-4 py-3.5 border-t border-gray-100 bg-white">
                <button onclick="closeDetalles()"
                        class="w-full text-white font-black text-sm py-3 rounded-2xl transition-all active:scale-[.98] bg-brand hover:opacity-90">
                    Cerrar detalles
                </button>
            </div>
        </div>
    </div>

    <script>
    const S = {
        saleId: null, data: null, timer: null, secs: 120,
        csrf  : document.querySelector('meta[name="csrf-token"]').content,
    };

    const MSG = {
        1: 'Lamentamos tu experiencia. ¡Mejoraremos para ti!',
        2: 'Gracias por tu comentario.',
        3: '¡Nos encanta saber que quedaste satisfecho!',
    };

    // ─────────────────────────────────────────────────────────────
    //  renderStars(value, total, sizePx)
    //  Genera `total` estrellas SVG. Cada estrella se llena de
    //  izquierda a derecha de forma proporcional (soporta decimales).
    //  value=1.7 → 1 llena + 1 a 70% + 1 vacía
    // ─────────────────────────────────────────────────────────────
    function renderStars(value, total, sizePx) {
        const sz  = sizePx || 20;
        const uid = 'sr' + Math.random().toString(36).slice(2, 7);
        let html  = '';
        for (let i = 0; i < total; i++) {
            const pct = Math.round(Math.min(Math.max(value - i, 0), 1) * 100);
            const gid = uid + i;
            html += `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"
                          style="display:inline-block;flex-shrink:0;vertical-align:middle;">
                <defs>
                    <linearGradient id="${gid}" x1="0" x2="1" y1="0" y2="0">
                        <stop offset="${pct}%" stop-color="#f59e0b"/>
                        <stop offset="${pct}%" stop-color="#d1d5db"/>
                    </linearGradient>
                </defs>
                <path fill="url(#${gid})"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88
                         L12 17.77l-6.18 3.25L7 14.14 2 9.27
                         l6.91-1.01L12 2z"/>
            </svg>`;
        }
        return html;
    }

    // Inicializa las estrellas fijas de los 3 botones
    document.addEventListener('DOMContentLoaded', () => {
        // Portrait
        document.getElementById('p-opt-1').innerHTML = renderStars(1, 3, 15);
        document.getElementById('p-opt-2').innerHTML = renderStars(2, 3, 15);
        document.getElementById('p-opt-3').innerHTML = renderStars(3, 3, 15);
        // Landscape
        document.getElementById('l-opt-1').innerHTML = renderStars(1, 3, 13);
        document.getElementById('l-opt-2').innerHTML = renderStars(2, 3, 13);
        document.getElementById('l-opt-3').innerHTML = renderStars(3, 3, 13);

        show('s-sleep');
        if ('wakeLock' in navigator) navigator.wakeLock.request('screen').catch(() => {});
    });

    // ─── Navegación de pantallas ───────────────────────────────────
    function show(id) {
        ['s-sleep','s-rate','s-thanks'].forEach(s => {
            const el = document.getElementById(s);
            el.classList.toggle('off', s !== id);
            el.classList.toggle('on',  s === id);
        });
        document.querySelectorAll(`#${id} .fade-up, #${id} .pop`).forEach(el => {
            el.style.opacity = '0'; el.style.animation = 'none';
            requestAnimationFrame(() => { el.style.animation = ''; });
        });
    }

    function fill(sel, val) { document.querySelectorAll(sel).forEach(el => el.textContent = val); }

    // ─── Polling de ventas pendientes ─────────────────────────────
    setInterval(async () => {
        try {
            const r = await fetch('/calificacion/check');
            const d = await r.json();
            if (d.pending && d.sale_id !== S.saleId) loadSale(d);
        } catch(e) {}
    }, 4000);

    function loadSale(d) {
        S.saleId = d.sale_id; S.data = d;
        fill('.js-client',   d.client);
        fill('.js-folio',    '#' + d.folio);
        fill('.js-total',    d.total);
        fill('.js-subtotal', d.subtotal);
        fill('.js-iva',      d.iva);
        fill('.js-vales',    d.vales + ' vale(s)');
        fill('.js-tipo',     d.tipo_venta === 'Credito' ? '📋 Crédito 15 días' : '💳 Contado');
        show('s-rate');
        startTimer();
    }

    // ─── Modal detalles ───────────────────────────────────────────
    function openDetalles() {
        const d = S.data; if (!d) return;
        stopTimer();
        document.getElementById('d-folio').textContent    = '#' + d.folio;
        document.getElementById('d-client').textContent   = d.client;
        document.getElementById('d-total').textContent    = d.total;
        document.getElementById('d-subtotal').textContent = d.subtotal;
        document.getElementById('d-iva').textContent      = d.iva;
        document.getElementById('d-tipo').textContent     = d.tipo_venta === 'Credito' ? '📋 Crédito 15d' : '💳 Contado';
        document.getElementById('d-desc').textContent     = d.descuento && parseFloat(d.descuento) > 0 ? '-$'+d.descuento : '$0.00';

        const mEl = document.getElementById('d-mats');
        mEl.innerHTML = '';
        (d.materials_detail || []).forEach(m => {
            mEl.innerHTML += `
            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0" style="background:#eef1f8;">
                        <svg class="w-4 h-4" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-gray-800 truncate">${m.nombre}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${parseFloat(m.cantidad).toFixed(2)} ${m.unidad}</p>
                    </div>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full shrink-0" style="background:#eef1f8;color:#121f48;">${m.unidad}</span>
                </div>
            </div>`;
        });
        if (!mEl.innerHTML) mEl.innerHTML = `<p class="text-xs text-center text-gray-300 font-black py-3">Sin materiales</p>`;

        const vEl = document.getElementById('d-vales');
        vEl.innerHTML = '';
        (d.vales_detail || []).forEach((v, i) => {
            const badge = v.estatus === 'Vigente' ? 'background:#dcfce7;color:#16a34a;' : 'background:#f3f4f6;color:#9ca3af;';
            vEl.innerHTML += `
            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black text-gray-500 shrink-0" style="background:#f3f4f6;">
                        ${String(i+1).padStart(2,'0')}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-gray-800 font-mono">${v.folio_vale}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">${v.unidad ?? 'Sin unidad asignada'}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-black text-gray-700">${parseFloat(v.cantidad).toFixed(2)} <span class="text-[10px] font-medium text-gray-400">${v.unidad_medida}</span></p>
                        <span class="text-[9px] font-black px-2 py-0.5 rounded-full" style="${badge}">${v.estatus}</span>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400">
                        <span class="inline-block w-1.5 h-1.5 rounded-full mr-1.5" style="background:#121f48;"></span>
                        ${v.material}
                    </p>
                </div>
            </div>`;
        });
        if (!vEl.innerHTML) vEl.innerHTML = `<p class="text-xs text-center text-gray-300 font-black py-3">Sin vales</p>`;

        document.getElementById('backdrop').classList.add('open');
    }

    function closeDetalles() {
        document.getElementById('backdrop').classList.remove('open');
        if (S.saleId) resumeTimer();
    }

    // ─── Timer ───────────────────────────────────────────────────
    function startTimer()  { clearInterval(S.timer); S.secs = 120; timerUI(120); S.timer = setInterval(tick, 1000); }
    function resumeTimer() { clearInterval(S.timer); S.timer = setInterval(tick, 1000); }
    function stopTimer()   { clearInterval(S.timer); }

    function tick() {
        S.secs--;
        timerUI(S.secs);
        if (S.secs <= 0) { clearInterval(S.timer); skipRating(); }
    }

    function timerUI(s) {
        const c   = s <= 20 ? '#ef4444' : '#121f48';
        const off = 113 - (113 * s / 120);
        ['tc','tc2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.style.strokeDashoffset = off; el.style.stroke = c; }
        });
        ['tt','tt2'].forEach(id => {
            const el = document.getElementById(id);
            if (el) { el.textContent = s; el.style.color = c; }
        });
        const lbl = document.getElementById('tt2-label');
        if (lbl) { lbl.textContent = s; lbl.style.color = c; }
    }

    // ─── Calificar ───────────────────────────────────────────────
    async function rate(score) {
        if (!S.saleId) return;
        stopTimer(); closeDetalles();

        document.querySelectorAll('.rate-opt').forEach(b => {
            const sel = parseInt(b.dataset.score) === score;
            b.style.transform = sel ? 'scale(1.3)' : 'scale(0.72)';
            b.style.opacity   = sel ? '1' : '0.18';
        });

        try {
            await fetch('/calificacion/rate', {
                method : 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':S.csrf},
                body   : JSON.stringify({sale_id:S.saleId, calificacion:score}),
            });
        } catch(e) {}
        showThanks(score);
    }

    async function skipRating() {
        if (!S.saleId) return;
        closeDetalles();
        try {
            await fetch('/calificacion/skip', {
                method : 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':S.csrf},
                body   : JSON.stringify({sale_id:S.saleId}),
            });
        } catch(e) {}
        goSleep();
    }

    function showThanks(score) {
        // Estrellas grandes con animación escalonada
        const container = document.getElementById('thanks-stars');
        container.innerHTML = '';
        for (let i = 0; i < 3; i++) {
            const pct = Math.round(Math.min(Math.max(score - i, 0), 1) * 100);
            const gid = 'tsg' + i;
            const wrap = document.createElement('div');
            wrap.className = 'ty-star';
            wrap.style.animationDelay = (i * 0.12) + 's';
            wrap.innerHTML = `<svg width="52" height="52" viewBox="0 0 24 24">
                <defs>
                    <linearGradient id="${gid}" x1="0" x2="1" y1="0" y2="0">
                        <stop offset="${pct}%" stop-color="#f59e0b"/>
                        <stop offset="${pct}%" stop-color="#e5e7eb"/>
                    </linearGradient>
                </defs>
                <path fill="url(#${gid})"
                      d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88
                         L12 17.77l-6.18 3.25L7 14.14 2 9.27
                         l6.91-1.01L12 2z"/>
            </svg>`;
            container.appendChild(wrap);
        }

        document.getElementById('thanks-msg').textContent = MSG[score] || 'Hasta pronto';
        show('s-thanks');
        S.saleId = null; S.data = null;
        setTimeout(goSleep, 4000);
    }

    function goSleep() {
        stopTimer(); S.saleId = null; S.data = null;
        document.querySelectorAll('.rate-opt').forEach(b => { b.style.transform = ''; b.style.opacity = ''; });
        show('s-sleep');
    }
    </script>
</body>
</html>