<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight flex items-center gap-2" style="font-weight:700;">
            <i class="fas fa-chart-line" style="color:#121f48;"></i>
            {{ __('Reportes y Métricas') }}
        </h2>
    </x-slot>

    <div class="py-12" style="font-family:'Inter',sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── FILTROS ── --}}
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="md:flex md:items-center md:justify-between mb-6 border-b border-gray-100 pb-4">
                        <div>
                            <h1 class="text-xl text-gray-900" style="font-weight:800;">Filtros de Búsqueda</h1>
                            <p class="text-sm text-gray-500 mt-1" style="font-weight:400;">Selecciona los criterios para generar tu reporte.</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex gap-3">
                            <span class="text-xs text-gray-400 self-center mr-2 hidden md:block uppercase tracking-widest" style="font-weight:700;">Exportar:</span>
                            <button type="submit" form="filterForm" formaction="{{ route('reports.excel') }}"
                                    class="bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 py-2 px-4 rounded-xl inline-flex items-center transition shadow-sm text-sm"
                                    style="font-weight:700;">
                                <i class="fas fa-file-excel mr-2 text-lg"></i> Excel
                            </button>
                            <button type="submit" form="filterForm" formaction="{{ route('reports.pdf') }}"
                                    class="bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 py-2 px-4 rounded-xl inline-flex items-center transition shadow-sm text-sm"
                                    style="font-weight:700;">
                                <i class="fas fa-file-pdf mr-2 text-lg"></i> PDF
                            </button>
                        </div>
                    </div>

                    <form id="filterForm" action="{{ route('reports.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                            <div class="col-span-1 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    <i class="fas fa-calendar-alt mr-1"></i> Desde
                                </label>
                                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                                       class="block w-full rounded-xl border-gray-200 shadow-sm text-sm transition"
                                       style="focus:border-color:#121f48;">
                            </div>
                            <div class="col-span-1 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    <i class="fas fa-calendar-alt mr-1"></i> Hasta
                                </label>
                                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                                       class="block w-full rounded-xl border-gray-200 shadow-sm text-sm transition">
                            </div>

                            <div class="col-span-1 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    <i class="fas fa-building mr-1"></i> Cliente
                                </label>
                                <select name="client_id" class="block w-full rounded-xl border-gray-200 shadow-sm text-sm transition" style="font-weight:400;">
                                    <option value="">Todos los Clientes</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    <i class="fas fa-cubes mr-1"></i> Material
                                </label>
                                <select name="material_id" class="block w-full rounded-xl border-gray-200 shadow-sm text-sm transition" style="font-weight:400;">
                                    <option value="">Todos los Materiales</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    <i class="fas fa-truck mr-1"></i> Unidad / Placa
                                </label>
                                <select name="unit_id" class="block w-full rounded-xl border-gray-200 shadow-sm text-sm transition" style="font-weight:400;">
                                    <option value="">Todas las Unidades</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->placa }} - {{ $unit->tipo_vehiculo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-3 flex items-end justify-end gap-3">
                                <a href="{{ route('reports.index') }}"
                                   class="text-gray-400 hover:text-gray-600 text-sm py-2 px-4 transition rounded-xl hover:bg-gray-100"
                                   style="font-weight:600;">
                                    Limpiar Filtros
                                </a>
                                <button type="submit"
                                        class="text-white text-sm py-2 px-6 rounded-xl shadow-md transition hover:-translate-y-0.5 flex items-center gap-2"
                                        style="background:#121f48; font-weight:700;"
                                        onmouseover="this.style.background='#0d1633'"
                                        onmouseout="this.style.background='#121f48'">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── RESULTADOS ── --}}
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-gray-700 text-sm uppercase tracking-wide" style="font-weight:800;">Resultados</h3>
                    <span class="text-xs px-3 py-1 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                        {{ $vales->total() }} registros encontrados
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Folio</th>
                                <th class="px-6 py-3.5 text-left text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">
                                    <i class="far fa-calendar mr-1"></i> Fecha
                                </th>
                                <th class="px-6 py-3.5 text-left text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Cliente</th>
                                <th class="px-6 py-3.5 text-left text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Material</th>
                                <th class="px-6 py-3.5 text-left text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Unidad</th>
                                <th class="px-6 py-3.5 text-center text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($vales as $vale)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-mono px-2 py-1 rounded-md border"
                                          style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                                        {{ $vale->folio_vale }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" style="font-weight:500;">
                                    {{ $vale->created_at->format('d/m/Y') }}
                                    <span class="text-xs text-gray-300 ml-1">{{ $vale->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800" style="font-weight:700;">{{ $vale->sale->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" style="font-weight:500;">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full shrink-0" style="background:#121f48; opacity:.4;"></div>
                                        {{ $vale->material->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($vale->unit)
                                        <div class="flex flex-col">
                                            <span class="text-gray-800" style="font-weight:700;">{{ $vale->unit->placa }}</span>
                                            <span class="text-[10px] text-gray-400 uppercase tracking-wide">{{ $vale->unit->tipo_vehiculo }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 italic text-xs">Externa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusMap = [
                                            'Surtido'   => ['bg-green-50',  'text-green-700',  'border-green-200'],
                                            'En Planta' => ['bg-yellow-50', 'text-yellow-700', 'border-yellow-200'],
                                            'Vigente'   => ['bg-[#eef1f8]', 'text-[#121f48]',  'border-[#c8cedf]'],
                                            'Vencido'   => ['bg-red-50',    'text-red-700',    'border-red-200'],
                                            'Cancelado' => ['bg-gray-100',  'text-gray-500',   'border-gray-200'],
                                        ];
                                        $sc = $statusMap[$vale->estatus] ?? ['bg-gray-100', 'text-gray-600', 'border-gray-200'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-[10px] leading-5 rounded-full border {{ $sc[0] }} {{ $sc[1] }} {{ $sc[2] }}"
                                          style="font-weight:700;">
                                        {{ $vale->estatus }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-2xl p-4 mb-4" style="background:#eef1f8;">
                                            <i class="fas fa-search text-3xl" style="color:#121f48; opacity:.4;"></i>
                                        </div>
                                        <p class="text-base text-gray-700" style="font-weight:700;">No se encontraron resultados</p>
                                        <p class="text-sm text-gray-400 mt-1">Intenta ajustar los filtros de búsqueda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $vales->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>