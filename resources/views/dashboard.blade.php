<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <div class="relative hidden md:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" class="w-64 py-2 pl-10 pr-4 bg-gray-100 border-none rounded-lg text-sm focus:ring-0" placeholder="Buscar por folio, cliente...">
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase">Total Vales Activos</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $totalValesActivos }}</p>
                    <p class="text-xs font-bold text-green-500 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        +5.2% <span class="text-gray-400 ml-1 font-normal">vs mes anterior</span>
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase">Ventas del Día</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">${{ number_format($ventasHoy, 2) }}</p>
                    <p class="text-xs font-bold text-red-500 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        -1.8% <span class="text-gray-400 ml-1 font-normal">vs ayer</span>
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase">Entregas Pendientes</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $entregasPendientes }}</p>
                    <p class="text-xs font-bold text-green-500 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        +2.0% <span class="text-gray-400 ml-1 font-normal">flujo normal</span>
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase">Nuevos Clientes (Mes)</p>
                    <p class="text-3xl font-extrabold text-gray-800 mt-2">{{ $nuevosClientes }}</p>
                    <p class="text-xs font-bold text-green-500 mt-2 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        +10.0% <span class="text-gray-400 ml-1 font-normal">crecimiento</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <h3 class="text-lg font-bold text-gray-800">Accesos Rápidos</h3>
                <div class="flex gap-3">
                    <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-bold shadow-sm transition">
                        Administrar Clientes
                    </a>
                    <a href="{{ route('sales.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Crear Nuevo Vale
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-base font-bold text-gray-800 mb-6">Ventas por Día (Últimos 7 Días)</h4>
                    <div class="h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-base font-bold text-gray-800 mb-6">Actividad Reciente</h4>
                    
                    <div class="space-y-6">
                        @forelse($actividadReciente as $history)
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center 
                                {{ $history->estatus_nuevo == 'Vigente' ? 'bg-blue-100 text-blue-500' : '' }}
                                {{ $history->estatus_nuevo == 'Surtido' ? 'bg-green-100 text-green-500' : '' }}
                                {{ $history->estatus_nuevo == 'Cancelado' ? 'bg-red-100 text-red-500' : '' }}
                                {{ $history->estatus_nuevo == 'En Planta' ? 'bg-yellow-100 text-yellow-600' : '' }}">
                                
                                @if($history->estatus_nuevo == 'Vigente') <i class="fas fa-ticket-alt text-xs"></i> @endif
                                @if($history->estatus_nuevo == 'Surtido') <i class="fas fa-check text-xs"></i> @endif
                                @if($history->estatus_nuevo == 'En Planta') <i class="fas fa-truck text-xs"></i> @endif
                            </div>
                            
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $history->estatus_nuevo == 'Vigente' ? 'Vale creado' : $history->estatus_nuevo }}
                                    <span class="text-gray-500 font-normal">#{{ $history->vale->folio_vale }}</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Cliente: {{ Str::limit($history->vale->sale->client->name ?? 'N/A', 20) }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $history->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4">No hay actividad reciente.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Ventas ($)',
                    data: @json($chartData),
                    backgroundColor: '#3B82F6', // Azul Tailwind
                    borderRadius: 6,
                    barThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</x-app-layout>