<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;" x-data="materialTable()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl text-gray-800 tracking-tight" style="font-weight:800;">Catálogo de Materiales</h1>
                    <p class="text-gray-500 text-sm mt-1">Gestiona precios, unidades y existencias.</p>
                </div>
                <a href="{{ route('materials.create') }}"
                   class="group text-white py-2.5 px-5 rounded-xl shadow-lg transition-all duration-200 flex items-center"
                   style="background:#121f48;"
                   onmouseover="this.style.background='#0d1633'"
                   onmouseout="this.style.background='#121f48'">
                    <div class="p-1 rounded-lg mr-2 transition" style="background:rgba(255,255,255,.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span style="font-weight:700;">Nuevo Material</span>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                @if($materials->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50 border-b border-gray-100" style="font-weight:800;">
                                    <th class="px-6 py-5">Material / Código</th>
                                    <th class="px-6 py-5">Unidad</th>
                                    <th class="px-6 py-5">Precio Actual</th>
                                    <th class="px-6 py-5">Stock</th>
                                    <th class="px-6 py-5 text-center">Estado</th>
                                    <th class="px-6 py-5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($materials as $material)
                                <tr class="hover:bg-gray-50/80 transition duration-150 group">

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl flex items-center justify-center text-lg border"
                                                 style="background:#eef1f8; border-color:#c8cedf; color:#121f48; font-weight:800;">
                                                {{ substr($material->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-900" style="font-weight:700;">{{ $material->name }}</div>
                                                <div class="text-xs text-gray-400 font-mono">{{ $material->code ?? 'S/C' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-lg border"
                                              style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                                            {{ $material->unit }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center text-green-700 font-bold font-mono bg-green-50 px-3 py-1.5 rounded-lg border border-green-200 w-fit">
                                            <span>$</span>
                                            <span class="ml-1">{{ number_format($material->price, 2) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700" style="font-weight:600;">
                                        {{ $material->stock }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($material->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs border bg-green-50 text-green-700 border-green-200" style="font-weight:700;">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs border bg-red-50 text-red-700 border-red-200" style="font-weight:700;">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Baja
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <button @click="openHistory({{ $material->id }}, '{{ $material->name }}')"
                                                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-purple-600 hover:border-purple-300 hover:bg-purple-50 transition shadow-sm"
                                                    title="Ver Historial">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </button>

                                            <a href="{{ route('materials.edit', $material->id) }}"
                                               class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 transition shadow-sm"
                                               title="Editar"
                                               onmouseover="this.style.background='#eef1f8'; this.style.borderColor='#c8cedf'; this.style.color='#121f48';"
                                               onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb'; this.style.color='#9ca3af';">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>

                                            @if($material->is_active)
                                                <form id="delete-material-{{ $material->id }}" action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                        onclick="confirmDelete('delete-material-{{ $material->id }}')"
                                                        class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm"
                                                        title="Dar de baja">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $materials->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-2xl mb-4" style="background:#eef1f8;">
                            <svg class="w-12 h-12" style="color:#121f48; opacity:.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <h3 class="text-lg text-gray-900" style="font-weight:700;">No hay materiales registrados</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza a construir tu catálogo de productos.</p>
                        <div class="mt-6">
                            <a href="{{ route('materials.create') }}" class="text-sm hover:underline" style="color:#121f48; font-weight:700;">+ Agregar Material</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal Historial --}}
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition.opacity
                     class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm"
                     @click="showModal = false"></div>

                <div x-show="showModal" x-transition.scale
                     class="inline-block w-full max-w-2xl p-0 my-8 overflow-hidden text-left align-middle bg-white shadow-2xl rounded-2xl">

                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-base text-gray-900" style="font-weight:800;">Historial del Material</h3>
                            <p class="text-sm mt-0.5" style="color:#121f48; font-weight:700;" x-text="selectedName"></p>
                        </div>
                        <button @click="showModal = false"
                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-200 hover:bg-red-100 hover:text-red-500 text-gray-500 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex border-b border-gray-100">
                        <button @click="activeTab = 'stock'"
                                :style="activeTab === 'stock' ? 'border-color:#121f48; color:#121f48; background:#eef1f8;' : 'border-color:transparent; color:#9ca3af;'"
                                class="w-1/2 py-3 px-1 text-center border-b-2 text-sm transition focus:outline-none"
                                style="font-weight:700;">
                            Movimientos de Stock
                        </button>
                        <button @click="activeTab = 'prices'"
                                :class="activeTab === 'prices' ? 'border-green-500 text-green-600 bg-green-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="w-1/2 py-3 px-1 text-center border-b-2 text-sm transition focus:outline-none"
                                style="font-weight:700;">
                            Historial de Precios
                        </button>
                    </div>

                    <div class="p-6 h-96 overflow-y-auto">

                        <div x-show="activeTab === 'stock'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">
                                        <th class="px-4 py-3 text-left">Fecha</th>
                                        <th class="px-4 py-3 text-left">Tipo</th>
                                        <th class="px-4 py-3 text-right">Cant.</th>
                                        <th class="px-4 py-3 text-left">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <template x-for="mov in stockMovements" :key="mov.id">
                                        <tr>
                                            <td class="px-4 py-3 text-xs text-gray-500" x-text="mov.date"></td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 inline-flex text-xs leading-5 rounded-full"
                                                      :class="mov.type === 'Entrada' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'"
                                                      style="font-weight:700;"
                                                      x-text="mov.type"></span>
                                            </td>
                                            <td class="px-4 py-3 text-sm font-bold text-right font-mono"
                                                :class="mov.type === 'Entrada' ? 'text-green-600' : 'text-red-600'"
                                                x-text="(mov.type === 'Entrada' ? '+' : '-') + mov.quantity"></td>
                                            <td class="px-4 py-3 text-xs text-gray-500">
                                                <div class="flex flex-col">
                                                    <span x-text="mov.reason" class="text-gray-700" style="font-weight:600;"></span>
                                                    <span x-text="mov.user" class="text-[10px] text-gray-400"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="stockMovements.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">No hay movimientos registrados.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div x-show="activeTab === 'prices'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display:none;">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:800;">
                                        <th class="px-4 py-3 text-left">Fecha de Cambio</th>
                                        <th class="px-4 py-3 text-right">Precio</th>
                                        <th class="px-4 py-3 text-left">Notas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <template x-for="(price, index) in priceHistory" :key="price.id">
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-600" x-text="price.date"></td>
                                            <td class="px-4 py-3 text-right">
                                                <span class="text-green-700 font-bold font-mono text-sm bg-green-50 px-2 py-1 rounded border border-green-100">
                                                    $ <span x-text="price.price"></span>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-400 italic">
                                                <span x-show="index === 0" style="color:#121f48; font-weight:700;">Precio Actual</span>
                                                <span x-show="index > 0">Histórico</span>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="priceHistory.length === 0">
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-400 text-sm">No hay historial de precios.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 text-right">
                        <button @click="showModal = false"
                                class="px-4 py-2 bg-white border border-gray-200 shadow-sm text-gray-600 rounded-xl hover:bg-gray-50 text-sm transition"
                                style="font-weight:600;">
                            Cerrar Ventana
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function materialTable() {
            return {
                showModal: false,
                activeTab: 'stock',
                selectedName: '',
                stockMovements: [],
                priceHistory: [],

                async openHistory(id, name) {
                    this.selectedName = name;
                    this.stockMovements = [];
                    this.priceHistory = [];
                    this.activeTab = 'stock';
                    this.showModal = true;

                    try {
                        let response = await fetch('/materials/' + id + '/history');
                        if (!response.ok) throw new Error('Error servidor');
                        let data = await response.json();
                        this.stockMovements = data.stock || [];
                        this.priceHistory   = data.prices || [];
                    } catch (error) {
                        this.showModal = false;
                        Swal.fire({ icon: 'error', title: 'Error de Conexión', text: 'No se pudo cargar el historial.' });
                    }
                }
            }
        }

        function confirmDelete(formId) {
            Swal.fire({
                title: '¿Dar de baja?', text: "Esta acción no se puede deshacer.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#EF4444', cancelButtonColor: '#121f48',
                confirmButtonText: 'Sí, dar de baja', cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-xl' }
            }).then((r) => { if (r.isConfirmed) document.getElementById(formId).submit(); });
        }
    </script>
</x-app-layout>