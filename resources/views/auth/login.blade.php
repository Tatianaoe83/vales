<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Vales Agregados</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        .login-bg {
            background-image: url('/img/login-bg.jpeg');
            background-size: cover;
            background-position: center;
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .form-enter { animation: slide-up .45s ease forwards; }

        .input-field {
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .input-field:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
            outline: none;
            background: #fff;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen w-full flex">

        {{-- ── Panel izquierdo — imagen + branding (solo desktop) ── --}}
        <div class="hidden lg:flex lg:w-1/2 login-bg relative flex-col">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-800/65 to-blue-900/85"></div>

            <div class="relative z-10 flex flex-col justify-between h-full p-12">

                {{-- Logo blanco --}}
                <div>
                    <img src="{{ asset('img/logo-blanco.png') }}"
                         alt="Vales Agregados"
                         style="height: 60px; width: auto; object-fit: contain; object-position: left;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div style="display:none" class="items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-white font-black text-lg">Vales Agregados</span>
                    </div>
                </div>

                {{-- Tagline --}}
                <div class="space-y-4">
                    <div class="w-10 h-1 bg-blue-400 rounded-full"></div>
                    <h2 class="text-4xl font-black text-white leading-tight">
                        Control total<br>
                        <span class="text-blue-300">de Vales Agregados</span>
                    </h2>
                    <p class="text-blue-200 text-base leading-relaxed max-w-xs">
                        Gestiona ventas, vales y flota en un solo sistema. Rápido, confiable y siempre disponible.
                    </p>
                </div>

                {{-- Feature pills --}}
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full border border-white/20">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Vales digitales
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full border border-white/20">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Control de flota
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full border border-white/20">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Reportes en tiempo real
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Panel derecho — formulario ── --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 bg-white relative overflow-y-auto">

            {{-- Logo mobile --}}
            <div class="lg:hidden mb-6 text-center">
                <img src="{{ asset('img/logo.png') }}"
                     alt="Vales Agregados"
                     style="height: 60px; width: auto; object-fit: contain; margin: 0 auto;"
                     onerror="this.style.display='none'">
            </div>

            {{-- Logo desktop eliminado --}}

            <div class="w-full max-w-md form-enter">

                {{-- Encabezado --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-black text-gray-900 mb-2">Bienvenido de vuelta</h1>
                    <p class="text-gray-400 text-sm">Ingresa tus credenciales para continuar.</p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-xl">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5"
                      x-data="{ show: false, loading: false }"
                      @submit="loading = true">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   required autofocus autocomplete="email"
                                   class="input-field w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 placeholder-gray-300"
                                   placeholder="tu@correo.com">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password"
                                   required autocomplete="current-password"
                                   class="input-field w-full pl-10 pr-11 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 placeholder-gray-300"
                                   placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg x-show="!show" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" x-cloak class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Recordar + olvidé --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-500">Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}"
                           class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            :disabled="loading"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-black text-white
                                   bg-blue-600 hover:bg-blue-700 active:scale-[.98]
                                   shadow-lg shadow-blue-200 hover:shadow-blue-300
                                   transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg x-show="loading" x-cloak class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span x-text="loading ? 'Ingresando...' : 'Acceder'">Acceder</span>
                        <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t border-gray-100 text-center text-xs text-gray-300 font-medium">
                    &copy; {{ date('Y') }} AgregadosSys · Todos los derechos reservados
                </div>
            </div>
        </div>
    </div>

</body>
</html>