<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> <title>Scanner Caseta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-white h-screen overflow-hidden" x-data="scannerApp()" x-init="initFocus()">

    <div class="h-16 bg-gray-800 flex justify-between items-center px-4 shadow-lg">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
            <span class="font-bold tracking-wider">CASETA ONLINE</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-400 hover:text-white text-sm uppercase font-bold">
                Salir <i class="fas fa-sign-out-alt ml-1"></i>
            </button>
        </form>
    </div>

    <div class="h-[calc(100vh-4rem)] flex flex-col items-center justify-center p-4 relative">
        
        <div x-show="!vale" class="text-center w-full max-w-md animate-fade-in">
            <div class="mb-8 relative inline-block">
                <i class="fas fa-qrcode text-[120px] text-blue-500 opacity-80 animate-pulse"></i>
                <div class="absolute -bottom-4 left-0 w-full text-center">
                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full uppercase font-bold">Modo Lectura</span>
                </div>
            </div>
            <h1 class="text-3xl font-black mb-2">ESCANEAR CÓDIGO</h1>
            <p class="text-gray-400 text-lg">Apunta la pistola al Vale</p>
            
            <input type="text" x-model="inputVale" @keydown.enter="lookupVale()" 
                   id="inputVale" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer" autocomplete="off" autofocus>
        </div>

        <div x-show="vale" style="display: none;" class="w-full max-w-lg bg-white text-gray-800 rounded-3xl overflow-hidden shadow-2xl relative animate-slide-up">
            
            <button @click="reset()" class="absolute top-4 right-4 bg-gray-200 text-gray-600 w-10 h-10 rounded-full flex items-center justify-center font-bold z-10 hover:bg-red-100 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>

            <div class="p-6 text-center" :class="context === 'entrada' ? 'bg-yellow-400' : 'bg-blue-600 text-white'">
                <p class="text-xs font-bold uppercase opacity-80 mb-1" x-text="context === 'entrada' ? 'ENTRADA DETECTADA' : 'SALIDA DETECTADA'"></p>
                <h2 class="text-4xl font-black tracking-tighter" x-text="vale?.folio_vale"></h2>
                <p class="text-sm font-mono mt-1 opacity-90" x-text="vale?.unit?.placa ?? 'SIN PLACAS'"></p>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-50 p-3 rounded-xl text-blue-600"><i class="fas fa-cubes text-2xl"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Material</p>
                        <p class="text-xl font-bold leading-none" x-text="vale?.material?.name"></p>
                        <p class="text-sm text-blue-600 font-bold"><span x-text="vale?.cantidad"></span> <span x-text="vale?.material?.unit"></span></p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    
                    <div x-show="context === 'entrada'">
                        <p class="text-yellow-600 font-bold animate-pulse mb-2">ESPERANDO ESCANEO DE UNIDAD...</p>
                        <input type="text" x-model="inputUnidad" @keydown.enter="confirmarEntrada()" 
                               id="inputUnidad"
                               class="w-full text-center text-2xl font-black uppercase border-2 border-yellow-400 rounded-xl p-3 focus:outline-none focus:ring-4 focus:ring-yellow-200"
                               placeholder="QR CAMIÓN" autocomplete="off">
                    </div>

                    <div x-show="context === 'salida'">
                        <p class="text-blue-600 font-bold mb-4">ESCANEE COMANDO DE MESA</p>
                        <div class="grid grid-cols-2 gap-3 opacity-50 pointer-events-none">
                            <div class="bg-gray-100 p-2 rounded border border-gray-300">
                                <span class="font-bold text-xs">CMD_SURTIDO</span>
                            </div>
                            <div class="bg-gray-100 p-2 rounded border border-gray-300">
                                <span class="font-bold text-xs">CMD_VACIO</span>
                            </div>
                        </div>
                        <input type="text" x-model="inputCommand" @keydown.enter="procesarComando()" 
                               id="inputCommand"
                               class="w-full mt-4 text-center text-xl uppercase border-2 border-blue-200 rounded-xl p-2"
                               placeholder="Esperando..." autocomplete="off">
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        function scannerApp() {
            return {
                inputVale: '', inputUnidad: '', inputCommand: '',
                vale: null, context: '',
                
                initFocus() {
                    setInterval(() => {
                        if(!this.vale) document.getElementById('inputVale')?.focus();
                        else if(this.context === 'entrada') document.getElementById('inputUnidad')?.focus();
                        else if(this.context === 'salida') document.getElementById('inputCommand')?.focus();
                    }, 500); // Re-enfoca cada medio segundo para asegurar "Manos Libres"
                },

                async lookupVale() {
                    if (!this.inputVale) return;
                    try {
                        let res = await fetch("{{ route('operations.lookup') }}", {
                            method: "POST",
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ code: this.inputVale })
                        });
                        let data = await res.json();
                        if (data.status === 'success') {
                            this.vale = data.data;
                            this.context = data.context;
                            this.playSound('beep');
                            this.inputVale = '';
                        } else {
                            this.alertError(data.message);
                            this.inputVale = '';
                        }
                    } catch(e) { this.alertError('Error de red'); }
                },

                async confirmarEntrada() {
                    if (!this.inputUnidad) return;
                    this.enviar('confirmar_entrada', this.inputUnidad);
                },

                async procesarComando() {
                    let cmd = this.inputCommand.toUpperCase();
                    if(cmd === 'CMD_SURTIDO') this.enviar('salida_surtido');
                    else if(cmd === 'CMD_VACIO') this.enviar('salida_vacio');
                    else { this.inputCommand = ''; this.playSound('error'); }
                },

                async enviar(accion, qr = null) {
                    try {
                        let res = await fetch("{{ route('operations.register') }}", {
                            method: "POST",
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ vale_id: this.vale.id, accion: accion, unit_code: qr })
                        });
                        let data = await res.json();
                        if (data.status === 'success') {
                            this.playSound('success');
                            Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
                            this.reset();
                        } else {
                            this.playSound('error');
                            Swal.fire({ icon: 'error', text: data.message });
                            if(accion === 'confirmar_entrada') this.inputUnidad = '';
                            else this.inputCommand = '';
                        }
                    } catch(e) { this.alertError('Error grave'); }
                },

                reset() { this.vale = null; this.inputVale = ''; this.inputUnidad = ''; this.inputCommand = ''; },
                alertError(msg) { this.playSound('error'); Swal.fire({ icon: 'error', title: 'Oops...', text: msg, timer: 2000, showConfirmButton: false }); },
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
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-in; }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</body>
</html>