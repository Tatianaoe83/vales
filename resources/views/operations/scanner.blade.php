<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Scanner Logística</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #reader { width: 100%; height: 100%; object-fit: cover; }
        #reader video { object-fit: cover; border-radius: 1rem; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 h-screen flex flex-col font-sans" x-data="cameraApp()" x-init="startCamera()">

    <div class="bg-white h-16 flex justify-between items-center px-4 shadow-sm z-20 sticky top-0 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-md">
                <i class="fas fa-eye"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 leading-tight">Caseta Visor</h1>
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 rounded-full" :class="scanning ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide" x-text="scanning ? 'CAMARA ACTIVA' : 'EN PAUSA'"></span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2">
                <i class="fas fa-power-off text-lg"></i>
            </button>
        </form>
    </div>

    <div class="flex-grow flex flex-col items-center justify-start pt-4 px-4 gap-4 overflow-y-auto pb-20">
        
        <div class="relative w-full max-w-sm aspect-[16/9] bg-black rounded-2xl shadow-lg overflow-hidden border border-gray-300 shrink-0">
            <div id="reader" class="w-full h-full opacity-90"></div>
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-40 h-40 border-2 border-white/40 rounded-lg relative">
                    <div class="absolute top-0 left-0 w-3 h-3 border-t-2 border-l-2 border-green-400"></div>
                    <div class="absolute top-0 right-0 w-3 h-3 border-t-2 border-r-2 border-green-400"></div>
                    <div class="absolute bottom-0 left-0 w-3 h-3 border-b-2 border-l-2 border-green-400"></div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 border-b-2 border-r-2 border-green-400"></div>
                </div>
            </div>
            <div class="absolute bottom-2 left-0 w-full text-center pointer-events-none">
                <span class="bg-black/70 text-white text-[10px] font-bold px-3 py-1 rounded-full backdrop-blur" x-text="mensajeGuia"></span>
            </div>
        </div>

        <div class="w-full max-w-sm flex gap-2" x-show="!vale">
            <div class="relative flex-grow">
                <i class="fas fa-keyboard absolute left-3 top-3 text-gray-400"></i>
                <input type="text" x-model="inputManual" @keydown.enter="simularEscaneo()"
                       class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 outline-none uppercase" 
                       placeholder="Escribir código manual...">
            </div>
            <button @click="simularEscaneo()" class="bg-gray-800 text-white px-4 rounded-lg shadow-sm hover:bg-gray-700">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>


        <div x-show="vale" x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-10" 
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;" 
             class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-4 relative">
            
            <button @click="resetTodo()" class="absolute top-2 right-2 z-10 w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-400 rounded-full hover:bg-red-100 hover:text-red-500 transition shadow-sm">
                <i class="fas fa-times"></i>
            </button>

            <div class="bg-gray-800 text-white p-5 text-center relative overflow-hidden">
                <i class="fas fa-truck absolute -left-4 -bottom-4 text-6xl text-gray-700 opacity-20 transform -rotate-12"></i>
                
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Unidad Autorizada</p>
                <h2 class="text-4xl font-black tracking-tight uppercase" 
                    x-text="vale?.unit?.placa ?? 'EXTERNA'">
                </h2>
                <div class="mt-2 inline-flex items-center gap-2 bg-gray-700 px-3 py-1 rounded-full text-xs">
                    <i class="fas fa-ticket-alt text-yellow-400"></i>
                    <span class="font-mono text-gray-300" x-text="vale?.folio_vale"></span>
                </div>
            </div>

            <div class="p-5 grid grid-cols-2 gap-4 border-b border-gray-100 bg-white">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Carga / Material</p>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-cubes text-blue-500 mt-1"></i>
                        <div>
                            <p class="font-bold text-gray-800 leading-tight" x-text="vale?.material?.name"></p>
                            <p class="text-xs text-gray-500"><span x-text="vale?.cantidad"></span> <span x-text="vale?.material?.unit"></span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Cliente Destino</p>
                    <p class="font-bold text-gray-800 text-sm truncate leading-tight" x-text="vale?.sale?.client?.name"></p>
                </div>
            </div>

            <div class="p-4 bg-gray-50">
                <div class="rounded-xl p-4 text-center border-2 shadow-sm transition-colors duration-300" 
                     :class="context === 'entrada' ? 'bg-yellow-50 border-yellow-400 text-yellow-800' : 'bg-green-50 border-green-500 text-green-800'">
                    
                    <p class="text-xs font-bold uppercase mb-1 opacity-80" x-text="context === 'entrada' ? 'PASO 2: VALIDACIÓN' : 'PASO FINAL: SALIDA'"></p>
                    
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas text-2xl" :class="context === 'entrada' ? 'fa-camera' : 'fa-check-circle'"></i>
                        <span class="font-black text-xl uppercase" x-text="context === 'entrada' ? 'ESCANEAR CAMIÓN' : 'CONFIRMAR SALIDA'"></span>
                    </div>

                    <div x-show="context === 'salida'" class="mt-3 grid grid-cols-2 gap-2">
                        <button @click="inputManual='CMD_SURTIDO'; simularEscaneo()" class="bg-blue-600 text-white text-xs font-bold py-2 rounded-lg shadow hover:bg-blue-700">
                            SURTIDO
                        </button>
                        <button @click="inputManual='CMD_VACIO'; simularEscaneo()" class="bg-white text-gray-600 text-xs font-bold py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
                            VACÍO
                        </button>
                    </div>

                    <p x-show="context === 'entrada'" class="text-xs mt-2 font-medium">
                        Por favor, apunte al código QR pegado en la unidad.
                    </p>
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
            context: 'inicio', // inicio | entrada | salida
            mensajeGuia: 'Escanea el Vale',
            inputManual: '',

            // --- 1. INICIALIZACIÓN DE CÁMARA ---
            async startCamera() {
                // Limpiamos si había una cámara prendida antes
                if (this.html5QrcodeScanner) {
                    try { await this.html5QrcodeScanner.stop(); } catch (e) {}
                }

                // Esperamos un poco para que el HTML (el div "reader") exista
                await new Promise(r => setTimeout(r, 300));

                this.html5QrcodeScanner = new Html5Qrcode("reader");

                // Configuración "Todo Terreno" para móviles
                const config = { 
                    fps: 10, // Cuadros por segundo (10 es estable)
                    // qrbox: { width: 250, height: 250 }, // COMENTADO: Para que lea toda la pantalla
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true // Usa el lector nativo de Android/iOS (Más rápido)
                    },
                    formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ] // Solo busca QR
                };
                
                try {
                    // Intento 1: Cámara Trasera (Environment)
                    await this.html5QrcodeScanner.start(
                        { facingMode: "environment" }, 
                        config, 
                        (decodedText) => { this.onScanSuccess(decodedText); },
                        (errorMessage) => { /* Ignoramos errores de lectura frame a frame */ }
                    );
                    this.scanning = true;
                } catch (err) {
                    console.error("Fallo cámara trasera, intentando frontal...", err);
                    
                    // Intento 2: Cualquier cámara disponible (Fallback)
                    try {
                        await this.html5QrcodeScanner.start(
                            { facingMode: "user" }, 
                            config, 
                            (decodedText) => { this.onScanSuccess(decodedText); },
                            (errorMessage) => {}
                        );
                        this.scanning = true;
                    } catch (err2) {
                        await Swal.fire('Error', 'No se pudo abrir la cámara. Verifica permisos y HTTPS.', 'error');
                        this.mensajeGuia = 'Usa código manual';
                    }
                }
            },

            // --- 2. LÓGICA DE ESCANEO ---
            simularEscaneo() {
                if(!this.inputManual) return;
                this.onScanSuccess(this.inputManual);
                this.inputManual = ''; 
            },

            async onScanSuccess(codigo) {
                // Pausa visual para no leer 2 veces lo mismo
                if(this.html5QrcodeScanner) {
                    try { await this.html5QrcodeScanner.pause(); } catch(e){}
                }

                this.playSound('beep');

                // Lógica Principal:
                if (!this.vale) {
                    // A. Si no hay vale cargado, buscamos qué es este código
                    await this.lookupVale(codigo);
                } else {
                    // B. Si ya hay vale, estamos validando el paso siguiente
                    if (this.context === 'entrada') await this.confirmarEntrada(codigo);
                    else if (this.context === 'salida') await this.procesarComando(codigo);
                }

                // Reanudar cámara
                if(this.html5QrcodeScanner) {
                    try { await this.html5QrcodeScanner.resume(); } catch(e){}
                }
            },

            // --- 3. CONEXIÓN CON LARAVEL (LOOKUP) ---
            async lookupVale(codigo) {
                try {
                    let res = await fetch("/operations/lookup", {
                        method: "POST",
                        headers: { 
                            "Content-Type": "application/json", 
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify({ code: codigo })
                    });

                    if (!res.ok) throw new Error(`Error: ${res.status}`);
                    let data = await res.json();

                    if (data.status === 'success') {
                        this.vale = data.data;
                        this.context = data.context; // Laravel nos dice si toca 'entrada' o 'salida'
                        
                        if(this.context === 'entrada') this.mensajeGuia = 'Escanea QR UNIDAD';
                        else if(this.context === 'salida') this.mensajeGuia = 'Confirma Salida';
                        
                    } else {
                        await this.alertError(data.message);
                    }
                } catch(e) { 
                    await this.alertError('Fallo de red: ' + e.message); 
                }
            },

            // --- 4. ACCIONES (REGISTRO) ---
            async confirmarEntrada(qrUnidad) { 
                // Aquí extraemos el UUID si el QR es una URL
                let uuidLimpio = this.limpiarCodigo(qrUnidad);
                await this.enviarServidor('confirmar_entrada', uuidLimpio); 
            },

            async procesarComando(codigo) {
                let cmd = codigo.toUpperCase();
                if(cmd.includes('SURTIDO')) await this.enviarServidor('salida_surtido');
                else if(cmd.includes('VACIO')) await this.enviarServidor('salida_vacio');
                else await this.alertError('Código inválido. Escanea SURTIDO o VACÍO.');
            },

            async enviarServidor(accion, unitCode = null) {
                try {
                    let res = await fetch("/operations/register", {
                        method: "POST",
                        headers: { 
                            "Content-Type": "application/json", 
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify({ vale_id: this.vale.id, accion: accion, unit_code: unitCode })
                    });

                    if (!res.ok) throw new Error(`Error: ${res.status}`);
                    let data = await res.json();

                    if (data.status === 'success') {
                        this.playSound('success');
                        await Swal.fire({ 
                            icon: 'success', title: '¡Correcto!', text: data.message, 
                            timer: 2000, showConfirmButton: false, background: '#f0fdf4', iconColor: '#16a34a'
                        });
                        this.resetTodo(); // Limpiamos pantalla para el siguiente camión
                    } else {
                        await this.alertError(data.message);
                    }
                } catch(e) {
                    await this.alertError('Fallo al guardar: ' + e.message);
                }
            },

            // Utilidad: Limpia si el QR es una URL completa (http://.../uuid)
            limpiarCodigo(codigo) {
                if (!codigo) return '';
                if (codigo.includes('http') || codigo.includes('/')) {
                    let partes = codigo.split('/');
                    return partes[partes.length - 1]; 
                }
                return codigo;
            },

            resetTodo() {
                this.vale = null;
                this.context = 'inicio';
                this.mensajeGuia = 'Escanea el Vale';
                this.inputManual = '';
            },

            async alertError(msg) {
                this.playSound('error');
                await Swal.fire({ icon: 'error', title: 'Alto', text: msg, confirmButtonColor: '#ef4444' });
            },

            playSound(type) {
                let a = new Audio();
                if(type=='beep') a.src='https://www.soundjay.com/button/beep-07.mp3';
                if(type=='success') a.src='https://www.soundjay.com/misc/sounds/magic-chime-01.mp3';
                if(type=='error') a.src='https://www.soundjay.com/button/button-10.mp3';
                a.play().catch(e=>{});
            }
        }
    }
</script>
</body>
</html>