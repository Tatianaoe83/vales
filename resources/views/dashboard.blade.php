<x-app-layout>

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl text-gray-800 leading-tight" style="font-family:'Inter',sans-serif;font-weight:800;">
                Dashboard
            </h2>
            <div class="relative hidden md:block">
                <form action="{{ route('search.global') }}" method="GET">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="query" value="{{ request('query') }}"
                           class="w-64 py-2 pl-10 pr-4 bg-gray-100 border-none rounded-lg text-sm focus:ring-0"
                           style="font-family:'Inter',sans-serif;"
                           placeholder="Buscar por folio, cliente, rol..." required>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── KPI CARDS ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Vales Activos --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Vales Activos</p>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#eef1f8;">
                            <svg class="w-4 h-4" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl text-gray-800" style="font-weight:800;">{{ $totalValesActivos }}</p>
                    <p class="text-[11px] text-green-500 mt-2 flex items-center gap-1" style="font-weight:600;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        +5.2% <span class="text-gray-300 ml-1" style="font-weight:400;">vs mes anterior</span>
                    </p>
                </div>

                {{-- Ventas del Día --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Ventas del Día</p>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#eef1f8;">
                            <svg class="w-4 h-4" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl text-gray-800" style="font-weight:800;">${{ number_format($ventasHoy, 2) }}</p>
                    <p class="text-[11px] text-red-400 mt-2 flex items-center gap-1" style="font-weight:600;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        -1.8% <span class="text-gray-300 ml-1" style="font-weight:400;">vs ayer</span>
                    </p>
                </div>

                {{-- Entregas Pendientes --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Entregas Pendientes</p>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#eef1f8;">
                            <svg class="w-4 h-4" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl text-gray-800" style="font-weight:800;">{{ $entregasPendientes }}</p>
                    <p class="text-[11px] text-green-500 mt-2 flex items-center gap-1" style="font-weight:600;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        +2.0% <span class="text-gray-300 ml-1" style="font-weight:400;">flujo normal</span>
                    </p>
                </div>

                {{-- Nuevos Clientes --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Nuevos Clientes</p>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#eef1f8;">
                            <svg class="w-4 h-4" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl text-gray-800" style="font-weight:800;">{{ $nuevosClientes }}</p>
                    <p class="text-[11px] text-green-500 mt-2 flex items-center gap-1" style="font-weight:600;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        +10.0% <span class="text-gray-300 ml-1" style="font-weight:400;">crecimiento</span>
                    </p>
                </div>

                {{-- Satisfacción KPI --}}
                <div class="p-5 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden" style="background:#121f48;">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full pointer-events-none" style="background:rgba(255,255,255,.06);"></div>
                    <div class="absolute -right-2 bottom-2 w-14 h-14 rounded-full pointer-events-none" style="background:rgba(255,255,255,.04);"></div>

                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <p class="text-[10px] uppercase tracking-widest" style="font-weight:700; color:rgba(255,255,255,.5);">Satisfacción</p>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,.12);">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="relative z-10">
                        {{-- Carita según promedio --}}
                        @php
                            $avg = $promedioCalificacion ?? 0;
                            $kpiFace = match(true) {
                                $avg >= 4.0 => '🤩',
                                $avg >= 2.0 => '😐',
                                $avg >  0   => '😞',
                                default     => '—',
                            };
                        @endphp
                        <div class="flex items-end gap-2 mb-2">
                            <span class="text-3xl leading-none">{{ $kpiFace }}</span>
                            <div class="flex items-end gap-1 mb-0.5">
                                <p class="text-2xl text-white leading-none" style="font-weight:900;">
                                    {{ number_format($avg, 1) }}
                                </p>
                                <span class="text-sm mb-0.5" style="color:rgba(255,255,255,.45); font-weight:600;">/ 5</span>
                            </div>
                        </div>

                        <div class="w-full rounded-full h-1.5 mb-2.5 overflow-hidden" style="background:rgba(255,255,255,.15);">
                            <div class="h-1.5 bg-white rounded-full"
                                 id="kpiRatingBar"
                                 style="width:0%; transition: width 1.2s cubic-bezier(.4,0,.2,1);"
                                 data-width="{{ ($avg / 5) * 100 }}%">
                            </div>
                        </div>

                        <p class="text-[10px]" style="font-weight:500; color:rgba(255,255,255,.5);">
                            <span class="text-white" style="font-weight:700;">{{ $totalCalificaciones ?? 0 }}</span> esta semana
                        </p>
                    </div>
                </div>

            </div>

            {{-- ── ACCESOS RÁPIDOS ── --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h3 class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">Accesos Rápidos</h3>
                <div class="flex gap-3">
                    <a href="{{ route('clients.index') }}"
                       class="px-4 py-2 bg-white hover:bg-gray-50 border border-gray-200 text-gray-600 rounded-xl text-sm transition shadow-sm"
                       style="font-weight:600;">
                        Administrar Clientes
                    </a>
                    <a href="{{ route('sales.create') }}"
                       class="px-4 py-2 text-white rounded-xl text-sm shadow-md transition flex items-center gap-2"
                       style="font-weight:700; background:#121f48;"
                       onmouseover="this.style.opacity='.88'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Nuevo Vale
                    </a>
                </div>
            </div>

            {{-- ── GRÁFICA + ACTIVIDAD ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:700;">Ventas por Día — Últimos 7 días</h4>
                        <span class="text-[10px] px-2.5 py-1 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">Esta semana</span>
                    </div>
                    <div class="h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm text-gray-800 uppercase tracking-wide mb-5" style="font-weight:700;">Actividad Reciente</h4>
                    <div class="space-y-4">
                        @forelse($actividadReciente as $history)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-[10px]
                                {{ $history->estatus_nuevo == 'Vigente'   ? 'bg-blue-100 text-blue-500'    : '' }}
                                {{ $history->estatus_nuevo == 'Surtido'   ? 'bg-green-100 text-green-500'  : '' }}
                                {{ $history->estatus_nuevo == 'Cancelado' ? 'bg-red-100 text-red-500'      : '' }}
                                {{ $history->estatus_nuevo == 'En Planta' ? 'bg-indigo-100 text-indigo-500': '' }}">
                                @if($history->estatus_nuevo == 'Vigente')   <i class="fas fa-ticket-alt"></i> @endif
                                @if($history->estatus_nuevo == 'Surtido')   <i class="fas fa-check"></i>      @endif
                                @if($history->estatus_nuevo == 'En Planta') <i class="fas fa-truck"></i>      @endif
                                @if($history->estatus_nuevo == 'Cancelado') <i class="fas fa-times"></i>      @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700 truncate" style="font-weight:700;">
                                    {{ $history->estatus_nuevo == 'Vigente' ? 'Vale creado' : $history->estatus_nuevo }}
                                    <span class="text-gray-400" style="font-weight:400;">#{{ $history->vale->folio_vale }}</span>
                                </p>
                                <p class="text-[11px] text-gray-400 mt-0.5" style="font-weight:400;">
                                    {{ Str::limit($history->vale->sale->client->name ?? 'N/A', 22) }}
                                </p>
                            </div>
                            <span class="text-[10px] text-gray-300 whitespace-nowrap mt-0.5" style="font-weight:500;">
                                {{ $history->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @empty
                        <p class="text-xs text-gray-300 text-center py-6" style="font-weight:500;">Sin actividad reciente.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ── WIDGET CALIFICACIONES (3 caritas) ── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center shrink-0" style="background:#121f48;">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:700;">Satisfacción del Cliente</h4>
                    </div>
                    <span class="text-[10px] text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-full" style="font-weight:700;">
                        {{ $totalCalificaciones ?? 0 }} evaluaciones esta semana
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-50">

                    {{-- Anillo central --}}
                    <div class="flex flex-col items-center justify-center px-8 py-8">
                        <div class="relative w-32 h-32 mb-5">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#eef1f8" stroke-width="9"/>
                                <circle cx="50" cy="50" r="38" fill="none"
                                        stroke="#121f48"
                                        stroke-width="9"
                                        stroke-linecap="round"
                                        stroke-dasharray="238.76"
                                        stroke-dashoffset="{{ 238.76 - (238.76 * (($promedioCalificacion ?? 0) / 5)) }}"
                                        style="transition: stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1);"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl leading-none" style="font-weight:900; color:#121f48;">
                                    {{ number_format($promedioCalificacion ?? 0, 1) }}
                                </span>
                                <span class="text-[10px] text-gray-300 mt-0.5" style="font-weight:700;">DE 5.0</span>
                            </div>
                        </div>

                        @php
                            $avg = $promedioCalificacion ?? 0;
                            [$wLabel, $wFace, $wColor, $wBg, $wBorder] = match(true) {
                                $avg >= 4.0 => ['¡Genial!',  '🤩', '#16a34a', '#f0fdf4', '#bbf7d0'],
                                $avg >= 2.0 => ['Regular',   '😐', '#d97706', '#fffbeb', '#fde68a'],
                                $avg >  0   => ['Malo',      '😞', '#ef4444', '#fef2f2', '#fecaca'],
                                default     => ['Sin datos', '—',  '#9ca3af', '#f9fafb', '#e5e7eb'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full border" style="font-weight:700; color:{{ $wColor }}; background:{{ $wBg }}; border-color:{{ $wBorder }};">
                            <span>{{ $wFace }}</span> {{ $wLabel }}
                        </span>
                        <p class="text-[11px] text-gray-300 mt-2" style="font-weight:400;">Promedio esta semana</p>
                    </div>

                    {{-- Barras de 3 caritas --}}
                    <div class="px-8 py-8 md:col-span-2 flex flex-col justify-center">
                        <p class="text-[10px] text-gray-300 uppercase tracking-widest mb-6" style="font-weight:800;">Distribución de Respuestas</p>

                        @php
                            // Sistema de 3 calificaciones: 1=Malo, 3=Regular, 5=Genial
                            $distribucion = $distribucionCalificaciones ?? [];
                            $malo    = ($distribucion[1] ?? 0);
                            $regular = ($distribucion[3] ?? 0);
                            $genial  = ($distribucion[5] ?? 0);
                            $totalResp = max($malo + $regular + $genial, 1);

                            $faces = [
                                ['face'=>'🤩', 'label'=>'¡Genial!', 'count'=>$genial,  'color'=>'#16a34a', 'bar'=>'#16a34a', 'bg'=>'#f0fdf4'],
                                ['face'=>'😐', 'label'=>'Regular',  'count'=>$regular, 'color'=>'#d97706', 'bar'=>'#d97706', 'bg'=>'#fffbeb'],
                                ['face'=>'😞', 'label'=>'Malo',     'count'=>$malo,    'color'=>'#ef4444', 'bar'=>'#ef4444', 'bg'=>'#fef2f2'],
                            ];
                        @endphp

                        <div class="space-y-5">
                            @foreach($faces as $f)
                            @php $pct = round(($f['count'] / $totalResp) * 100); @endphp
                            <div class="flex items-center gap-4">

                                {{-- Carita + etiqueta --}}
                                <div class="flex items-center gap-2 w-24 shrink-0">
                                    <span class="text-xl leading-none">{{ $f['face'] }}</span>
                                    <span class="text-[10px] text-gray-500 truncate" style="font-weight:700;">{{ $f['label'] }}</span>
                                </div>

                                {{-- Barra --}}
                                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full rating-bar"
                                         style="width:0%; background:{{ $f['bar'] }}; transition: width 1.1s cubic-bezier(.4,0,.2,1);"
                                         data-width="{{ $pct }}%">
                                    </div>
                                </div>

                                {{-- Números --}}
                                <div class="flex items-center gap-2 w-20 shrink-0 justify-end">
                                    <span class="text-xs text-gray-700" style="font-weight:700;">{{ $f['count'] }}</span>
                                    <span class="text-[10px] text-gray-300">resp.</span>
                                    <span class="text-[10px] ml-1" style="font-weight:600; color:{{ $f['color'] }};">{{ $pct }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Ventas ($)',
            data: @json($chartData),
            backgroundColor: '#121f48',
            hoverBackgroundColor: '#0d1633',
            borderRadius: 6,
            barThickness: 28,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F3F4F6', borderDash: [3,3] },
                ticks: { color: '#9CA3AF', font: { family:'Inter', size:11, weight:'600' } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#9CA3AF', font: { family:'Inter', size:11, weight:'600' } }
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const kpi = document.getElementById('kpiRatingBar');
        if (kpi) kpi.style.width = kpi.dataset.width;
        document.querySelectorAll('.rating-bar').forEach(b => b.style.width = b.dataset.width);
    }, 250);
});
</script>

<style>* { font-family: 'Inter', sans-serif; }</style>
</x-app-layout>