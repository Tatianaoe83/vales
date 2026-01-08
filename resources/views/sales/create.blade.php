<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans" x-data="salesWizard()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Nueva Venta de Vales</h1>
                    <p class="text-gray-500 text-sm mt-1">Completa la información para generar los pases de salida.</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold text-gray-400 uppercase">Folio Preliminar</span>
                    <span class="text-xl font-mono font-bold text-blue-600">#VTA-NUEVA</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <form id="saleForm" action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                Datos del Cliente
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Seleccionar Cliente</label>
                                    <select name="client_id" x-model="clientId" @change="fetchClientData()" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Buscar cliente...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" data-rfc="{{ $client->rfc }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">RFC</label>
                                    <input type="text" x-model="clientRfc" class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm text-gray-500 cursor-not-allowed" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Detalles del Material
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Material</label>
                                    <select name="material_id" x-model="materialId" @change="updatePrice()" class="w-full border-gray-300 rounded-lg text-sm" required>
                                        <option value="">Seleccione material...</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" data-price="{{ $material->precio_actual }}" data-unit="{{ $material->unidad_medida }}">
                                                {{ $material->nombre }} - ${{ $material->precio_actual }} / {{ $material->unidad_medida }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cantidad Total (<span x-text="unitLabel"></span>)</label>
                                    <input type="number" step="0.01" name="cantidad_total" x-model="cantidad" @input="calculateTotals()" class="w-full border-gray-300 rounded-lg text-sm font-bold text-gray-900" placeholder="0.00" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Precio Unitario</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">$</span>
                                        <input type="number" step="0.01" name="precio_unitario" x-model="precio" @input="calculateTotals()" class="w-full pl-7 border-gray-300 rounded-lg text-sm bg-gray-50" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Descuento (%)</label>
                                    <input type="number" name="descuento_porcentaje" x-model="descuentoPorcentaje" @input="calculateTotals()" class="w-full border-gray-300 rounded-lg text-sm" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                Logística y Vales
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Condición de Pago</label>
                                    <select name="tipo_venta" class="w-full border-gray-300 rounded-lg text-sm">
                                        <option value="Contado">Contado</option>
                                        <option value="Credito">Crédito (15 días)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">División de Vales</label>
                                    <select name="numero_vales" x-model="numVales" class="w-full border-gray-300 rounded-lg text-sm">
                                        <option value="1">1 Solo Vale (Viaje único)</option>
                                        <option value="2">2 Vales (Dividir carga)</option>
                                        <option value="3">3 Vales</option>
                                        <option value="manual">Manual / Múltiples</option>
                                    </select>
                                    <p class="text-xs text-blue-500 mt-1" x-show="numVales > 1">
                                        Se generarán <span x-text="numVales"></span> vales de <span x-text="(cantidad / numVales).toFixed(2)"></span> <span x-text="unitLabel"></span> cada uno.
                                    </p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Unidad Sugerida (Opcional)</label>
                                    <select name="unit_id" class="w-full border-gray-300 rounded-lg text-sm">
                                        <option value="">-- Sin asignar (Cliente envía transporte) --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">
                                                {{ $unit->placa }} - {{ $unit->tipo_vehiculo }} (Cap: {{ $unit->capacidad_maxima }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                             <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-blue-500/30 transition transform active:scale-95 flex items-center justify-center gap-2 ml-auto w-full md:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Generar Vales
                            </button>
                        </div>

                    </form>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl shadow-xl border border-blue-100 sticky top-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Resumen de Venta</h4>
                        
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-medium">$<span x-text="subtotal.toFixed(2)">0.00</span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Descuento (<span x-text="descuentoPorcentaje"></span>%)</span>
                                <span class="font-medium text-red-500">-$<span x-text="montoDescuento.toFixed(2)">0.00</span></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>IVA (16%)</span>
                                <span class="font-medium">$<span x-text="iva.toFixed(2)">0.00</span></span>
                            </div>
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <span class="text-2xl font-extrabold text-blue-600">$<span x-text="total.toFixed(2)">0.00</span></span>
                            </div>
                        </div>

                        <div class="mt-6 bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="text-xs text-blue-800 font-bold mb-1">Confirmación</p>
                                    <p class="text-xs text-blue-600">
                                        Se generarán <strong x-text="numVales">1</strong> vales físicos. 
                                        El inventario se descontará automáticamente.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function salesWizard() {
            return {
                clientId: '',
                clientRfc: '',
                materialId: '',
                unitLabel: 'm³',
                
                cantidad: 0,
                precio: 0,
                descuentoPorcentaje: 0,
                
                subtotal: 0,
                montoDescuento: 0,
                iva: 0,
                total: 0,
                
                numVales: 1,

                fetchClientData() {
                    // Simulación simple (en prod usar fetch real)
                    const select = document.querySelector(`select[name="client_id"]`);
                    const option = select.options[select.selectedIndex];
                    this.clientRfc = option.getAttribute('data-rfc') || '';
                },

                updatePrice() {
                    const select = document.querySelector(`select[name="material_id"]`);
                    const option = select.options[select.selectedIndex];
                    if(option.value) {
                        this.precio = parseFloat(option.getAttribute('data-price')) || 0;
                        this.unitLabel = option.getAttribute('data-unit') || 'm³';
                        this.calculateTotals();
                    }
                },

                calculateTotals() {
                    // Cálculo Básico
                    let rawSubtotal = this.cantidad * this.precio;
                    
                    // Descuento
                    this.montoDescuento = rawSubtotal * (this.descuentoPorcentaje / 100);
                    
                    // Subtotal tras descuento
                    this.subtotal = rawSubtotal - this.montoDescuento;
                    
                    // IVA (16%)
                    this.iva = this.subtotal * 0.16;
                    
                    // Total Final
                    this.total = this.subtotal + this.iva;
                }
            }
        }
    </script>
</x-app-layout>