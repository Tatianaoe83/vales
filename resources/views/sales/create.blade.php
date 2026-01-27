<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans" x-data="salesWizard({{ $units }})"> <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Nueva Venta de Vales</h1>
                    <p class="text-gray-500 text-sm mt-1">Logística automática basada en unidades del cliente.</p>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold text-gray-400 uppercase">Folio Preliminar</span>
                    <span class="text-xl font-mono font-bold text-blue-600">#VTA-NUEVA</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <form id="saleForm" action="{{ route('sales.store') }}" method="POST" @submit.prevent="submitForm">
                        @csrf
                        <input type="hidden" name="trips_configuration" :value="JSON.stringify(trips)">
                        
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                Cliente
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Cliente</label>
                                    <select name="client_id" x-model="clientId" @change="onClientChange()" class="w-full border-gray-300 rounded-lg text-sm" required>
                                        <option value="">Buscar cliente...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" data-rfc="{{ $client->rfc }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">RFC</label>
                                    <input type="text" x-model="clientRfc" class="w-full bg-gray-50 border-gray-200 rounded-lg text-sm text-gray-500" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Material y Cantidad
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Material</label>
                                    <select name="material_id" x-model="materialId" @change="updatePrice()" class="w-full border-gray-300 rounded-lg text-sm" required>
                                        <option value="" data-price="0">Seleccione...</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}" 
                                                    data-price="{{ $material->price }}" 
                                                    data-unit="{{ $material->unit }}">
                                                {{ $material->name }} - ${{ number_format((float)$material->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Total a Surtir (<span x-text="unitLabel"></span>)</label>
                                    <input type="number" step="0.01" name="cantidad_total" x-model="cantidad" @input="recalcEverything()" class="w-full border-gray-300 rounded-lg text-sm font-bold text-gray-900" placeholder="0.00" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Precio Unitario</label>
                                    <input type="number" step="0.01" name="precio_unitario" x-model="precio" class="w-full border-gray-300 rounded-lg text-sm bg-gray-50" readonly>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Descuento (%)</label>
                                    <input type="number" name="descuento_porcentaje" x-model="descuentoPorcentaje" @input="calculateTotals()" class="w-full border-gray-300 rounded-lg text-sm" placeholder="0">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                    Logística de Entregas
                                </h3>
                                
                                <div class="flex items-center bg-gray-100 p-1 rounded-lg">
                                    <button type="button" @click="setMode('auto')" :class="{'bg-white shadow text-blue-600': mode === 'auto', 'text-gray-500': mode !== 'auto'}" class="px-3 py-1 text-xs font-bold rounded-md transition">Automático</button>
                                    <button type="button" @click="setMode('manual')" :class="{'bg-white shadow text-blue-600': mode === 'manual', 'text-gray-500': mode !== 'manual'}" class="px-3 py-1 text-xs font-bold rounded-md transition">Manual</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Condición de Pago</label>
                                <select name="tipo_venta" class="w-full border-gray-300 rounded-lg text-sm mb-4">
                                    <option value="Contado">Contado</option>
                                    <option value="Credito">Crédito (15 días)</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                    <span>Asignado: <span x-text="totalDistributed.toFixed(2)"></span></span>
                                    <span>Meta: <span x-text="parseFloat(cantidad).toFixed(2)"></span></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" :style="'width: ' + progressPercentage + '%'"></div>
                                </div>
                                <p class="text-xs text-green-500 mt-1 font-bold" x-show="remaining <= 0 && trips.length > 0">¡Logística Completa!</p>
                                <p class="text-xs text-red-400 mt-1" x-show="remaining > 0">Falta asignar material.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="border-r border-gray-100 pr-4" x-show="mode === 'manual'">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Unidades del Cliente:</h4>
                                    <div class="space-y-2 h-64 overflow-y-auto pr-2">
                                        
                                        <button type="button" @click="addTrip(null, 'Manual / Externo', 0)" class="w-full text-left p-2 rounded-lg border border-dashed border-gray-300 hover:bg-gray-50 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Unidad Externa / Flete</span>
                                            <span class="text-blue-600 text-xs font-bold">+</span>
                                        </button>

                                        <template x-for="unit in clientUnits" :key="unit.id">
                                            <button type="button" @click="addTrip(unit.id, unit.placa + ' - ' + unit.tipo_vehiculo, unit.capacidad_maxima)" 
                                                    class="w-full text-left p-2 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 flex justify-between items-center transition">
                                                <div>
                                                    <p class="text-sm font-bold text-gray-700" x-text="unit.placa"></p>
                                                    <p class="text-xs text-gray-500"><span x-text="unit.tipo_vehiculo"></span> (Cap: <span x-text="unit.capacidad_maxima"></span>)</p>
                                                </div>
                                                <div class="bg-blue-100 text-blue-600 rounded-full w-6 h-6 flex items-center justify-center font-bold text-xs">+</div>
                                            </button>
                                        </template>
                                        
                                        <div x-show="clientUnits.length === 0" class="text-center p-4 text-xs text-gray-400">
                                            Este cliente no tiene unidades registradas.
                                        </div>
                                    </div>
                                </div>

                                <div :class="{'md:col-span-2': mode === 'auto', 'md:col-span-1': mode === 'manual'}">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">
                                        Viajes Programados (<span x-text="trips.length"></span>)
                                        <span x-show="mode === 'auto'" class="text-blue-500 ml-2 text-[10px]">(Generado Automáticamente)</span>
                                    </h4>
                                    
                                    <div class="space-y-2 h-64 overflow-y-auto bg-gray-50 p-2 rounded-lg border border-gray-200">
                                        <template x-for="(trip, index) in trips" :key="index">
                                            <div class="bg-white p-2 rounded shadow-sm flex items-center justify-between border border-gray-100">
                                                <div class="flex-1">
                                                    <p class="text-xs font-bold text-gray-700" x-text="trip.name"></p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <label class="text-xs text-gray-400">Carga:</label>
                                                        <input type="number" step="0.01" x-model="trip.amount" 
       @input="validateTripLimit(trip)" 
       :readonly="mode === 'auto'"
       :class="{'bg-gray-100 text-gray-500': mode === 'auto', 'bg-white text-blue-600': mode === 'manual'}"
       class="w-24 p-1 text-xs border-gray-300 rounded text-center font-bold">
                                                        <span class="text-xs text-gray-400" x-text="unitLabel"></span>
                                                    </div>
                                                </div>
                                                <button x-show="mode === 'manual'" type="button" @click="removeTrip(index)" class="text-red-400 hover:text-red-600 ml-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <div x-show="trips.length === 0" class="text-center py-10 text-gray-400 text-xs">
                                            <span x-show="cantidad <= 0">Ingresa una cantidad para calcular.</span>
                                            <span x-show="cantidad > 0 && clientUnits.length === 0 && mode === 'auto'">El cliente no tiene unidades.<br>Cambia a modo Manual.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-right">
                             <button type="submit" :disabled="remaining > 0.1 || trips.length === 0" :class="{'opacity-50 cursor-not-allowed': remaining > 0.1 || trips.length === 0}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition flex items-center justify-center gap-2 ml-auto w-full md:w-auto">
                                Generar <span x-text="trips.length"></span> Vales
                            </button>
                        </div>
                    </form>
                </div>

<div class="lg:col-span-1">
    <div class="bg-white p-6 rounded-2xl shadow-xl border border-blue-100 sticky top-6">
        <h4 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2">Resumen</h4>
        <div class="space-y-3 text-sm">
            
            <div class="flex justify-between text-gray-400 text-xs">
                <span>Importe</span> 
                <span>$<span x-text="(cantidad * precio).toFixed(2)"></span></span>
            </div>

            <div class="flex justify-between text-red-500 font-medium" x-show="descuentoPorcentaje > 0" x-transition>
                <span>Descuento (<span x-text="descuentoPorcentaje"></span>%)</span> 
                <span>-$<span x-text="((cantidad * precio) * (descuentoPorcentaje / 100)).toFixed(2)"></span></span>
            </div>

            <div class="border-t border-gray-100 my-2"></div>

            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span> 
                <span class="font-medium">$<span x-text="subtotal.toFixed(2)"></span></span>
            </div>

            <div class="flex justify-between text-gray-600">
                <span>IVA (16%)</span> 
                <span class="font-medium">$<span x-text="iva.toFixed(2)"></span></span>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-800">Total</span>
                <span class="text-2xl font-extrabold text-blue-600">$<span x-text="total.toFixed(2)"></span></span>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

<script>
    function salesWizard(allUnitsDb) {
        return {
            clientId: '', clientRfc: '', materialId: '', unitLabel: 'm³',
            cantidad: 0, precio: 0, descuentoPorcentaje: 0,
            subtotal: 0, montoDescuento: 0, iva: 0, total: 0,
            
            mode: 'auto',
            allUnits: allUnitsDb, 
            clientUnits: [],
            trips: [],

            get totalDistributed() {
                return this.trips.reduce((acc, trip) => acc + parseFloat(trip.amount || 0), 0);
            },

            get remaining() {
                return Math.max(0, this.cantidad - this.totalDistributed);
            },

            get progressPercentage() {
                if(this.cantidad <= 0) return 0;
                return Math.min(100, (this.totalDistributed / this.cantidad) * 100);
            },

            onClientChange() {
                this.fetchClientData();
                this.clientUnits = this.allUnits.filter(u => u.client_id == this.clientId);
                this.autoAssignLogistics();
            },

            recalcEverything() {
                this.calculateTotals();
                this.autoAssignLogistics();
            },

            setMode(newMode) {
                this.mode = newMode;
                if(newMode === 'auto') {
                    this.autoAssignLogistics();
                } else {
                    this.trips = []; 
                }
            },

            // --- NUEVA FUNCIÓN DE VALIDACIÓN ---
            validateTripLimit(trip) {
                // 1. Si es modo auto, no validamos manual
                if (this.mode === 'auto') return;

                // 2. Buscamos la unidad original para saber su capacidad real
                let unit = this.allUnits.find(u => u.id == trip.unit_id);

                // 3. Si no encontramos la unidad (ej. es externa), permitimos todo
                if (!unit) return;

                let maxCap = parseFloat(unit.capacidad_maxima);
                let currentAmount = parseFloat(trip.amount);

                // 4. EL IF QUE PEDISTE: Validar si excede
                if (currentAmount > maxCap) {
                    // Usamos SweetAlert o un alert normal
                    Swal.fire({
                        icon: 'warning',
                        title: 'Capacidad Excedida',
                        text: `La unidad ${unit.placa} solo soporta ${maxCap} ${this.unitLabel}.`,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    
                    // 5. Corregimos el valor al máximo permitido
                    trip.amount = maxCap;
                }
                
                // Recalculamos totales por si el número cambió
                this.calculateTotals();
            },
            // -----------------------------------

            autoAssignLogistics() {
                if (this.mode !== 'auto') return; 
                
                this.trips = [];
                let cargaRestante = parseFloat(this.cantidad);

                if (cargaRestante <= 0) return;

                if (this.clientUnits.length === 0) {
                    return;
                }

                let unitIndex = 0;
                
                while (cargaRestante > 0.01) {
                    
                    let sortedUnits = this.clientUnits.sort((a, b) => b.capacidad_maxima - a.capacidad_maxima);
                    
                    let unit = sortedUnits[unitIndex % sortedUnits.length];
                    
                    let capacidad = parseFloat(unit.capacidad_maxima);
                    if(capacidad <= 0) capacidad = 99999; 
                    let cantidadParaEsteViaje = Math.min(capacidad, cargaRestante);
                    
                    this.trips.push({
                        unit_id: unit.id,
                        name: unit.placa + ' - ' + unit.tipo_vehiculo,
                        amount: parseFloat(cantidadParaEsteViaje.toFixed(2))
                    });

                    cargaRestante -= cantidadParaEsteViaje;
                    unitIndex++;

                    if(unitIndex > 100) break;
                }
            },

            addTrip(id, name, cap) {
                let amountToAdd = 0;
                // Al agregar manual, sugerimos lo que falta, pero sin pasarnos de la capacidad
                if (cap > 0) amountToAdd = Math.min(cap, this.remaining);
                else amountToAdd = this.remaining;
                
                amountToAdd = Math.round(amountToAdd * 100) / 100;

                this.trips.push({ unit_id: id, name: name, amount: amountToAdd });
            },

            removeTrip(index) {
                this.trips.splice(index, 1);
            },

            submitForm() {
                if (this.remaining > 0.1) {
                    Swal.fire('Atención', 'Aún falta material por asignar.', 'warning');
                    return;
                }
                document.getElementById('saleForm').submit();
            },

            fetchClientData() {
                const select = document.querySelector(`select[name="client_id"]`);
                if(!select.selectedIndex) return;
                const option = select.options[select.selectedIndex];
                this.clientRfc = option.getAttribute('data-rfc') || '';
            },

            updatePrice() {
                const select = document.querySelector(`select[name="material_id"]`);
                const option = select.options[select.selectedIndex];
                if(option.value) {
                    this.precio = parseFloat(option.getAttribute('data-price')) || 0;
                    this.unitLabel = option.getAttribute('data-unit') || 'm³';
                    this.recalcEverything();
                }
            },

            calculateTotals() {
                let rawSubtotal = (parseFloat(this.cantidad) || 0) * (parseFloat(this.precio) || 0);
                this.montoDescuento = rawSubtotal * ((parseFloat(this.descuentoPorcentaje) || 0) / 100);
                this.subtotal = rawSubtotal - this.montoDescuento;
                this.iva = this.subtotal * 0.16;
                this.total = this.subtotal + this.iva;
            }
        }
    }
</script>
</x-app-layout>