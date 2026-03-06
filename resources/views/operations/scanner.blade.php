<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Scanner Logística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root { --brand: #121f48; --brand-soft: #eef1f8; --brand-border: #c8cedf; }
        * { font-family: 'Inter', sans-serif; }
        #reader { width: 100%; height: 100%; }
        #reader video { object-fit: cover; width: 100% !important; height: 100% !important; border-radius: 0; }
        #reader > div { display: none !important; }
        [x-cloak] { display: none !important; }
        @keyframes cooldown-bar { from { width: 100%; } to { width: 0%; } }
        .cooldown-bar-anim { animation: cooldown-bar linear forwards; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" x-data="cameraApp()" x-init="startCamera()">

    {{-- ── HEADER ── --}}
    <div class="bg-white h-16 flex justify-between items-center px-4 shadow-sm z-20 sticky top-0 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center shadow-sm"
                 style="background:#121f48;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-sm text-gray-900 leading-tight" style="font-weight:800;">Caseta Visor</h1>
                <div class="flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full transition-colors" :class="scanning ? 'bg-green-400 animate-pulse' : 'bg-red-400'"></div>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;" x-text="scanning ? 'CÁMARA ACTIVA' : 'EN PAUSA'"></span>
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
            <div id="reader" class="absolute inset-0"></div>

            {{-- Marco de escaneo --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-36 h-36 relative">
                    <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-green-400 rounded-tl-sm"></div>
                    <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-green-400 rounded-tr-sm"></div>
                    <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-green-400 rounded-bl-sm"></div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-green-400 rounded-br-sm"></div>
                </div>
            </div>

            {{-- Cooldown overlay --}}
            <div x-show="cooldownActive" class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-2 pointer-events-none">
                <svg class="w-8 h-8 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-white text-xs" style="font-weight:700;">Espera <span x-text="cooldownRemaining"></span>s</span>
            </div>

            {{-- Barra de cooldown --}}
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/30">
                <div x-ref="cooldownBar" class="h-full transition-none" style="width:100%; background:#eef1f8;"></div>
            </div>

            {{-- Etiqueta guía --}}
            <div class="absolute bottom-3 left-0 w-full text-center pointer-events-none">
                <span class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur" style="font-weight:700;" x-text="mensajeGuia"></span>
            </div>
        </div>

        {{-- ── INPUT MANUAL ── --}}
        <div class="w-full max-w-sm flex gap-2" x-show="!vale">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-gray-300 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
                </svg>
                <input type="text" x-model="inputManual" @keydown.enter="simularEscaneo()"
                       class="w-full bg-white border border-gray-200 rounded-xl pl-9 pr-3 py-2.5 text-sm outline-none uppercase shadow-sm transition"
                       style="font-weight:500;"
                       placeholder="Código manual...">
            </div>
            <button @click="simularEscaneo()"
                    class="text-white px-4 rounded-xl shadow-sm transition-colors flex items-center justify-center"
                    style="background:#121f48;"
                    onmouseover="this.style.background='#0d1633'"
                    onmouseout="this.style.background='#121f48'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        {{-- ── CARD DEL VALE ── --}}
        <div x-show="vale"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-6"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;"
             class="w-full max-w-sm bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header de la card — usa brand color --}}
            <div class="text-white px-5 pt-5 pb-6 relative overflow-hidden" style="background:#121f48;">
                {{-- Ícono decorativo --}}
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-white/5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
                </svg>

                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[9px] text-white/50 uppercase tracking-widest mb-1" style="font-weight:800;">Unidad autorizada</p>
                        <h2 class="text-3xl text-white tracking-tight uppercase" style="font-weight:800;" x-text="vale?.unit?.placa ?? 'EXTERNA'"></h2>
                    </div>
                    <button @click="resetTodo()"
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
                    <span class="text-xs text-white/80 font-mono" style="font-weight:600;" x-text="vale?.folio_vale"></span>
                </div>
            </div>

            {{-- Datos --}}
            <div class="p-4 grid grid-cols-2 gap-4 border-b border-gray-100">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-2" style="font-weight:800;">Carga / Material</p>
                    <p class="text-sm text-gray-800 leading-tight" style="font-weight:700;" x-text="vale?.material?.name"></p>
                    <p class="text-xs mt-0.5" style="font-weight:600; color:#121f48;">
                        <span x-text="vale?.cantidad"></span> <span x-text="vale?.material?.unit"></span>
                    </p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-2" style="font-weight:800;">Cliente destino</p>
                    <p class="text-sm text-gray-800 leading-tight truncate" style="font-weight:700;" x-text="vale?.sale?.client?.name"></p>
                </div>
            </div>

            {{-- Panel de acción --}}
            <div class="p-4">
                {{-- Estado: Entrada --}}
                <div x-show="context === 'entrada'"
                     class="rounded-xl p-4 text-center border border-yellow-200 bg-yellow-50">
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

                {{-- Estado: Salida --}}
                <div x-show="context === 'salida'"
                     class="rounded-xl p-4 border border-green-200 bg-green-50">
                    <p class="text-[9px] text-green-600 uppercase tracking-widest mb-3 text-center" style="font-weight:800;">Paso Final · Confirmar Salida</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="inputManual='CMD_SURTIDO'; simularEscaneo()"
                                class="text-white text-xs py-2.5 rounded-xl shadow-sm transition-colors"
                                style="background:#121f48; font-weight:700;"
                                onmouseover="this.style.background='#0d1633'"
                                onmouseout="this.style.background='#121f48'">
                            Surtido
                        </button>
                        <button @click="inputManual='CMD_VACIO'; simularEscaneo()"
                                class="bg-white hover:bg-gray-50 text-gray-600 text-xs py-2.5 rounded-xl border border-gray-200 shadow-sm transition-colors"
                                style="font-weight:700;">
                            Vacío
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script>
function cameraApp() {
    return {
        html5QrcodeScanner: null,
        scanning: false,
        vale: null,
        context: 'inicio',
        mensajeGuia: 'Escanea el vale',
        inputManual: '',

        COOLDOWN_SECS: 7,
        cooldownActive: false,
        cooldownRemaining: 0,
        _cooldownTimer: null,
        _cooldownInterval: null,

        async startCamera() {
            if (this.html5QrcodeScanner) {
                try { await this.html5QrcodeScanner.stop(); } catch (e) {}
            }
            await new Promise(r => setTimeout(r, 300));
            this.html5QrcodeScanner = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
            };

            const onSuccess = (text) => { this.onScanSuccess(text); };
            const onError   = () => {};

            try {
                await this.html5QrcodeScanner.start({ facingMode: "environment" }, config, onSuccess, onError);
                this.scanning = true;
            } catch {
                try {
                    await this.html5QrcodeScanner.start({ facingMode: "user" }, config, onSuccess, onError);
                    this.scanning = true;
                } catch {
                    this.alertError('No se pudo abrir la cámara. Verifica permisos y HTTPS.');
                    this.mensajeGuia = 'Usa código manual';
                }
            }
        },

        startCooldown() {
            this.cooldownActive    = true;
            this.cooldownRemaining = this.COOLDOWN_SECS;

            const bar = this.$refs.cooldownBar;
            if (bar) {
                bar.style.transition = 'none';
                bar.style.width = '100%';
                requestAnimationFrame(() => {
                    bar.style.transition = `width ${this.COOLDOWN_SECS}s linear`;
                    bar.style.width = '0%';
                });
            }

            clearInterval(this._cooldownInterval);
            this._cooldownInterval = setInterval(() => {
                this.cooldownRemaining--;
                if (this.cooldownRemaining <= 0) clearInterval(this._cooldownInterval);
            }, 1000);

            clearTimeout(this._cooldownTimer);
            this._cooldownTimer = setTimeout(() => {
                this.cooldownActive = false;
                if (bar) { bar.style.transition = 'none'; bar.style.width = '100%'; }
            }, this.COOLDOWN_SECS * 1000);
        },

        simularEscaneo() {
            if (!this.inputManual.trim()) return;
            this.onScanSuccess(this.inputManual.trim());
            this.inputManual = '';
        },

        async onScanSuccess(codigo) {
            const esComandoManual = ['CMD_SURTIDO','CMD_VACIO'].includes(codigo.toUpperCase());
            if (this.cooldownActive && !esComandoManual) return;

            if (this.html5QrcodeScanner) {
                try { await this.html5QrcodeScanner.pause(); } catch(e) {}
            }

            this.playSound('beep');
            this.startCooldown();

            if (!this.vale) {
                await this.lookupVale(codigo);
            } else {
                if (this.context === 'entrada')     await this.confirmarEntrada(codigo);
                else if (this.context === 'salida') await this.procesarComando(codigo);
            }

            if (this.html5QrcodeScanner) {
                try { await this.html5QrcodeScanner.resume(); } catch(e) {}
            }
        },

        async lookupVale(codigo) {
            try {
                const res  = await this._post('/operations/lookup', { code: codigo });
                const data = await res.json();

                if (!res.ok || data.status !== 'success') {
                    return this.alertError(data?.message ?? `Error del servidor (${res.status})`);
                }

                this.vale    = data.data;
                this.context = data.context;
                this.mensajeGuia = this.context === 'entrada' ? 'Escanea QR de la unidad' : 'Confirma la salida';

            } catch (e) {
                this.alertError('Sin conexión. Verifica tu red e intenta de nuevo.');
            }
        },

        async confirmarEntrada(qrUnidad) {
            const uuid = this.limpiarCodigo(qrUnidad);
            await this.enviarServidor('confirmar_entrada', uuid);
        },

        async procesarComando(codigo) {
            const cmd = codigo.toUpperCase();
            if (cmd.includes('SURTIDO'))    await this.enviarServidor('salida_surtido');
            else if (cmd.includes('VACIO')) await this.enviarServidor('salida_vacio');
            else this.alertError('Código inválido. Usa los botones Surtido / Vacío.');
        },

        async enviarServidor(accion, unitCode = null) {
            try {
                const res  = await this._post('/operations/register', { vale_id: this.vale.id, accion, unit_code: unitCode });
                const data = await res.json();

                if (!res.ok || data.status !== 'success') {
                    return this.alertError(data?.message ?? `Error del servidor (${res.status})`);
                }

                this.playSound('success');
                await Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#f0fdf4',
                    iconColor: '#16a34a',
                    customClass: { popup: 'rounded-2xl shadow-xl' }
                });
                this.resetTodo();

            } catch (e) {
                this.alertError('Sin conexión. No se pudo guardar el registro.');
            }
        },

        _post(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(body)
            });
        },

        limpiarCodigo(codigo) {
            if (!codigo) return '';
            if (codigo.includes('http') || codigo.includes('/')) {
                const partes = codigo.split('/');
                return partes[partes.length - 1];
            }
            return codigo;
        },

        resetTodo() {
            this.vale    = null;
            this.context = 'inicio';
            this.mensajeGuia = 'Escanea el vale';
            this.inputManual = '';
        },

        alertError(msg) {
            this.playSound('error');
            return Swal.fire({
                icon: 'error',
                title: 'Atención',
                text: msg,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#121f48',
                customClass: { popup: 'rounded-2xl shadow-xl', confirmButton: 'rounded-xl' }
            });
        },

        playSound(type) {
            const srcs = {
                beep:    'https://www.soundjay.com/button/beep-07.mp3',
                success: 'https://www.soundjay.com/misc/sounds/magic-chime-01.mp3',
                error:   'https://www.soundjay.com/button/button-10.mp3'
            };
            const a = new Audio(srcs[type]);
            a.play().catch(() => {});
        }
    }
}
</script>
</body>
</html>