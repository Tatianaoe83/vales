<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Vales Agregados') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('img/vale-regalo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')

    <style>
        :root {
            --brand:        #121f48;
            --brand-hover:  #0d1633;
            --brand-soft:   #eef1f8;
            --brand-border: #c8cedf;
        }

        * { font-family: 'Inter', sans-serif; }

        [x-cloak] { display: none !important; }

        /* Sidebar transitions */
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .drawer-transition   { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .mobile-overlay      { transition: opacity 0.3s ease; }

        /* Sidebar active item */
        .nav-active {
            background-color: var(--brand) !important;
            color: #fff !important;
        }
        .nav-active .nav-icon-wrap {
            background-color: rgba(255,255,255,.15) !important;
        }

        /* Sub-item active */
        .subnav-active {
            color: var(--brand) !important;
            background-color: var(--brand-soft) !important;
            font-weight: 700;
        }

        /* Bottom nav active */
        .bottom-active {
            color: var(--brand) !important;
        }
        .bottom-active .bottom-icon-wrap {
            background-color: var(--brand-soft) !important;
        }

        /* Central FAB */
        .brand-fab {
            background-color: var(--brand) !important;
            box-shadow: 0 4px 14px rgba(18,31,72,.35) !important;
        }

        /* User avatar */
        .user-avatar {
            background-color: var(--brand) !important;
        }

        /* Scrollbar slim */
        .sidebar-scroll::-webkit-scrollbar { width: 3px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        /* Mobile top bar hamburger hover */
        .mob-menu-btn:hover { background-color: var(--brand-soft); color: var(--brand); }

        /* Folio chip */
        .folio-chip { color: var(--brand); }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100"
      x-data="{
          sidebarOpen: window.innerWidth >= 768,
          mobileOpen: false,
          isMobile: window.innerWidth < 768,
          init() {
              window.addEventListener('resize', () => {
                  this.isMobile = window.innerWidth < 768;
                  if (!this.isMobile) this.mobileOpen = false;
              });
          }
      }">

    <div class="flex h-screen bg-gray-100 overflow-hidden">

        {{-- ── MOBILE OVERLAY ── --}}
        <div x-show="mobileOpen && isMobile"
             x-cloak
             @click="mobileOpen = false"
             class="mobile-overlay fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm md:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        {{-- ── SIDEBAR ── --}}
        <aside x-cloak
               class="flex flex-col bg-white border-r border-gray-200 shadow-xl z-50 h-full"
               :class="{
                   'sidebar-transition': !isMobile,
                   'w-64':  !isMobile && sidebarOpen,
                   'w-20':  !isMobile && !sidebarOpen,
                   'fixed left-0 top-0 w-72 drawer-transition': isMobile,
                   'translate-x-0':    isMobile && mobileOpen,
                   '-translate-x-full': isMobile && !mobileOpen,
               }">

            {{-- Logo + toggle --}}
            <div class="flex items-center justify-between h-16 border-b border-gray-100 px-4 shrink-0">
                <div class="flex items-center overflow-hidden">
                    <img src="{{ asset('img/logo.png') }}" alt="Vales Agregados"
                         style="height:36px; width:auto; object-fit:contain;"
                         x-show="sidebarOpen || isMobile"
                         onerror="this.style.display='none'">
                    <img src="{{ asset('img/logo-solo.png') }}" alt="Icono"
                         class="w-8 h-8 object-contain shrink-0 mx-auto"
                         x-show="!sidebarOpen && !isMobile"
                         onerror="this.style.display='none'">
                </div>

                {{-- Desktop toggle --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="hidden md:flex w-7 h-7 items-center justify-center rounded-lg text-gray-400 transition-all hover:text-white"
                        style="transition:background .15s;"
                        onmouseover="this.style.background='#121f48'"
                        onmouseout="this.style.background=''">
                    <i class="fas text-sm" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                </button>

                {{-- Mobile close --}}
                <button @click="mobileOpen = false"
                        class="md:hidden text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Nav items --}}
            <div class="flex-1 flex flex-col overflow-y-auto sidebar-scroll py-3 space-y-0.5 overflow-x-hidden">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   @click="if(isMobile) mobileOpen = false"
                   class="group flex items-center px-3 py-2.5 mx-2 rounded-xl transition-all duration-200
                          {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <div class="nav-icon-wrap w-8 h-8 flex items-center justify-center rounded-lg shrink-0
                                {{ request()->routeIs('dashboard') ? '' : 'group-hover:bg-white' }}">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="ml-3 font-semibold text-sm whitespace-nowrap"
                          x-show="sidebarOpen || isMobile">Dashboard</span>
                </a>

                {{-- Operaciones --}}
                <div x-data="{ open: {{ request()->routeIs('sales.*') ? 'true' : 'false' }} }" class="mx-2">
                    <button @click="open = !open; if(!sidebarOpen && !isMobile) sidebarOpen = true"
                            class="group w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0" style="background:var(--brand-soft);">
                                <i class="fas fa-truck-loading text-sm" style="color:var(--brand);"></i>
                            </div>
                            <span class="ml-3 font-semibold text-sm whitespace-nowrap"
                                  x-show="sidebarOpen || isMobile">Operaciones</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200 text-gray-400"
                           :class="open ? 'rotate-180' : ''"
                           x-show="sidebarOpen || isMobile"></i>
                    </button>
                    <div x-show="open && (sidebarOpen || isMobile)" x-collapse class="mt-0.5 space-y-0.5">
                        <a href="{{ route('sales.create') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('sales.create') ? 'subnav-active' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-plus-circle w-4 mr-2 text-xs"></i> Nueva Venta
                        </a>
                        <a href="{{ route('sales.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('sales.index') ? 'subnav-active' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-list w-4 mr-2 text-xs"></i> Historial Vales
                        </a>
                    </div>
                </div>

                {{-- Reportes --}}
                <a href="{{ route('reports.index') }}"
                   @click="if(isMobile) mobileOpen = false"
                   class="group flex items-center px-3 py-2.5 mx-2 rounded-xl transition-all duration-200
                          {{ request()->routeIs('reports.*') ? 'bg-purple-600 text-white shadow-md shadow-purple-200' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg shrink-0
                                {{ request()->routeIs('reports.*') ? 'bg-purple-500' : 'bg-purple-50 group-hover:bg-purple-100' }}">
                        <i class="fas fa-chart-pie text-sm {{ request()->routeIs('reports.*') ? 'text-white' : 'text-purple-500' }}"></i>
                    </div>
                    <span class="ml-3 font-semibold text-sm whitespace-nowrap"
                          x-show="sidebarOpen || isMobile">Reportes</span>
                </a>

                {{-- Divider --}}
                <div class="mx-3 pt-3 pb-1" x-show="sidebarOpen || isMobile">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2">Administración</p>
                </div>
                <div class="mx-3 border-t border-gray-100" x-show="!sidebarOpen && !isMobile"></div>

                {{-- Catálogos --}}
                @if(auth()->user()->can('manage clients') || auth()->user()->can('manage materials'))
                <div x-data="{ open: {{ request()->routeIs('clients.*') || request()->routeIs('materials.*') || request()->routeIs('units.*') ? 'true' : 'false' }} }" class="mx-2">
                    <button @click="open = !open; if(!sidebarOpen && !isMobile) sidebarOpen = true"
                            class="group w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 group-hover:bg-orange-100 shrink-0">
                                <i class="fas fa-boxes text-orange-500 text-sm"></i>
                            </div>
                            <span class="ml-3 font-semibold text-sm whitespace-nowrap"
                                  x-show="sidebarOpen || isMobile">Catálogos</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                           :class="open ? 'rotate-180' : ''"
                           x-show="sidebarOpen || isMobile"></i>
                    </button>
                    <div x-show="open && (sidebarOpen || isMobile)" x-collapse class="mt-0.5 space-y-0.5">
                        @can('manage clients')
                        <a href="{{ route('clients.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('clients.*') ? 'text-orange-600 bg-orange-50 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-users w-4 mr-2 text-xs"></i> Clientes
                        </a>
                        @endcan
                        @can('manage materials')
                        <a href="{{ route('materials.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('materials.*') ? 'text-orange-600 bg-orange-50 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-cubes w-4 mr-2 text-xs"></i> Materiales
                        </a>
                        @endcan
                        <a href="{{ route('units.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('units.*') ? 'text-orange-600 bg-orange-50 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-truck w-4 mr-2 text-xs"></i> Unidades
                        </a>
                    </div>
                </div>
                @endif

                {{-- Seguridad --}}
                @if(auth()->user()->can('manage users') || auth()->user()->can('manage roles'))
                <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }" class="mx-2">
                    <button @click="open = !open; if(!sidebarOpen && !isMobile) sidebarOpen = true"
                            class="group w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 group-hover:bg-red-100 shrink-0">
                                <i class="fas fa-shield-alt text-red-500 text-sm"></i>
                            </div>
                            <span class="ml-3 font-semibold text-sm whitespace-nowrap"
                                  x-show="sidebarOpen || isMobile">Seguridad</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                           :class="open ? 'rotate-180' : ''"
                           x-show="sidebarOpen || isMobile"></i>
                    </button>
                    <div x-show="open && (sidebarOpen || isMobile)" x-collapse class="mt-0.5 space-y-0.5">
                        @can('manage users')
                        <a href="{{ route('users.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('users.*') ? 'text-red-600 bg-red-50 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-user-cog w-4 mr-2 text-xs"></i> Usuarios
                        </a>
                        @endcan
                        @can('manage roles')
                        <a href="{{ route('roles.index') }}"
                           @click="if(isMobile) mobileOpen = false"
                           class="flex items-center pl-11 pr-3 py-2 text-sm rounded-xl transition-all
                                  {{ request()->routeIs('roles.*') ? 'text-red-600 bg-red-50 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                            <i class="fas fa-key w-4 mr-2 text-xs"></i> Roles
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

            </div>

            {{-- User + Logout --}}
            <div class="shrink-0 p-3 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3 px-2 py-2 mb-2" x-show="sidebarOpen || isMobile">
                    <div class="user-avatar w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-black shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all text-sm font-medium px-3 py-2.5"
                            :class="(sidebarOpen || isMobile) ? 'justify-start' : 'justify-center'">
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-red-100 transition-colors shrink-0">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="whitespace-nowrap" x-show="sidebarOpen || isMobile">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN CONTENT ── --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- Mobile top bar --}}
            <header class="md:hidden bg-white border-b border-gray-200 shadow-sm shrink-0 z-30">
                <div class="flex items-center justify-between px-4 h-14">
                    <button @click="mobileOpen = true"
                            class="mob-menu-btn w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 transition-all active:scale-95">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                    <img src="{{ asset('img/logo.png') }}" alt="Vales Agregados"
                         style="height:32px; width:auto; object-fit:contain;"
                         onerror="this.style.display='none'">
                    <div class="user-avatar w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-black">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Content area --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">

                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <div class="p-4 md:p-6 pb-24 md:pb-6">
                    {{ $slot }}
                </div>
            </main>

            {{-- ── BOTTOM NAV (mobile) ── --}}
            <nav class="md:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-gray-200 shadow-lg">
                <div class="flex items-center justify-around px-2 py-1.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all
                              {{ request()->routeIs('dashboard') ? 'bottom-active' : 'text-gray-400' }}">
                        <div class="bottom-icon-wrap w-8 h-8 flex items-center justify-center rounded-xl transition-all
                                    {{ request()->routeIs('dashboard') ? 'bottom-active' : '' }}">
                            <i class="fas fa-home text-base"></i>
                        </div>
                        <span class="text-[10px] font-bold">Inicio</span>
                    </a>

                    {{-- Nueva Venta --}}
                    <a href="{{ route('sales.create') }}"
                       class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all
                              {{ request()->routeIs('sales.create') ? 'bottom-active' : 'text-gray-400' }}">
                        <div class="bottom-icon-wrap w-8 h-8 flex items-center justify-center rounded-xl transition-all
                                    {{ request()->routeIs('sales.create') ? 'bottom-active' : '' }}">
                            <i class="fas fa-plus-circle text-base"></i>
                        </div>
                        <span class="text-[10px] font-bold">Nueva Venta</span>
                    </a>

                    {{-- FAB central --}}
                    <button @click="mobileOpen = true"
                            class="flex flex-col items-center gap-0.5 -mt-5">
                        <div class="brand-fab w-12 h-12 rounded-2xl flex items-center justify-center active:scale-95 transition-transform">
                            <i class="fas fa-th-large text-white text-lg"></i>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 mt-0.5">Menú</span>
                    </button>

                    {{-- Historial --}}
                    <a href="{{ route('sales.index') }}"
                       class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all
                              {{ request()->routeIs('sales.index') ? 'bottom-active' : 'text-gray-400' }}">
                        <div class="bottom-icon-wrap w-8 h-8 flex items-center justify-center rounded-xl transition-all
                                    {{ request()->routeIs('sales.index') ? 'bottom-active' : '' }}">
                            <i class="fas fa-list text-base"></i>
                        </div>
                        <span class="text-[10px] font-bold">Historial</span>
                    </a>

                    {{-- Reportes --}}
                    <a href="{{ route('reports.index') }}"
                       class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl transition-all
                              {{ request()->routeIs('reports.*') ? 'text-purple-600' : 'text-gray-400' }}">
                        <div class="w-8 h-8 flex items-center justify-center rounded-xl transition-all
                                    {{ request()->routeIs('reports.*') ? 'bg-purple-100' : '' }}">
                            <i class="fas fa-chart-pie text-base"></i>
                        </div>
                        <span class="text-[10px] font-bold">Reportes</span>
                    </a>

                </div>
            </nav>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end',
            showConfirmButton: false, timer: 3000, timerProgressBar: true,
            didOpen: (t) => {
                t.addEventListener('mouseenter', Swal.stopTimer);
                t.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        @if (session('success'))
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
        @if (session('error'))
            Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
        @endif
        @if ($errors->any())
            let errorHtml = '<ul class="text-left text-sm list-disc pl-5">';
            @foreach ($errors->all() as $error)
                errorHtml += '<li>{{ $error }}</li>';
            @endforeach
            errorHtml += '</ul>';
            Swal.fire({
                title: '¡Atención!', html: errorHtml, icon: 'warning',
                confirmButtonText: 'Entendido', confirmButtonColor: '#121f48',
            });
        @endif

        window.confirmDelete = function(formId) {
            Swal.fire({
                title: '¿Estás seguro?', text: "No podrás revertir esto.", icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#121f48',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }
    </script>
</body>
</html>