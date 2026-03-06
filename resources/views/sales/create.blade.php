<x-app-layout>

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endpush

<div class="pb-32 bg-gray-50 min-h-screen" x-data="salesWizard({{ $units }}, {{ $materials }})">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        {{-- ── ENCABEZADO ── --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-1 h-8 rounded-full brand-bg"></div>
                    <h1 class="text-2xl text-gray-900 tracking-tight" style="font-family:'Inter',sans-serif;font-weight:800;">Nueva Venta de Vales</h1>
                </div>
                <p class="text-gray-400 text-sm pl-4" style="font-family:'Inter',sans-serif;font-weight:400;">Logística automática basada en unidades del cliente</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Indicadores de paso --}}
                <div class="flex items-center gap-1.5 bg-white border border-gray-100 rounded-2xl px-4 py-2.5 shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full brand-bg flex items-center justify-center text-[10px] text-white" style="font-family:'Inter',sans-serif;font-weight:800;">1</div>
                        <span class="text-[11px] brand-text hidden sm:block" style="font-family:'Inter',sans-serif;font-weight:700;">Cliente</span>
                    </div>
                    <div class="w-4 h-px bg-gray-200 mx-1"></div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] transition-colors" style="font-family:'Inter',sans-serif;font-weight:800;"
                             :class="confirmedLines.length > 0 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'">2</div>
                        <span class="text-[11px] hidden sm:block transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;"
                              :class="confirmedLines.length > 0 ? 'text-blue-600' : 'text-gray-400'">Materiales</span>
                    </div>
                    <div class="w-4 h-px bg-gray-200 mx-1"></div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] transition-colors" style="font-family:'Inter',sans-serif;font-weight:800;"
                             :class="trips.length > 0 && remaining <= 0 ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400'">3</div>
                        <span class="text-[11px] hidden sm:block transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;"
                              :class="trips.length > 0 && remaining <= 0 ? 'text-green-600' : 'text-gray-400'">Logística</span>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl px-4 py-2.5 shadow-sm text-right">
                    <span class="block text-[9px] text-gray-300 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:800;">Folio</span>
                    <span class="text-base font-mono brand-text" style="font-family:'Inter',sans-serif;font-weight:800;">#VTA-NUEVA</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── COLUMNA PRINCIPAL ── --}}
            <div class="lg:col-span-2 space-y-5">
                <form id="saleForm" action="{{ route('sales.store') }}" method="POST" @submit.prevent="submitForm">
                    @csrf
                    <input type="hidden" name="lines_configuration" :value="JSON.stringify(confirmedLines)">
                    <input type="hidden" name="trips_configuration" :value="JSON.stringify(trips)">

                    {{-- ── PASO 1: CLIENTE ── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                            <div class="w-7 h-7 rounded-xl brand-bg flex items-center justify-center text-xs text-white shrink-0" style="font-family:'Inter',sans-serif;font-weight:800;">1</div>
                            <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">Datos del Cliente</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:500;">Cliente</label>
                                    <select name="client_id" x-model="clientId" @change="onClientChange()" required
                                            class="w-full border-gray-200 focus:border-brand focus:ring-brand rounded-xl text-sm transition-colors bg-gray-50 focus:bg-white" style="font-family:'Inter',sans-serif;font-weight:400;">
                                        <option value="">Seleccionar cliente...</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" data-rfc="{{ $client->rfc }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:500;">RFC</label>
                                    <div class="relative">
                                        <input type="text" x-model="clientRfc" placeholder="—" readonly
                                               class="w-full bg-gray-50 border-gray-100 rounded-xl text-sm text-gray-500 cursor-not-allowed font-mono" style="font-family:'Inter',sans-serif;font-weight:400;">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="clientRfc">
                                            <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── PASO 2: MATERIALES ── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs shrink-0 transition-colors" style="font-family:'Inter',sans-serif;font-weight:800;"
                                     :class="confirmedLines.length > 0 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'">2</div>
                                <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">Materiales a Facturar</h3>
                            </div>
                            <div x-show="confirmedLines.length > 0" x-transition
                                 class="flex items-center gap-1.5 brand-bg-soft brand-text text-[10px] px-3 py-1.5 rounded-full" style="font-family:'Inter',sans-serif;font-weight:800;">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span x-text="confirmedLines.length"></span> confirmado(s)
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            {{-- Selector activo --}}
                            <div class="brand-bg-soft border brand-border rounded-2xl p-5" style="background:#eef1f8;">
                                <p class="text-[9px] brand-text uppercase tracking-widest mb-4 flex items-center gap-2" style="font-family:'Inter',sans-serif;font-weight:800; opacity:.6;">
                                    <span class="w-3 h-px brand-bg block"></span>Agregar material<span class="w-3 h-px brand-bg block"></span>
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                    <div class="md:col-span-4 space-y-1.5">
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:500;">Material</label>
                                        <select id="activeMaterialSelect" x-model="draft.material_id" @change="onDraftMatChange()"
                                                class="w-full brand-border bg-white focus:border-brand focus:ring-brand rounded-xl text-sm transition-colors" style="font-family:'Inter',sans-serif;font-weight:400;">
                                            <option value="">Seleccione...</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" data-price="{{ $material->price }}" data-unit="{{ $material->unit }}">{{ $material->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:500;">Cantidad</label>
                                        <div class="relative">
                                            <input type="number" step="1" min="1" x-model.number="draft.cantidad" placeholder="0"
                                                   @keydown="if(['.','e','-','+'].includes($event.key)) $event.preventDefault()"
                                                   @input="if(parseFloat($el.value) < 1 || isNaN(parseFloat($el.value))) $el.value = 1; draft.cantidad = parseInt($el.value)"
                                                   @paste="$event.preventDefault()"
                                                   class="w-full brand-border bg-white focus:border-brand focus:ring-brand rounded-xl text-sm text-center pr-7 transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;">
                                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[9px] brand-text pointer-events-none" style="font-family:'Inter',sans-serif;font-weight:800; opacity:.5;" x-text="draft.unitLabel"></span>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:500;">Precio</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-300" style="font-family:'Inter',sans-serif;font-weight:800;">$</span>
                                            <input type="number" step="0.01" x-model="draft.precio" readonly
                                                   class="w-full bg-gray-100 border-gray-100 rounded-xl text-sm text-center text-gray-400 cursor-not-allowed pl-6" style="font-family:'Inter',sans-serif;font-weight:400;">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 space-y-1.5">
                                        <label class="block text-[10px] text-gray-500 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:500;">Desc. %</label>
                                        <div class="relative">
                                            <input type="number" step="1" min="0" max="100" x-model="draft.descuentoPorcentaje" placeholder="0"
                                                   @keydown="if(['-','+','e','.'].includes($event.key)) $event.preventDefault()"
                                                   @input="
                                                       let v = parseInt($el.value);
                                                       if (isNaN(v) || v < 0) { $el.value = 0; draft.descuentoPorcentaje = 0; }
                                                       else if (v > 100)      { $el.value = 100; draft.descuentoPorcentaje = 100; }
                                                   "
                                                   @paste="$event.preventDefault()"
                                                   class="w-full brand-border bg-white focus:border-brand focus:ring-brand rounded-xl text-sm text-center transition-colors pr-6" style="font-family:'Inter',sans-serif;font-weight:400;">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-300" style="font-family:'Inter',sans-serif;font-weight:800;">%</span>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="button" @click="confirmDraft()"
                                                :disabled="!draft.material_id || parseFloat(draft.cantidad) <= 0"
                                                :class="(!draft.material_id || parseFloat(draft.cantidad) <= 0)
                                                    ? 'opacity-40 cursor-not-allowed bg-gray-300 text-gray-500'
                                                    : 'brand-bg text-white hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0'"
                                                class="w-full flex items-center justify-center gap-2 text-xs py-2.5 px-4 rounded-xl transition-all duration-150 shadow-sm" style="font-family:'Inter',sans-serif;font-weight:600;">
                                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end" x-show="draft.material_id && parseFloat(draft.cantidad) > 0" x-transition>
                                    <span class="text-[11px] brand-text bg-white brand-border px-3 py-1 rounded-full shadow-sm" style="font-family:'Inter',sans-serif;font-weight:700;">
                                        Subtotal: $<span x-text="draftSubtotal.toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>

                            {{-- Cards confirmados --}}
                            <div x-show="confirmedLines.length > 0" x-transition>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <template x-for="(line, idx) in confirmedLines" :key="idx">
                                        <div class="group flex items-stretch bg-gray-50 hover:bg-white border border-gray-100 hover:border-blue-200 hover:shadow-md rounded-xl transition-all duration-200 hover:-translate-y-0.5 overflow-hidden">
                                            <div class="w-1 brand-bg shrink-0"></div>
                                            <div class="flex-1 min-w-0 px-3 py-3">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="text-xs text-gray-700 group-hover:brand-text truncate leading-tight flex-1" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="getMaterialName(line.material_id)"></p>
                                                    <span class="shrink-0 text-[9px] bg-gray-100 group-hover:brand-bg-soft text-gray-400 group-hover:brand-text px-1.5 py-0.5 rounded-md transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="line.unitLabel"></span>
                                                </div>
                                                <div class="flex items-center justify-between gap-1">
                                                    <span class="text-[11px] text-gray-400 truncate" style="font-family:'Inter',sans-serif;font-weight:500;">
                                                        <span x-text="line.cantidad"></span> × $<span x-text="parseFloat(line.precio).toFixed(2)"></span>
                                                        <template x-if="parseFloat(line.descuentoPorcentaje) > 0">
                                                            <span class="ml-1 text-red-400" style="font-weight:700;">−<span x-text="line.descuentoPorcentaje"></span>%</span>
                                                        </template>
                                                    </span>
                                                    <span class="text-xs text-gray-800 shrink-0" style="font-family:'Inter',sans-serif;font-weight:700;">$<span x-text="lineSubtotal(line).toFixed(2)"></span></span>
                                                </div>
                                            </div>
                                            <div class="flex flex-col border-l border-gray-100 shrink-0">
                                                <button type="button" @click="openModal(idx)"
                                                        class="flex-1 w-9 flex items-center justify-center text-gray-300 hover:brand-text hover:brand-bg-soft transition-colors border-b border-gray-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" @click="removeConfirmedLine(idx)"
                                                        class="flex-1 w-9 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Vacío --}}
                            <div x-show="confirmedLines.length === 0" class="flex flex-col items-center justify-center py-8 text-center border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50/50">
                                <div class="w-10 h-10 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-300 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">Sin materiales</p>
                                <p class="text-[11px] text-gray-300 mt-1" style="font-family:'Inter',sans-serif;font-weight:400;">Selecciona y presiona Agregar</p>
                            </div>
                        </div>
                    </div>

                    {{-- ── PASO 3: LOGÍSTICA ── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center text-xs shrink-0 transition-colors" style="font-family:'Inter',sans-serif;font-weight:800;"
                                     :class="trips.length > 0 && remaining <= 0 ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400'">3</div>
                                <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">Logística de Entregas</h3>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="flex items-center bg-gray-100 p-0.5 rounded-xl">
                                    <button type="button" @click="setMode('auto')"
                                            :class="mode === 'auto' ? 'bg-white shadow brand-text' : 'text-gray-400 hover:text-gray-600'"
                                            class="px-3.5 py-1.5 text-[11px] rounded-[10px] transition-all" style="font-family:'Inter',sans-serif;font-weight:700;">⚡ Auto</button>
                                    <button type="button" @click="setMode('manual')"
                                            :class="mode === 'manual' ? 'bg-white shadow brand-text' : 'text-gray-400 hover:text-gray-600'"
                                            class="px-3.5 py-1.5 text-[11px] rounded-[10px] transition-all" style="font-family:'Inter',sans-serif;font-weight:700;">✋ Manual</button>
                                </div>
                                <select name="tipo_venta" class="border-gray-200 rounded-xl text-xs focus:border-brand focus:ring-brand bg-gray-50 py-2" style="font-family:'Inter',sans-serif;font-weight:600;">
                                    <option value="Contado">💳 Contado</option>
                                    <option value="Credito">📋 Crédito 15d</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            {{-- Barra progreso --}}
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full transition-colors" :class="remaining <= 0 && trips.length > 0 ? 'bg-green-400' : 'bg-blue-400 animate-pulse'"></div>
                                        <span class="text-[11px] text-red-500 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="remaining > 0">
                                            Falta: <span x-text="remaining.toFixed(2)"></span> <span x-text="primaryUnitLabel"></span>
                                        </span>
                                        <span class="text-[11px] text-green-600 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="remaining <= 0 && trips.length > 0">✓ Logística completa</span>
                                        <span class="text-[11px] text-gray-300 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="trips.length === 0">Esperando materiales...</span>
                                    </div>
                                    <span class="text-[11px] text-gray-400" style="font-family:'Inter',sans-serif;font-weight:500;">
                                        <span class="brand-text" style="font-weight:700;" x-text="totalDistributed.toFixed(2)"></span> / <span x-text="totalCantidad.toFixed(2)"></span>
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500"
                                         :class="remaining <= 0 ? 'bg-green-400' : 'bg-blue-500'"
                                         :style="`width:${progressPercentage}%`"></div>
                                </div>
                            </div>

                            {{-- Grid flotilla + viajes --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- FLOTILLA --}}
                                <div x-show="mode === 'manual'" class="border border-gray-100 rounded-xl overflow-hidden bg-white flex flex-col">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between shrink-0">
                                        <span class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:800;">Flotilla</span>
                                        <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="clientUnits.length + ' unidades'"></span>
                                    </div>

                                    <div class="px-3 pt-3 shrink-0">
                                        <button type="button" @click="addTrip(null, 'Externo / Flete', 0)"
                                                class="w-full flex items-center gap-2.5 p-2.5 rounded-xl border-2 border-dashed border-gray-200 hover:brand-border hover:brand-bg-soft group transition-all">
                                            <div class="w-6 h-6 rounded-lg bg-gray-100 group-hover:brand-bg-soft flex items-center justify-center shrink-0 transition-colors">
                                                <svg class="w-3 h-3 text-gray-400 group-hover:brand-text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                                </svg>
                                            </div>
                                            <span class="text-[11px] text-gray-400 group-hover:brand-text transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;">Unidad Externa / Flete</span>
                                        </button>
                                    </div>

                                    <div class="p-3 overflow-y-auto custom-scrollbar" style="max-height: 280px;">
                                        <div x-show="clientUnits.length === 0" class="flex flex-col items-center py-8 text-center">
                                            <svg class="w-8 h-8 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <p class="text-[11px] text-gray-300" style="font-family:'Inter',sans-serif;font-weight:700;">Sin unidades</p>
                                            <p class="text-[10px] text-gray-200 mt-0.5" style="font-family:'Inter',sans-serif;font-weight:400;">Selecciona un cliente primero</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2" x-show="clientUnits.length > 0">
                                            <template x-for="unit in clientUnits" :key="unit.id">
                                                <button type="button"
                                                        @click="addTrip(unit.id, unit.placa + ' · ' + unit.tipo_vehiculo, unit.capacidad_maxima)"
                                                        class="group flex flex-col items-start p-2.5 rounded-xl border border-gray-100 hover:brand-border hover:brand-bg-soft bg-white transition-all hover:shadow-sm hover:-translate-y-0.5 text-left">
                                                    <div class="flex items-center justify-between w-full mb-1">
                                                        <span class="text-[11px] text-gray-700 group-hover:brand-text transition-colors truncate pr-1" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="unit.placa"></span>
                                                        <div class="w-4 h-4 rounded-md bg-gray-100 group-hover:brand-bg flex items-center justify-center transition-colors shrink-0">
                                                            <svg class="w-2.5 h-2.5 text-gray-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="text-[10px] text-gray-400 leading-tight truncate w-full" style="font-family:'Inter',sans-serif;font-weight:500;" x-text="unit.tipo_vehiculo"></span>
                                                    <span class="text-[10px] brand-text mt-0.5" style="font-family:'Inter',sans-serif;font-weight:700; opacity:.7;">Cap: <span x-text="unit.capacidad_maxima"></span></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- VIAJES PROGRAMADOS --}}
                                <div class="border border-gray-100 rounded-xl overflow-hidden flex flex-col bg-gray-50"
                                     :class="mode === 'auto' ? 'md:col-span-2' : ''">
                                    <div class="px-4 py-3 bg-white border-b border-gray-100 flex items-center justify-between shrink-0">
                                        <span class="text-[10px] text-gray-400 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:800;">Viajes Programados</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;"
                                              :class="trips.length > 0 ? 'brand-bg-soft brand-text' : 'bg-gray-100 text-gray-400'"
                                              x-text="trips.length + ' viaje(s)'"></span>
                                    </div>

                                    <div class="p-3 overflow-y-auto custom-scrollbar space-y-2" style="max-height: 320px;">
                                        <template x-for="(trip, index) in trips" :key="index">
                                            <div class="flex items-center gap-3 bg-white border border-gray-100 rounded-xl p-3 group hover:brand-border hover:shadow-sm transition-all relative overflow-hidden">
                                                <div class="absolute left-0 top-0 bottom-0 w-0.5 brand-bg rounded-l-xl"></div>
                                                <div class="pl-2 flex-1 min-w-0">
                                                    <p class="text-[11px] text-gray-700 truncate mb-1.5" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="trip.name"></p>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[9px] text-gray-400 uppercase tracking-wide shrink-0" style="font-family:'Inter',sans-serif;font-weight:700;">Carga:</span>
                                                        <input type="number" step="0.01" min="0.01" x-model="trip.amount"
                                                               @keydown="if(['-','+','e'].includes($event.key)) $event.preventDefault()"
                                                               @input="if(parseFloat($el.value) <= 0 || isNaN(parseFloat($el.value))) $el.value = 0.01; validateTripLimit(trip)"
                                                               @paste="$event.preventDefault()"
                                                               :readonly="mode === 'auto'"
                                                               :class="mode === 'auto'
                                                                   ? 'bg-transparent border-transparent brand-text cursor-default'
                                                                   : 'bg-white brand-border brand-text focus:ring-1 focus:ring-brand'"
                                                               class="w-20 p-1 text-xs rounded-lg text-center border transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;">
                                                        <span class="text-[10px] text-gray-300" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="primaryUnitLabel"></span>
                                                    </div>
                                                </div>
                                                <button x-show="mode === 'manual'" type="button" @click="removeTrip(index)"
                                                        class="w-6 h-6 flex items-center justify-center rounded-lg text-gray-200 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>

                                        <div x-show="trips.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-3 border border-gray-100">
                                                <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            </div>
                                            <p class="text-xs text-gray-300" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="totalCantidad <= 0">Agrega materiales primero</p>
                                            <p class="text-xs text-gray-300" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="totalCantidad > 0 && clientUnits.length === 0 && mode === 'auto'">Sin unidades — usa modo Manual</p>
                                            <p class="text-xs text-gray-300" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="totalCantidad > 0 && (clientUnits.length > 0 || mode === 'manual')">Selecciona unidades de la flotilla</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            {{-- ── RESUMEN LATERAL ── --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-6 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-50 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-xl brand-bg-soft flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 brand-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm text-gray-800 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">Resumen</h4>
                    </div>

                    <div class="p-5">
                        <div class="space-y-2 mb-5 min-h-[3rem]">
                            <template x-for="(line, idx) in confirmedLines" :key="idx">
                                <div class="flex justify-between items-start gap-2 text-xs" x-show="line.material_id">
                                    <div class="flex items-start gap-2 min-w-0">
                                        <div class="w-1 h-1 rounded-full brand-bg mt-1.5 shrink-0"></div>
                                        <span class="text-gray-500 truncate" style="font-family:'Inter',sans-serif;font-weight:500;" x-text="getMaterialName(line.material_id)"></span>
                                    </div>
                                    <span class="text-gray-800 shrink-0" style="font-family:'Inter',sans-serif;font-weight:700;">$<span x-text="lineSubtotal(line).toFixed(2)"></span></span>
                                </div>
                            </template>
                            <div x-show="confirmedLines.length === 0" class="text-[11px] text-gray-300 text-center py-3 uppercase tracking-wide" style="font-family:'Inter',sans-serif;font-weight:700;">
                                Sin materiales
                            </div>
                        </div>

                        <div class="space-y-2 text-xs border-t border-gray-50 pt-4">
                            <div class="flex justify-between text-gray-400">
                                <span style="font-family:'Inter',sans-serif;font-weight:500;">Importe bruto</span>
                                <span style="font-family:'Inter',sans-serif;font-weight:700;">$<span x-text="importeBruto.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-red-400" style="font-family:'Inter',sans-serif;font-weight:700;" x-show="montoDescuento > 0" x-transition>
                                <span>Descuentos</span>
                                <span>−$<span x-text="montoDescuento.toFixed(2)"></span></span>
                            </div>
                            <div class="border-t border-dashed border-gray-100 my-2"></div>
                            <div class="flex justify-between text-gray-600">
                                <span style="font-family:'Inter',sans-serif;font-weight:500;">Subtotal</span>
                                <span style="font-family:'Inter',sans-serif;font-weight:700;">$<span x-text="subtotal.toFixed(2)"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span style="font-family:'Inter',sans-serif;font-weight:500;">IVA 16%</span>
                                <span style="font-family:'Inter',sans-serif;font-weight:700;">$<span x-text="iva.toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>

                    <div class="brand-bg px-5 py-4 flex justify-between items-center">
                        <span class="text-[10px] uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:800; color:rgba(255,255,255,.5);">Total</span>
                        <span class="text-2xl text-white" style="font-family:'Inter',sans-serif;font-weight:800;">$<span x-text="total.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── BARRA STICKY INFERIOR ── --}}
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-100 shadow-2xl shadow-black/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap flex-1">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full transition-colors" :class="confirmedLines.length > 0 ? 'bg-green-400' : 'bg-gray-200'"></div>
                        <span class="text-[11px] text-gray-400" style="font-family:'Inter',sans-serif;font-weight:500;">
                            <span class="text-gray-700" style="font-weight:700;" x-text="confirmedLines.length"></span> material(es)
                        </span>
                    </div>
                    <div class="w-px h-3 bg-gray-200"></div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full transition-colors" :class="trips.length > 0 ? 'bg-green-400' : 'bg-gray-200'"></div>
                        <span class="text-[11px] text-gray-400" style="font-family:'Inter',sans-serif;font-weight:500;">
                            <span class="text-gray-700" style="font-weight:700;" x-text="trips.length"></span> viaje(s)
                        </span>
                    </div>
                    <div class="w-px h-3 bg-gray-200" x-show="totalCantidad > 0"></div>
                    <div class="flex items-center gap-1.5" x-show="totalCantidad > 0">
                        <div class="w-1.5 h-1.5 rounded-full transition-colors" :class="remaining <= 0 ? 'bg-green-400' : 'bg-red-400 animate-pulse'"></div>
                        <span class="text-[11px] transition-colors" style="font-family:'Inter',sans-serif;font-weight:600;" :class="remaining <= 0 ? 'text-green-600' : 'text-red-400'">
                            <span x-show="remaining > 0">Faltan <span x-text="remaining.toFixed(2)"></span> <span x-text="primaryUnitLabel"></span></span>
                            <span x-show="remaining <= 0">Todo asignado</span>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-4 ml-auto">
                    <div class="text-right hidden sm:block">
                        <p class="text-[9px] text-gray-300 uppercase tracking-widest" style="font-family:'Inter',sans-serif;font-weight:800;">Total</p>
                        <p class="text-xl brand-text" style="font-family:'Inter',sans-serif;font-weight:800;">$<span x-text="total.toFixed(2)"></span></p>
                    </div>
                    <button type="button" @click="submitForm()"
                            :disabled="remaining > 0.1 || trips.length === 0 || confirmedLines.length === 0"
                            :class="(remaining > 0.1 || trips.length === 0 || confirmedLines.length === 0)
                                ? 'opacity-40 cursor-not-allowed bg-gray-200 text-gray-400'
                                : 'brand-bg text-white hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0'"
                            class="flex items-center gap-2 py-2.5 px-5 rounded-xl shadow transition-all duration-200 text-sm whitespace-nowrap" style="font-family:'Inter',sans-serif;font-weight:600;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Generar <span x-text="trips.length"></span> Vale(s)
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MODAL ── --}}
    <div x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div class="absolute inset-0 bg-gray-600/30 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-3"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden z-10">

            <div class="brand-bg px-5 py-4 flex items-start justify-between gap-4">
                <div>
                    <p class="text-[9px] uppercase tracking-widest mb-0.5" style="font-family:'Inter',sans-serif;font-weight:800; color:rgba(255,255,255,.45);">Detalle del material</p>
                    <h3 class="text-base text-white leading-tight" style="font-family:'Inter',sans-serif;font-weight:800;" x-text="modalLine ? getMaterialName(modalLine.material_id) : ''"></h3>
                </div>
                <button @click="closeModal()" class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0 text-white" style="background:rgba(255,255,255,.12);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-3" x-show="modalLine">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3.5 text-center border border-gray-100">
                        <p class="text-[9px] text-gray-300 uppercase tracking-widest mb-1" style="font-family:'Inter',sans-serif;font-weight:800;">Cantidad</p>
                        <p class="text-xl text-gray-800" style="font-family:'Inter',sans-serif;font-weight:800;" x-text="modalLine ? modalLine.cantidad : ''"></p>
                        <p class="text-[10px] text-gray-300 mt-0.5" style="font-family:'Inter',sans-serif;font-weight:700;" x-text="modalLine ? modalLine.unitLabel : ''"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3.5 text-center border border-gray-100">
                        <p class="text-[9px] text-gray-300 uppercase tracking-widest mb-1" style="font-family:'Inter',sans-serif;font-weight:800;">Precio Unit.</p>
                        <p class="text-xl text-gray-800" style="font-family:'Inter',sans-serif;font-weight:800;">$<span x-text="modalLine ? parseFloat(modalLine.precio).toFixed(2) : ''"></span></p>
                        <p class="text-[10px] text-gray-300 mt-0.5" style="font-family:'Inter',sans-serif;font-weight:500;">por <span x-text="modalLine ? modalLine.unitLabel : ''"></span></p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex items-center justify-between"
                     x-show="modalLine && parseFloat(modalLine.descuentoPorcentaje) > 0">
                    <span class="text-xs text-gray-400" style="font-family:'Inter',sans-serif;font-weight:700;">Descuento</span>
                    <div class="text-right">
                        <span class="text-sm text-red-500" style="font-family:'Inter',sans-serif;font-weight:700;"><span x-text="modalLine ? modalLine.descuentoPorcentaje : ''"></span>%</span>
                        <p class="text-[10px] text-gray-400" style="font-family:'Inter',sans-serif;font-weight:400;">−$<span x-text="modalLine ? (parseFloat(modalLine.cantidad)*parseFloat(modalLine.precio)*(parseFloat(modalLine.descuentoPorcentaje)/100)).toFixed(2) : ''"></span></p>
                    </div>
                </div>

                <div class="brand-bg rounded-xl p-3.5 flex justify-between items-center">
                    <p class="text-xs" style="font-family:'Inter',sans-serif;font-weight:600; color:rgba(255,255,255,.6);">Subtotal línea</p>
                    <p class="text-xl text-white" style="font-family:'Inter',sans-serif;font-weight:800;">$<span x-text="modalLine ? lineSubtotal(modalLine).toFixed(2) : ''"></span></p>
                </div>

                <div class="flex gap-2.5 pt-1">
                    <button type="button" @click="removeConfirmedLine(modalLineIdx); closeModal()"
                            class="flex-1 flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-500 text-xs py-2.5 rounded-xl transition-colors" style="font-family:'Inter',sans-serif;font-weight:700;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                    <button type="button" @click="closeModal()"
                            class="flex-1 brand-bg text-white text-xs py-2.5 rounded-xl transition-colors" style="font-family:'Inter',sans-serif;font-weight:600;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function salesWizard(allUnitsDb, allMaterialsDb) {
    return {
        clientId: '', clientRfc: '',
        mode: 'auto',
        allUnits: allUnitsDb,
        allMaterials: allMaterialsDb,
        clientUnits: [],
        trips: [],
        draft: { material_id: '', cantidad: 0, precio: 0, descuentoPorcentaje: 0, unitLabel: '' },
        confirmedLines: [],
        modalOpen: false,
        modalLineIdx: null,

        get modalLine()         { return this.modalLineIdx !== null ? this.confirmedLines[this.modalLineIdx] : null; },
        get totalCantidad()     { return this.confirmedLines.reduce((s,l) => s+(parseFloat(l.cantidad)||0), 0); },
        get primaryUnitLabel()  { const f=this.confirmedLines.find(l=>l.unitLabel); return f?f.unitLabel:''; },
        get importeBruto()      { return this.confirmedLines.reduce((s,l) => s+(parseFloat(l.cantidad)||0)*(parseFloat(l.precio)||0), 0); },
        get montoDescuento()    { return this.confirmedLines.reduce((s,l) => { const b=(parseFloat(l.cantidad)||0)*(parseFloat(l.precio)||0); return s+b*((parseFloat(l.descuentoPorcentaje)||0)/100); }, 0); },
        get subtotal()          { return this.importeBruto - this.montoDescuento; },
        get iva()               { return this.subtotal * 0.16; },
        get total()             { return this.subtotal + this.iva; },
        get totalDistributed()  { return this.trips.reduce((s,t) => s+parseFloat(t.amount||0), 0); },
        get remaining()         { return Math.max(0, this.totalCantidad - this.totalDistributed); },
        get progressPercentage(){ return this.totalCantidad<=0 ? 0 : Math.min(100,(this.totalDistributed/this.totalCantidad)*100); },
        get draftSubtotal()     { const b=(parseFloat(this.draft.cantidad)||0)*(parseFloat(this.draft.precio)||0); return b-b*((parseFloat(this.draft.descuentoPorcentaje)||0)/100); },

        onDraftMatChange() {
            const sel = document.getElementById('activeMaterialSelect');
            if (!sel) return;
            const opt = sel.options[sel.selectedIndex];
            this.draft.precio    = parseFloat(opt.getAttribute('data-price')) || 0;
            this.draft.unitLabel = opt.getAttribute('data-unit') || '';
        },

        confirmDraft() {
            if (!this.draft.material_id || parseFloat(this.draft.cantidad) <= 0) return;
            this.draft.cantidad            = Math.max(1, parseInt(this.draft.cantidad) || 1);
            this.draft.descuentoPorcentaje = Math.min(100, Math.max(0, parseInt(this.draft.descuentoPorcentaje) || 0));
            this.confirmedLines.push({...this.draft});
            this.draft = { material_id:'', cantidad:0, precio:0, descuentoPorcentaje:0, unitLabel:'' };
            const sel = document.getElementById('activeMaterialSelect');
            if (sel) sel.selectedIndex = 0;
            this.recalcEverything();
        },

        removeConfirmedLine(idx) { this.confirmedLines.splice(idx, 1); this.recalcEverything(); },
        openModal(idx)  { this.modalLineIdx = idx; this.modalOpen = true; },
        closeModal()    { this.modalOpen = false; this.modalLineIdx = null; },

        lineSubtotal(line) { const b=(parseFloat(line.cantidad)||0)*(parseFloat(line.precio)||0); return b-b*((parseFloat(line.descuentoPorcentaje)||0)/100); },
        getMaterialName(id) { const m=this.allMaterials.find(m=>m.id==id); return m?m.name:''; },

        onClientChange() {
            const sel = document.querySelector('select[name="client_id"]');
            if (sel && sel.selectedIndex) this.clientRfc = sel.options[sel.selectedIndex].getAttribute('data-rfc') || '';
            this.clientUnits = this.allUnits.filter(u => u.client_id == this.clientId);
            this.autoAssignLogistics();
        },

        recalcEverything() { this.autoAssignLogistics(); },
        setMode(m) { this.mode=m; m==='auto' ? this.autoAssignLogistics() : (this.trips=[]); },

        autoAssignLogistics() {
            if (this.mode !== 'auto') return;
            this.trips = [];
            let restante = this.totalCantidad;
            if (restante <= 0 || this.clientUnits.length === 0) return;
            const sorted = [...this.clientUnits].sort((a,b) => b.capacidad_maxima - a.capacidad_maxima);
            let i = 0;
            while (restante > 0.01) {
                const unit  = sorted[i % sorted.length];
                const cap   = parseFloat(unit.capacidad_maxima) || 99999;
                const carga = parseFloat(Math.min(cap, restante).toFixed(2));
                this.trips.push({ unit_id:unit.id, name:unit.placa+' · '+unit.tipo_vehiculo, amount:carga });
                restante -= carga;
                if (++i > 100) break;
            }
        },

        addTrip(id, name, cap) {
            let amt = cap > 0 ? Math.min(cap, this.remaining) : this.remaining;
            amt = Math.max(0, parseFloat(amt.toFixed(2)));
            this.trips.push({ unit_id:id, name, amount:amt });
        },
        removeTrip(idx) { this.trips.splice(idx, 1); },

        validateTripLimit(trip) {
            if (this.mode === 'auto') return;
            if (parseFloat(trip.amount) <= 0 || isNaN(parseFloat(trip.amount))) {
                trip.amount = 0.01;
            }
            const unit = this.allUnits.find(u => u.id == trip.unit_id);
            if (!unit) return;
            const max = parseFloat(unit.capacidad_maxima);
            if (parseFloat(trip.amount) > max) {
                Swal.fire({ icon:'warning', title:'Capacidad excedida', text:`Máx: ${max} ${this.primaryUnitLabel}`, toast:true, position:'top-end', timer:3000, showConfirmButton:false });
                trip.amount = max;
            }
        },

        submitForm() {
            if (this.confirmedLines.length === 0) { Swal.fire('Atención','Agrega al menos un material.','warning'); return; }
            if (this.remaining > 0.1)             { Swal.fire('Atención','Falta asignar material en logística.','warning'); return; }
            const mapped = this.confirmedLines.map(l => ({
                material_id:     l.material_id,
                cantidad_total:  l.cantidad,
                precio_unitario: l.precio,
                descuento:       l.descuentoPorcentaje || 0,
                unit:            l.unitLabel
            }));
            document.querySelector('[name="lines_configuration"]').value = JSON.stringify(mapped);
            document.querySelector('[name="trips_configuration"]').value  = JSON.stringify(this.trips);
            document.getElementById('saleForm').submit();
        }
    }
}
</script>

<style>
    * { font-family: 'Inter', sans-serif; }

    /* ── Brand color utilities ── */
    :root { --brand: #121f48; --brand-soft: #eef1f8; --brand-border: #c8cedf; }

    .brand-bg           { background-color: var(--brand) !important; }
    .brand-bg-soft      { background-color: var(--brand-soft) !important; }
    .brand-text         { color: var(--brand) !important; }
    .brand-border       { border-color: var(--brand-border) !important; }
    .hover\:brand-border:hover { border-color: var(--brand-border) !important; }
    .hover\:brand-bg-soft:hover { background-color: var(--brand-soft) !important; }
    .hover\:brand-text:hover { color: var(--brand) !important; }
    .hover\:brand-bg:hover  { background-color: var(--brand) !important; }
    .group:hover .group-hover\:brand-text { color: var(--brand) !important; }
    .group:hover .group-hover\:brand-bg   { background-color: var(--brand) !important; }
    .group:hover .group-hover\:brand-bg-soft { background-color: var(--brand-soft) !important; }

    /* Alpine dynamic :class bg-blue-600 → brand */
    .bg-blue-600 { background-color: var(--brand) !important; }
    .text-blue-600 { color: var(--brand) !important; }
    .bg-blue-500  { background-color: #1a2d6b !important; }
    .bg-blue-400  { background-color: #2a3f80 !important; }
    .border-blue-200 { border-color: var(--brand-border) !important; }
    .focus\:border-blue-500:focus { border-color: var(--brand) !important; }
    .focus\:ring-blue-500:focus   { --tw-ring-color: var(--brand) !important; }
    .focus\:border-brand:focus    { border-color: var(--brand) !important; }
    .focus\:ring-brand:focus      { --tw-ring-color: var(--brand) !important; }
    .hover\:border-blue-300:hover { border-color: var(--brand-border) !important; }
    .hover\:bg-blue-50:hover      { background-color: var(--brand-soft) !important; }
    .hover\:text-blue-700:hover   { color: var(--brand) !important; }
    .hover\:bg-blue-700:hover     { background-color: #0d1633 !important; }
    .bg-blue-100  { background-color: var(--brand-soft) !important; }
    .bg-blue-50   { background-color: var(--brand-soft) !important; }
    .border-blue-100 { border-color: var(--brand-border) !important; }
    .text-blue-200 { color: rgba(255,255,255,.5) !important; }
    .text-blue-300 { color: rgba(255,255,255,.35) !important; }
    .text-blue-400 { color: #4a6fa5 !important; }
    .text-blue-500 { color: var(--brand) !important; }
    .text-blue-700 { color: var(--brand) !important; }

    /* Progress bar brand color */
    .bg-blue-500 { background-color: var(--brand) !important; }

    .custom-scrollbar::-webkit-scrollbar       { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>
</x-app-layout>