<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-500"></i>
            {{ __('Reportes y Métricas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="md:flex md:items-center md:justify-between mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Filtros de Búsqueda</h1>
                            <p class="text-sm text-gray-500 mt-1">Selecciona los criterios para generar tu reporte.</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex gap-3">
                            <span class="text-xs font-semibold uppercase text-gray-400 self-center mr-2 hidden md:block">Exportar:</span>
                            <button type="submit" form="filterForm" formaction="{{ route('reports.excel') }}" class="bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 font-bold py-2 px-4 rounded-lg inline-flex items-center transition shadow-sm">
                                <i class="fas fa-file-excel mr-2 text-lg"></i> Excel
                            </button>
                            <button type="submit" form="filterForm" formaction="{{ route('reports.pdf') }}" class="bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 font-bold py-2 px-4 rounded-lg inline-flex items-center transition shadow-sm">
                                <i class="fas fa-file-pdf mr-2 text-lg"></i> PDF
                            </button>
                        </div>
                    </div>

                    <form id="filterForm" action="{{ route('reports.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                    <i class="fas fa-calendar-alt mr-1 text-gray-400"></i> Desde
                                </label>
                                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                    <i class="fas fa-calendar-alt mr-1 text-gray-400"></i> Hasta
                                </label>
                                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>

                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                    <i class="fas fa-building mr-1 text-gray-400"></i> Cliente
                                </label>
                                <select name="client_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Todos los Clientes</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                    <i class="fas fa-cubes mr-1 text-gray-400"></i> Material
                                </label>
                                <select name="material_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Todos los Materiales</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                    <i class="fas fa-truck mr-1 text-gray-400"></i> Unidad / Placa
                                </label>
                                <select name="unit_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Todas las Unidades</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->placa }} - {{ $unit->tipo_vehiculo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-end justify-end gap-3">
                                <a href="{{ route('reports.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm py-2 px-4 transition">
                                    Limpiar Filtros
                                </a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                    <i class="fas fa-search mr-2"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-gray-700 font-bold text-lg">Resultados</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $vales->total() }} Registros encontrados
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <i class="far fa-calendar mr-1"></i> Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Unidad</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($vales as $vale)
                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded border border-gray-300 font-mono">
                                        {{ $vale->folio_vale }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $vale->created_at->format('d/m/Y') }} <span class="text-xs text-gray-400">{{ $vale->created_at->format('H:i') }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $vale->sale->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                        {{ $vale->material->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($vale->unit)
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">{{ $vale->unit->placa }}</span>
                                            <span class="text-xs text-gray-400 uppercase">{{ $vale->unit->tipo_vehiculo }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Externa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClasses = [
                                            'Surtido' => 'bg-green-100 text-green-800 border-green-200',
                                            'En Planta' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'Vigente' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Vencido' => 'bg-red-100 text-red-800 border-red-200',
                                            'Cancelado' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        ];
                                        $currentClass = $statusClasses[$vale->estatus] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $currentClass }}">
                                        {{ $vale->estatus }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 rounded-full p-4 mb-3">
                                            <i class="fas fa-search text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-lg font-medium text-gray-900">No se encontraron resultados</p>
                                        <p class="text-sm text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $vales->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>