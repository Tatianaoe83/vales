<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Gestión de Vales y Logística
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ── Toolbar ── --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-5">

                {{-- Búsqueda --}}
                <form action="{{ route('sales.index') }}" method="GET" class="flex gap-2 flex-1">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xs"></i>
                        </div>
                        <input type="text" name="search"
                               placeholder="Buscar folio o cliente..."
                               value="{{ request('search') }}"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <button type="submit"
                            class="bg-gray-800 text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-700 transition shadow-sm shrink-0">
                        Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('sales.index') }}"
                           class="text-gray-400 text-sm flex items-center hover:text-red-500 transition shrink-0">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                {{-- Acciones --}}
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('vales.export', 'xlsx') }}"
                       class="flex items-center gap-1.5 bg-green-600 text-white px-3 py-2.5 rounded-xl text-xs font-black hover:bg-green-700 shadow-sm transition">
                        <i class="fas fa-file-excel"></i>
                        <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('vales.export', 'csv') }}"
                       class="flex items-center gap-1.5 bg-gray-600 text-white px-3 py-2.5 rounded-xl text-xs font-black hover:bg-gray-700 shadow-sm transition">
                        <i class="fas fa-file-csv"></i>
                        <span class="hidden sm:inline">CSV</span>
                    </a>
                    <a href="{{ route('sales.create') }}"
                       class="flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2.5 rounded-xl text-xs font-black hover:bg-blue-700 shadow-sm transition">
                        <i class="fas fa-plus"></i>
                        <span>Nueva Venta</span>
                    </a>
                </div>
            </div>

            {{-- ════════════════════════════════════
                 DESKTOP: tabla (md+)
            ════════════════════════════════════ --}}
            <div class="hidden md:block bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="px-5 py-4 w-8"></th>
                            <th class="px-5 py-4">Folio</th>
                            <th class="px-5 py-4">Cliente</th>
                            <th class="px-5 py-4">Fecha</th>
                            <th class="px-5 py-4 text-center">Logística</th>
                            <th class="px-5 py-4 text-right">Total</th>
                            <th class="px-5 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>

                    @foreach($sales as $sale)
                    <tbody x-data="{ open: false }" class="border-b border-gray-50 last:border-0">
                        <tr class="cursor-pointer group hover:bg-blue-50/40 transition-colors" @click="open = !open">
                            <td class="px-5 py-4">
                                <div class="w-6 h-6 flex items-center justify-center rounded-lg transition-all duration-200"
                                     :class="open ? 'bg-blue-600 text-white rotate-90' : 'bg-gray-100 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-500'">
                                    <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"></i>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-black text-gray-800 text-sm font-mono">{{ $sale->folio }}</p>
                                @if($sale->tipo_venta == 'Credito')
                                    <span class="text-[9px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-black border border-purple-200">Crédito</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-700 text-sm">{{ $sale->client->name }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-xs font-medium">
                                {{ $sale->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-100 py-1 px-3 rounded-full text-[11px] font-black">
                                    <i class="fas fa-truck text-[9px]"></i>
                                    {{ $sale->vales->count() }} vales
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <p class="font-black text-gray-800">${{ number_format($sale->total, 2) }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank" @click.stop
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition" title="PDF">
                                        <i class="fas fa-file-pdf text-sm"></i>
                                    </a>
                                    <a href="{{ route('sales.email', $sale->id) }}" @click.stop="enviarCorreo($event)"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 transition" title="Correo">
                                        <i class="fas fa-envelope text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- Detalle vales desktop --}}
                        <tr x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <td colspan="7" class="px-5 pb-4 pt-0 bg-blue-50/30">
                                <div class="bg-white rounded-xl border border-blue-100 overflow-hidden shadow-sm ml-8">
                                    <table class="w-full text-xs">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                                <th class="py-2.5 px-4 text-left">Folio Vale</th>
                                                <th class="py-2.5 px-4 text-left">Material</th>
                                                <th class="py-2.5 px-4 text-center">Carga</th>
                                                <th class="py-2.5 px-4 text-left">Unidad</th>
                                                <th class="py-2.5 px-4 text-center">Estatus</th>
                                                <th class="py-2.5 px-4 text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sale->vales as $vale)
                                            <tr class="border-b last:border-0 hover:bg-blue-50/40 transition-colors">
                                                <td class="py-3 px-4 font-mono font-black text-blue-600 text-xs">{{ $vale->folio_vale }}</td>
                                                <td class="py-3 px-4 text-gray-700 font-semibold">{{ $vale->material->name }}</td>
                                                <td class="py-3 px-4 text-center font-black text-gray-800">
                                                    {{ $vale->cantidad }} {{ $vale->material->unit }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if($vale->unit)
                                                        <div class="flex items-center gap-1.5">
                                                            <i class="fas fa-truck text-gray-400 text-[10px]"></i>
                                                            <span class="font-semibold text-gray-700">{{ $vale->unit->placa }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 italic">Unidad Externa</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <span class="inline-block px-2 py-1 rounded-full text-[10px] font-black border
                                                        {{ $vale->estatus == 'Vigente'   ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                        {{ $vale->estatus == 'En Planta' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                                        {{ $vale->estatus == 'Surtido'   ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                                        {{ $vale->estatus == 'Vencido'   ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                                        {{ $vale->estatus == 'Cancelado' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                                        {{ $vale->estatus }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        @if($vale->estatus == 'Vigente')
                                                            <form action="{{ route('vales.status', $vale->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="estatus" value="En Planta">
                                                                <button type="submit" class="flex items-center gap-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-2.5 py-1 rounded-lg text-[10px] font-black transition border border-yellow-200">
                                                                    <i class="fas fa-sign-in-alt"></i> Entrada
                                                                </button>
                                                            </form>
                                                        @elseif($vale->estatus == 'En Planta')
                                                            <form action="{{ route('vales.status', $vale->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="estatus" value="Surtido">
                                                                <button type="submit" class="flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-2.5 py-1 rounded-lg text-[10px] font-black transition border border-blue-200">
                                                                    <i class="fas fa-check"></i> Surtir
                                                                </button>
                                                            </form>
                                                        @elseif($vale->estatus == 'Vencido')
                                                            <form action="{{ route('vales.restore', $vale->id) }}" method="POST"
                                                                  onsubmit="return confirm('¿Confirmas reactivar este vale?')">
                                                                @csrf
                                                                <button type="submit" class="flex items-center gap-1 bg-green-100 hover:bg-green-200 text-green-800 px-2.5 py-1 rounded-lg text-[10px] font-black transition border border-green-200">
                                                                    <i class="fas fa-redo-alt"></i> Restablecer
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <button type="button"
                                                                onclick="verHistorial({{ $vale->id }}, '{{ $vale->folio_vale }}')"
                                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-blue-600 transition">
                                                            <i class="fas fa-history text-xs"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    @endforeach
                </table>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $sales->links() }}
                </div>
            </div>

            {{-- ════════════════════════════════════
                 MOBILE: tarjetas (< md)
            ════════════════════════════════════ --}}
            <div class="md:hidden space-y-3">
                @foreach($sales as $sale)
                <div x-data="{ open: false }"
                     class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    {{-- Tarjeta cabecera --}}
                    <div class="p-4 cursor-pointer" @click="open = !open">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-black text-gray-800 font-mono text-sm truncate">{{ $sale->folio }}</p>
                                    @if($sale->tipo_venta == 'Credito')
                                        <span class="shrink-0 text-[9px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-black border border-purple-200">Crédito</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 font-semibold truncate">{{ $sale->client->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sale->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-gray-800 text-base">${{ number_format($sale->total, 2) }}</p>
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-100 py-0.5 px-2 rounded-full text-[10px] font-black mt-1">
                                    <i class="fas fa-truck text-[8px]"></i>
                                    {{ $sale->vales->count() }} vales
                                </span>
                            </div>
                        </div>

                        {{-- Footer tarjeta --}}
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank" @click.stop
                                   class="flex items-center gap-1.5 bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-black hover:bg-red-100 transition">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('sales.email', $sale->id) }}" @click.stop="enviarCorreo($event)"
                                   class="flex items-center gap-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-black hover:bg-blue-100 transition">
                                    <i class="fas fa-envelope"></i> Correo
                                </a>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs font-bold transition"
                                 :class="open ? 'text-blue-600' : 'text-gray-400'">
                                <span x-text="open ? 'Ocultar' : 'Ver vales'"></span>
                                <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"
                                   :class="open ? 'rotate-180' : ''"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Vales en mobile --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="border-t border-gray-100 bg-gray-50/50 divide-y divide-gray-100">

                        @foreach($sale->vales as $vale)
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <p class="font-black text-blue-600 font-mono text-xs">{{ $vale->folio_vale }}</p>
                                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $vale->material->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $vale->cantidad }} {{ $vale->material->unit }}
                                        @if($vale->unit)
                                            · <i class="fas fa-truck"></i> {{ $vale->unit->placa }}
                                        @else
                                            · <span class="italic">Unidad Externa</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2 py-1 rounded-full text-[10px] font-black border
                                        {{ $vale->estatus == 'Vigente'   ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                        {{ $vale->estatus == 'En Planta' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                        {{ $vale->estatus == 'Surtido'   ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                        {{ $vale->estatus == 'Vencido'   ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                        {{ $vale->estatus == 'Cancelado' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                        {{ $vale->estatus }}
                                    </span>
                                </div>
                            </div>

                            {{-- Acciones vale mobile --}}
                            <div class="flex items-center gap-2">
                                @if($vale->estatus == 'Vigente')
                                    <form action="{{ route('vales.status', $vale->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="estatus" value="En Planta">
                                        <button type="submit"
                                                class="flex items-center gap-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1.5 rounded-lg text-xs font-black transition border border-yellow-200">
                                            <i class="fas fa-sign-in-alt"></i> Entrada
                                        </button>
                                    </form>
                                @elseif($vale->estatus == 'En Planta')
                                    <form action="{{ route('vales.status', $vale->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="estatus" value="Surtido">
                                        <button type="submit"
                                                class="flex items-center gap-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1.5 rounded-lg text-xs font-black transition border border-blue-200">
                                            <i class="fas fa-check"></i> Surtir
                                        </button>
                                    </form>
                                @elseif($vale->estatus == 'Vencido')
                                    <form action="{{ route('vales.restore', $vale->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Confirmas reactivar este vale?')">
                                        @csrf
                                        <button type="submit"
                                                class="flex items-center gap-1.5 bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1.5 rounded-lg text-xs font-black transition border border-green-200">
                                            <i class="fas fa-redo-alt"></i> Restablecer
                                        </button>
                                    </form>
                                @endif

                                <button type="button"
                                        onclick="verHistorial({{ $vale->id }}, '{{ $vale->folio_vale }}')"
                                        class="flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-black transition">
                                    <i class="fas fa-history"></i> Historial
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="py-2">
                    {{ $sales->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- ── Modal Historial ── --}}
    <div id="historyModal"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 hidden backdrop-blur-sm"
         onclick="if(event.target===this) closeHistory()">
        <div class="bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-2xl shadow-2xl overflow-hidden">

            <div class="flex items-start justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5">Bitácora de movimientos</p>
                    <p class="text-sm font-black text-gray-800 font-mono" id="modalFolio">—</p>
                </div>
                <button onclick="closeHistory()"
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 hover:bg-red-100 hover:text-red-500 text-gray-500 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="p-5 max-h-[60dvh] overflow-y-auto bg-gray-50" id="historyContent">
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-circle-notch fa-spin text-2xl"></i>
                    <p class="mt-2 text-xs">Cargando...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function enviarCorreo(event) {
            Swal.fire({
                title: 'Enviando Correo...',
                text: 'Por favor espere un momento.',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        function verHistorial(id, folio) {
            document.getElementById('historyModal').classList.remove('hidden');
            document.getElementById('modalFolio').innerText = folio;
            const c = document.getElementById('historyContent');
            c.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-circle-notch fa-spin text-2xl"></i><p class="mt-2 text-xs">Consultando bitácora...</p></div>';

            fetch(`/vales/${id}/history`)
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        c.innerHTML = '<p class="text-center text-gray-400 text-sm py-8">Sin movimientos registrados.</p>';
                        return;
                    }
                    const colors = {
                        'Vigente': 'bg-green-500', 'En Planta': 'bg-yellow-500',
                        'Surtido': 'bg-blue-600',  'Vencido': 'bg-orange-500', 'Cancelado': 'bg-red-500'
                    };
                    let html = '<div class="relative border-l-2 border-gray-200 ml-3 space-y-4">';
                    data.forEach(item => {
                        const fecha = new Date(item.created_at).toLocaleString('es-MX', { day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit' });
                        const dot   = colors[item.estatus_nuevo] || 'bg-gray-300';
                        html += `
                        <div class="relative pl-7">
                            <span class="absolute -left-[9px] top-1.5 h-4 w-4 rounded-full border-4 border-white ${dot}"></span>
                            <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                <div class="flex justify-between items-start gap-2">
                                    <span class="font-black text-xs uppercase text-gray-700">${item.estatus_nuevo}</span>
                                    <span class="text-[10px] text-gray-400 shrink-0">${fecha}</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">${item.comentarios || 'Sin comentarios'}</p>
                                <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
                                    <i class="fas fa-user-circle"></i> ${item.user ? item.user.name : 'Sistema'}
                                </p>
                            </div>
                        </div>`;
                    });
                    c.innerHTML = html + '</div>';
                })
                .catch(() => { c.innerHTML = '<p class="text-center text-red-400 text-sm py-8">Error al cargar historial.</p>'; });
        }

        function closeHistory() {
            document.getElementById('historyModal').classList.add('hidden');
        }
    </script>
</x-app-layout>