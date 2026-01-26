<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vales Agregados') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            [x-cloak] { display: none !important; }
            /* Transición suave para el ancho del sidebar */
            .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: true }">
        
        <div class="flex h-screen bg-gray-100">

            <aside class="flex flex-col bg-white border-r border-gray-200 sidebar-transition shadow-lg z-30"
                   :class="sidebarOpen ? 'w-64' : 'w-20'">
                
                <div class="flex items-center justify-between h-16 border-b border-gray-200 px-4">
                    <span class="text-xl font-bold text-blue-600 truncate" x-show="sidebarOpen" x-transition>
                        Vales App
                    </span>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-600 focus:outline-none">
                        <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-bars'"></i>
                    </button>
                </div>

                <div class="flex-1 flex flex-col overflow-y-auto py-4 space-y-1 overflow-x-hidden">
                    
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-4 py-3 transition-colors duration-200"
                       :class="request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'">
                        <i class="fas fa-home w-6 text-center text-lg"></i>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen">Dashboard</span>
                    </a>

                    <div x-data="{ open: {{ request()->routeIs('sales.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-truck-loading w-6 text-center text-lg text-blue-500"></i>
                                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen">Operaciones</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                               :class="open ? 'transform rotate-180' : ''" x-show="sidebarOpen"></i>
                        </button>
                        
                        <div x-show="open && sidebarOpen" x-collapse class="bg-gray-50">
                            <a href="{{ route('sales.create') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-plus-circle w-4 mr-2"></i> Nueva Venta
                            </a>
                            <a href="{{ route('sales.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-list w-4 mr-2"></i> Historial Vales
                            </a>
                            <a href="#" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-check-double w-4 mr-2"></i> Entregas Planta
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center px-4 py-3 transition-colors duration-200"
                       :class="request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600 border-r-4 border-blue-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'">
                        <i class="fas fa-chart-pie w-6 text-center text-lg text-purple-500"></i>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen">Reportes</span>
                    </a>

                    <div class="border-t border-gray-200 my-2 mx-4" x-show="sidebarOpen">
                        <span class="text-xs font-bold text-gray-400 uppercase mt-2 block">Administración</span>
                    </div>

                    @if(auth()->user()->can('manage clients') || auth()->user()->can('manage materials'))
                    <div x-data="{ open: {{ request()->routeIs('clients.*') || request()->routeIs('materials.*') || request()->routeIs('units.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-boxes w-6 text-center text-lg text-orange-500"></i>
                                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen">Catálogos</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                               :class="open ? 'transform rotate-180' : ''" x-show="sidebarOpen"></i>
                        </button>
                        
                        <div x-show="open && sidebarOpen" x-collapse class="bg-gray-50">
                            @can('manage clients')
                            <a href="{{ route('clients.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-users w-4 mr-2"></i> Clientes
                            </a>
                            @endcan
                            @can('manage materials')
                            <a href="{{ route('materials.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-cubes w-4 mr-2"></i> Materiales
                            </a>
                            @endcan
                            <a href="{{ route('units.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-truck w-4 mr-2"></i> Unidades
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles'))
                    <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" 
                                class="w-full flex items-center justify-between px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt w-6 text-center text-lg text-red-500"></i>
                                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarOpen">Seguridad</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                               :class="open ? 'transform rotate-180' : ''" x-show="sidebarOpen"></i>
                        </button>
                        
                        <div x-show="open && sidebarOpen" x-collapse class="bg-gray-50">
                            @can('manage users')
                            <a href="{{ route('users.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-user-cog w-4 mr-2"></i> Usuarios
                            </a>
                            @endcan
                            @can('manage roles')
                            <a href="{{ route('roles.index') }}" class="flex items-center pl-12 pr-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <i class="fas fa-key w-4 mr-2"></i> Roles
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif

                </div>

                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex w-full items-center text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors text-sm font-medium p-2"
                                :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                            <i class="fas fa-sign-out-alt w-5 text-lg"></i>
                            <span class="ml-3 whitespace-nowrap" x-show="sidebarOpen">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden relative">
                
                <div class="md:hidden bg-white border-b p-4 flex justify-between items-center">
                    <span class="font-bold text-blue-600">Vales App</span>
                    </div>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    @include('layouts.navigation')

                    @if (isset($header))
                        <header class="bg-white shadow-sm">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <div class="p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
            
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Toast Configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Success Message
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Error Message
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Validation Errors
            @if ($errors->any())
                let errorHtml = '<ul class="text-left text-sm list-disc pl-5">';
                @foreach ($errors->all() as $error)
                    errorHtml += '<li>{{ $error }}</li>';
                @endforeach
                errorHtml += '</ul>';

                Swal.fire({
                    title: '¡Atención!',
                    html: errorHtml,
                    icon: 'warning',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3B82F6',
                });
            @endif

            // Delete Confirmation Helper
            window.confirmDelete = function(formId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esto.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                })
            }
        </script>
    </body>
</html>