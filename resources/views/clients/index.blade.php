<x-app-layout>

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

<div class="py-12 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl text-gray-800 tracking-tight" style="font-weight:800;">Cartera de Clientes</h1>
                <p class="text-gray-500 text-sm mt-1" style="font-weight:400;">Gestiona la información y estado de tus socios comerciales.</p>
            </div>
            <a href="{{ route('clients.create') }}"
               class="group text-white py-2.5 px-5 rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2"
               style="font-weight:700; background:#121f48;">
                <div class="p-1 rounded-lg" style="background:rgba(255,255,255,.15);">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                Nuevo Cliente
            </a>
        </div>

        {{-- TABLA --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
            @if($clients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs tracking-wider text-gray-400 uppercase bg-gray-50 border-b border-gray-100" style="font-weight:700;">
                                <th class="px-6 py-5">Cliente / Razón Social</th>
                                <th class="px-6 py-5">RFC</th>
                                <th class="px-6 py-5">Contacto</th>
                                <th class="px-6 py-5 text-center">Estado</th>
                                <th class="px-6 py-5 text-center">Compras</th>
                                <th class="px-6 py-5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($clients as $client)
                            <tr class="transition duration-200 group hover:bg-gray-50/80">

                                {{-- Nombre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-sm text-white" style="background:#121f48; font-weight:800;">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm text-gray-900" style="font-weight:700;">{{ $client->name }}</div>
                                            <div class="text-xs text-gray-400" style="font-weight:400;">ID: {{ $client->id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- RFC --}}
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 rounded-md bg-gray-100 text-gray-600 font-mono tracking-wide" style="font-weight:600;">
                                        {{ $client->rfc ?? 'SIN RFC' }}
                                    </span>
                                </td>

                                {{-- Contacto --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 flex items-center gap-2" style="font-weight:500;">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $client->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-2" style="font-weight:400;">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $client->phone ?? 'Sin teléfono' }}
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4 text-center">
                                    @if($client->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-green-50 text-green-700 border border-green-200" style="font-weight:600;">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-red-50 text-red-700 border border-red-200" style="font-weight:600;">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- Historial --}}
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                            onclick="openHistory({{ $client->id }}, '{{ addslashes($client->name) }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all text-xs border"
                                            style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Historial
                                    </button>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-3">

                                        <a href="{{ route('clients.edit', $client->id) }}"
                                           class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-white hover:border-transparent transition shadow-sm"
                                           style="transition:all .15s;"
                                           onmouseover="this.style.background='#121f48'; this.style.color='white';"
                                           onmouseout="this.style.background='white'; this.style.color='#6b7280';"
                                           title="Editar Cliente">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>

                                        @if($client->is_active)
                                            <form id="deactivate-form-{{ $client->id }}" action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:none;">
                                                @csrf @method('DELETE')
                                            </form>
                                            <button type="button"
                                                    onclick="Swal.fire({ title:'¿Desactivar Cliente?', text:'Sus datos permanecerán pero no podrá operar.', icon:'warning', showCancelButton:true, confirmButtonColor:'#F59E0B', cancelButtonColor:'#d33', confirmButtonText:'Sí, desactivar', cancelButtonText:'Cancelar' }).then(r=>{ if(r.isConfirmed) document.getElementById('deactivate-form-{{ $client->id }}').submit(); })"
                                                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-orange-500 hover:border-orange-300 hover:bg-orange-50 transition shadow-sm" title="Desactivar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button disabled class="p-2 bg-gray-50 border border-gray-100 rounded-lg text-gray-300 cursor-not-allowed">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            </button>
                                        @endif

                                        <form id="force-delete-form-{{ $client->id }}" action="{{ route('clients.forceDelete', $client->id) }}" method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button type="button"
                                                onclick="confirmDelete('force-delete-form-{{ $client->id }}')"
                                                class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $clients->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="inline-block p-4 rounded-full mb-4" style="background:#eef1f8;">
                        <svg class="w-12 h-12" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg text-gray-900" style="font-weight:600;">No hay clientes registrados</h3>
                    <p class="mt-1 text-gray-500">Comienza agregando tu primer cliente al sistema.</p>
                    <div class="mt-6">
                        <a href="{{ route('clients.create') }}" style="color:#121f48; font-weight:600;">+ Agregar Cliente</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL HISTORIAL --}}
<div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display:none !important; font-family:'Inter',sans-serif;">

    <div class="absolute inset-0 bg-gray-800/40 backdrop-blur-sm" onclick="closeHistory()"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden z-10 flex flex-col" style="max-height:90vh;">

        {{-- Header --}}
        <div class="px-6 py-5 flex items-start justify-between gap-4 shrink-0" style="background:#121f48;">
            <div>
                <p class="text-[10px] uppercase tracking-widest mb-0.5" style="font-weight:800; color:rgba(255,255,255,.45);">Historial de Compras</p>
                <h3 id="historyModalTitle" class="text-xl text-white leading-tight" style="font-weight:800;">—</h3>
            </div>
            <button onclick="closeHistory()" class="w-8 h-8 flex items-center justify-center rounded-xl text-white shrink-0" style="background:rgba(255,255,255,.12);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-px bg-gray-100 border-b border-gray-100 shrink-0">
            <div class="bg-white px-5 py-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1" style="font-weight:700;">Total Compras</p>
                <p id="statCount" class="text-2xl text-gray-800" style="font-weight:800;">—</p>
            </div>
            <div class="bg-white px-5 py-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1" style="font-weight:700;">Monto Total</p>
                <p id="statTotal" class="text-2xl" style="font-weight:800; color:#121f48;">—</p>
            </div>
            <div class="bg-white px-5 py-4 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1" style="font-weight:700;">Satisfacción Prom.</p>
                <p id="statRating" class="text-2xl text-gray-800" style="font-weight:800;">—</p>
            </div>
        </div>

        {{-- Spinner --}}
        <div id="historySpinner" class="flex flex-col items-center justify-center py-16">
            <div class="w-10 h-10 border-4 border-gray-200 rounded-full animate-spin mb-4" style="border-top-color:#121f48;"></div>
            <p class="text-sm text-gray-400" style="font-weight:500;">Cargando historial...</p>
        </div>

        {{-- Tabla --}}
        <div id="historyContent" class="overflow-y-auto flex-1" style="display:none;">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10">
                    <tr class="text-[10px] text-gray-400 uppercase tracking-wider bg-gray-50 border-b border-gray-100" style="font-weight:800;">
                        <th class="px-5 py-3.5">Folio</th>
                        <th class="px-5 py-3.5">Fecha</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5 text-right">Subtotal</th>
                        <th class="px-5 py-3.5 text-right">IVA</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                        <th class="px-5 py-3.5 text-center">Satisfacción</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody" class="divide-y divide-gray-50"></tbody>
            </table>
        </div>

        {{-- Vacío --}}
        <div id="historyEmpty" class="flex flex-col items-center justify-center py-16" style="display:none;">
            <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-sm text-gray-400 uppercase tracking-wide" style="font-weight:700;">Sin compras registradas</p>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex justify-end shrink-0">
            <button onclick="closeHistory()" class="px-5 py-2 bg-white border border-gray-200 hover:bg-gray-100 text-gray-600 rounded-xl transition text-sm" style="font-weight:600;">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
const historyRoute = "{{ route('clients.salesHistory', ':id') }}";

// Sistema de 3 calificaciones: 1 = Malo, 3 = Regular, 5 = Excelente
const RATING_MAP = {
    1: { face: '😞', label: 'Malo',     color: '#ef4444', bg: '#fef2f2', border: '#fecaca' },
    3: { face: '😐', label: 'Regular',  color: '#d97706', bg: '#fffbeb', border: '#fde68a' },
    5: { face: '🤩', label: '¡Genial!', color: '#16a34a', bg: '#f0fdf4', border: '#bbf7d0' },
};

// Mapea cualquier valor numérico al más cercano de {1, 3, 5}
function nearestRating(val) {
    return [1, 3, 5].reduce((prev, curr) =>
        Math.abs(curr - val) < Math.abs(prev - val) ? curr : prev
    );
}

function buildRatingHtml(calif) {
    if (calif === null || calif === undefined || calif === '') {
        return '<span class="text-[11px] text-gray-300 italic" style="font-weight:500;">Sin calificar</span>';
    }
    const r = RATING_MAP[nearestRating(parseFloat(calif))];
    return `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs border"
                  style="font-weight:700; color:${r.color}; background:${r.bg}; border-color:${r.border};">
                <span style="font-size:1rem; line-height:1;">${r.face}</span>
                ${r.label}
            </span>`;
}

function buildStatRating(promCalif) {
    if (promCalif === 'N/A') return '<span class="text-gray-400">N/A</span>';
    const r = RATING_MAP[nearestRating(parseFloat(promCalif))];
    return `<span style="color:${r.color};">${r.face} ${promCalif}</span>`;
}

function openHistory(clientId, clientName) {
    const modal = document.getElementById('historyModal');
    modal.style.removeProperty('display');
    modal.style.display = 'flex';

    document.getElementById('historyModalTitle').textContent = clientName;
    document.getElementById('statCount').textContent  = '—';
    document.getElementById('statTotal').textContent  = '—';
    document.getElementById('statRating').innerHTML   = '—';
    document.getElementById('historySpinner').style.display = 'flex';
    document.getElementById('historyContent').style.display = 'none';
    document.getElementById('historyEmpty').style.display   = 'none';

    fetch(historyRoute.replace(':id', clientId), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('historySpinner').style.display = 'none';

        if (!data.sales || data.sales.length === 0) {
            document.getElementById('historyEmpty').style.display = 'flex';
            document.getElementById('statCount').textContent  = '0';
            document.getElementById('statTotal').textContent  = '$0.00';
            document.getElementById('statRating').innerHTML   = 'N/A';
            return;
        }

        const totalMonto = data.sales.reduce((s, v) => s + parseFloat(v.total || 0), 0);
        const califs     = data.sales.filter(v => v.calificacion !== null && v.calificacion !== '');
        const promCalif  = califs.length > 0
            ? (califs.reduce((s, v) => s + parseFloat(v.calificacion), 0) / califs.length).toFixed(1)
            : 'N/A';

        document.getElementById('statCount').textContent = data.sales.length;
        document.getElementById('statTotal').textContent = '$' + totalMonto.toLocaleString('es-MX', { minimumFractionDigits:2, maximumFractionDigits:2 });
        document.getElementById('statRating').innerHTML  = buildStatRating(promCalif);

        const tbody = document.getElementById('historyTableBody');
        tbody.innerHTML = '';
        data.sales.forEach(sale => {
            const fecha    = sale.created_at ? sale.created_at.substring(0, 10) : '—';
            const tipo     = sale.tipo_venta || '—';
            const subtotal = parseFloat(sale.subtotal || 0);
            const iva      = parseFloat(sale.iva      || 0);
            const total    = parseFloat(sale.total    || 0);
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50/80 transition-colors';
            row.innerHTML = `
                <td class="px-5 py-3.5">
                    <span class="font-mono text-xs px-2 py-0.5 rounded-md" style="font-weight:700; color:#121f48; background:#eef1f8;">${sale.folio || '#' + sale.id}</span>
                </td>
                <td class="px-5 py-3.5 text-xs text-gray-500" style="font-weight:500;">${fecha}</td>
                <td class="px-5 py-3.5">
                    <span class="text-xs px-2 py-0.5 rounded-full border ${tipo === 'Contado' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-blue-50 text-blue-700 border-blue-200'}" style="font-weight:600;">${tipo}</span>
                </td>
                <td class="px-5 py-3.5 text-right text-xs text-gray-500" style="font-weight:500;">$${subtotal.toLocaleString('es-MX',{minimumFractionDigits:2})}</td>
                <td class="px-5 py-3.5 text-right text-xs text-gray-400">$${iva.toLocaleString('es-MX',{minimumFractionDigits:2})}</td>
                <td class="px-5 py-3.5 text-right text-sm text-gray-800" style="font-weight:700;">$${total.toLocaleString('es-MX',{minimumFractionDigits:2})}</td>
                <td class="px-5 py-3.5 text-center">${buildRatingHtml(sale.calificacion)}</td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById('historyContent').style.display = 'block';
    })
    .catch(() => {
        document.getElementById('historySpinner').style.display = 'none';
        document.getElementById('historyEmpty').style.display   = 'flex';
    });
}

function closeHistory() {
    document.getElementById('historyModal').style.display = 'none';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeHistory(); });
</script>

<style>* { font-family: 'Inter', sans-serif; }</style>
</x-app-layout>