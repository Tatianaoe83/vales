<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Gestión de Vales y Logística
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-4">
                
                <form action="{{ route('sales.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Buscar folio o cliente..." value="{{ request('search') }}" class="rounded-lg border-gray-300 text-sm w-64 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('sales.index') }}" class="text-gray-500 text-sm flex items-center hover:text-red-500">Limpiar</a>
                    @endif
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('vales.export', 'xlsx') }}" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 shadow flex items-center gap-2 transition" title="Descargar Reporte Excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    
                    <a href="{{ route('vales.export', 'csv') }}" class="bg-gray-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-gray-700 shadow flex items-center gap-2 transition" title="Descargar CSV">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>

                    <a href="{{ route('sales.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 shadow flex items-center gap-2 transition ml-2">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-100 text-gray-600 uppercase font-bold">
                            <tr>
                                <th class="px-4 py-3"></th> 
                                <th class="px-4 py-3">Folio Venta</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Fecha</th>
                                <th class="px-4 py-3 text-center">Logística</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        
                        @foreach($sales as $sale)
                        <tbody x-data="{ open: false }" class="border-b border-gray-100 hover:bg-gray-50 transition">
                            
                            <tr class="cursor-pointer group" @click="open = !open">
                                <td class="px-4 py-4 text-gray-400">
                                    <i class="fas fa-chevron-right transition-transform duration-200 group-hover:text-blue-400" :class="{'rotate-90 text-blue-600': open}"></i>
                                </td>
                                <td class="px-4 py-4 font-bold text-gray-800">
                                    {{ $sale->folio }}
                                    @if($sale->tipo_venta == 'Credito')
                                        <span class="ml-2 text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full border border-purple-200">Crédito</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ $sale->client->name }}</td>
                                <td class="px-4 py-4 text-gray-500">{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-bold shadow-sm">
                                        {{ $sale->vales->count() }} Viajes/Vales
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right font-bold text-gray-900">${{ number_format($sale->total, 2) }}</td>
                                
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank" @click.stop class="text-red-500 hover:text-red-700 transition" title="Ver PDF">
                                            <i class="fas fa-file-pdf fa-lg"></i>
                                        </a>
                                        
                                        <a href="{{ route('sales.email', $sale->id) }}" 
                                           @click.stop="enviarCorreo($event)"
                                           class="text-blue-500 hover:text-blue-700 transition" 
                                           title="Enviar Correo al Cliente">
                                            <i class="fas fa-envelope fa-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr x-show="open" x-transition.opacity class="bg-gray-50 shadow-inner">
                                <td colspan="7" class="p-4 pl-12 border-l-4 border-blue-500">
                                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-3 border-b pb-2 flex justify-between items-center">
                                            <span>Desglose de Logística ({{ $sale->vales->count() }} unidades)</span>
                                            <span class="text-[10px] font-normal text-gray-400">Gestione el flujo de cada camión aquí</span>
                                        </h4>
                                        
                                        <table class="w-full text-xs">
                                            <thead>
                                                <tr class="text-gray-500 border-b bg-gray-50">
                                                    <th class="py-2 px-2 text-left">Folio Vale</th>
                                                    <th class="py-2 px-2 text-left">Material</th>
                                                    <th class="py-2 px-2 text-center">Carga</th>
                                                    <th class="py-2 px-2 text-left">Unidad</th>
                                                    <th class="py-2 px-2 text-center">Estatus Actual</th>
                                                    <th class="py-2 px-2 text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sale->vales as $vale)
                                                <tr class="border-b last:border-0 hover:bg-blue-50 transition">
                                                    <td class="py-3 px-2 font-mono font-bold text-blue-600">{{ $vale->folio_vale }}</td>
                                                    <td class="py-3 px-2">{{ $vale->material->name }}</td>
                                                    <td class="py-3 px-2 text-center font-bold">{{ $vale->cantidad }} {{ $vale->material->unit }}</td>
                                                    <td class="py-3 px-2">
                                                        @if($vale->unit)
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-truck text-gray-400"></i> 
                                                                <span class="font-semibold">{{ $vale->unit->placa }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-400 italic">Unidad Externa</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-2 text-center">
                                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold border
                                                            {{ $vale->estatus == 'Vigente' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                            {{ $vale->estatus == 'En Planta' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                                            {{ $vale->estatus == 'Surtido' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                                            {{ $vale->estatus == 'Vencido' ? 'bg-orange-100 text-orange-800 border-orange-200' : '' }}
                                                            {{ $vale->estatus == 'Cancelado' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                                            {{ $vale->estatus }}
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="py-3 px-2 text-center flex items-center justify-center gap-2">
                                                        
                                                        {{-- 1. Si está VIGENTE -> Dar Entrada --}}
                                                        @if($vale->estatus == 'Vigente')
                                                            <form action="{{ route('vales.status', $vale->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="estatus" value="En Planta">
                                                                <button type="submit" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded-md shadow-sm text-[10px] font-bold transition flex items-center gap-1 border border-yellow-200">
                                                                    <i class="fas fa-sign-in-alt"></i> Entrada
                                                                </button>
                                                            </form>

                                                        {{-- 2. Si está EN PLANTA -> Surtir --}}
                                                        @elseif($vale->estatus == 'En Planta')
                                                            <form action="{{ route('vales.status', $vale->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <input type="hidden" name="estatus" value="Surtido">
                                                                <button type="submit" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-md shadow-sm text-[10px] font-bold transition flex items-center gap-1 border border-blue-200">
                                                                    <i class="fas fa-check"></i> Surtir
                                                                </button>
                                                            </form>

                                                        {{-- 3. Si está VENCIDO -> RESTABLECER --}}
                                                        @elseif($vale->estatus == 'Vencido')
                                                            <form action="{{ route('vales.restore', $vale->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirmas reactivar este vale? Se extenderá la vigencia 15 días.')">
                                                                @csrf
                                                                <button type="submit" class="bg-green-100 hover:bg-green-200 text-green-800 px-3 py-1 rounded-md shadow-sm text-[10px] font-bold transition flex items-center gap-1 border border-green-200">
                                                                    <i class="fas fa-redo-alt"></i> Restablecer
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <button type="button" onclick="verHistorial({{ $vale->id }}, '{{ $vale->folio_vale }}')" 
                                                                class="text-gray-400 hover:text-blue-600 transition p-1" 
                                                                title="Ver Historial de Movimientos">
                                                            <i class="fas fa-history"></i>
                                                        </button>

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

                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-100 m-4">
            
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Historial de Movimientos</h3>
                    <p class="text-xs text-gray-500 font-mono" id="modalFolio">Folio: ---</p>
                </div>
                <button onclick="closeHistory()" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 max-h-[60vh] overflow-y-auto bg-gray-50" id="historyContent">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
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
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }

        function verHistorial(id, folio) {
            document.getElementById('historyModal').classList.remove('hidden');
            document.getElementById('modalFolio').innerText = 'Folio: ' + folio;
            
            const container = document.getElementById('historyContent');
            container.innerHTML = '<div class="text-center py-10 text-gray-400"><i class="fas fa-circle-notch fa-spin text-3xl"></i><p class="mt-2 text-xs">Consultando bitácora...</p></div>';

            fetch(`/vales/${id}/history`)
                .then(res => res.json())
                .then(data => {
                    if(data.length === 0) {
                        container.innerHTML = '<p class="text-center text-gray-400">Sin movimientos registrados.</p>';
                        return;
                    }

                    let html = '<div class="relative border-l-2 border-gray-200 ml-3 space-y-6">';
                    
                    data.forEach(item => {
                        const fecha = new Date(item.created_at).toLocaleString('es-MX', { day: '2-digit', month: 'short', hour: '2-digit', minute:'2-digit' });
                        
                        let color = 'bg-gray-200';
                        if(item.estatus_nuevo === 'Vigente') color = 'bg-green-500';
                        if(item.estatus_nuevo === 'En Planta') color = 'bg-yellow-500';
                        if(item.estatus_nuevo === 'Surtido') color = 'bg-blue-600';
                        if(item.estatus_nuevo === 'Vencido') color = 'bg-orange-500';
                        if(item.estatus_nuevo === 'Cancelado') color = 'bg-red-500';

                        html += `
                        <div class="relative pl-8">
                            <span class="absolute -left-[9px] top-1 h-5 w-5 rounded-full border-4 border-white ${color}"></span>
                            <div class="bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                <div class="flex justify-between items-start">
                                    <span class="font-bold text-xs uppercase text-gray-700">${item.estatus_nuevo}</span>
                                    <span class="text-[10px] text-gray-400">${fecha}</span>
                                </div>
                                <p class="text-sm text-gray-700 font-medium mt-1">${item.comentarios || 'Sin comentarios'}</p>
                                <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
                                    <i class="fas fa-user-circle"></i> ${item.user ? item.user.name : 'Sistema'}
                                </p>
                            </div>
                        </div>`;
                    });

                    html += '</div>';
                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = '<p class="text-center text-red-400">Error al cargar historial.</p>';
                });
        }

        function closeHistory() {
            document.getElementById('historyModal').classList.add('hidden');
        }
    </script>
</x-app-layout>