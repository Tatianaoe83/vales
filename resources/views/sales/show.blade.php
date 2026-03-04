<x-app-layout>
    <div class="py-6 sm:py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <p class="font-bold">¡Venta Registrada Exitosamente!</p>
                    <p class="text-sm">Los vales han sido generados y el inventario actualizado.</p>
                </div>
                <a href="{{ route('sales.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-xs shadow whitespace-nowrap">
                    + Nueva Venta
                </a>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-5 sm:p-8">

                {{-- Header: cliente + total --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 border-b border-gray-100 pb-6 mb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800">{{ $sale->client->name }}</h1>
                        <p class="text-gray-500 text-sm mt-1">RFC: {{ $sale->client->rfc }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                Folio Venta: {{ $sale->folio }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $sale->tipo_venta == 'Credito' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-green-50 text-green-700 border-green-100' }} border">
                                {{ $sale->tipo_venta }}
                            </span>
                        </div>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-sm text-gray-400 uppercase font-bold">Total a Pagar</p>
                        <p class="text-3xl sm:text-4xl font-extrabold text-gray-900">${{ number_format($sale->total, 2) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Incluye IVA</p>
                    </div>
                </div>

                {{-- Vales --}}
                <h3 class="text-lg font-bold text-gray-800 mb-4">Vales Generados ({{ $sale->vales->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach($sale->vales as $vale)
                    <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center bg-gray-50 hover:bg-white hover:shadow-md transition">
                        <div class="min-w-0 mr-3">
                            <p class="text-xs font-bold text-gray-400 uppercase">Folio Vale</p>
                            <p class="text-lg font-mono font-bold text-gray-800 truncate">{{ $vale->folio_vale }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $vale->material->nombre }} - {{ $vale->cantidad }} {{ $vale->material->unidad_medida }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="block text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded-full mb-2 text-center">
                                {{ $vale->estatus }}
                            </span>
                            <a href="{{ route('units.gafete', $vale->uuid) }}" target="_blank" class="text-blue-600 text-xs font-bold hover:underline">
                                Ver QR
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Centro de Impresión --}}
                <div class="bg-gray-50 rounded-xl p-5 sm:p-6 border border-gray-200">
                    <div class="mb-4 sm:mb-0 sm:flex sm:justify-between sm:items-center gap-4">
                        <div class="mb-4 sm:mb-0">
                            <h4 class="font-bold text-gray-800">Centro de Impresión</h4>
                            <p class="text-sm text-gray-500">Selecciona el formato de salida deseado.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('sales.ticket', $sale->id) }}" target="_blank"
                               class="flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-5 rounded-xl shadow-lg transition transform hover:scale-105 text-sm text-center">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Imprimir Ticket (80mm)
                            </a>
                            <a href="{{ route('sales.email', $sale->id) }}"
                               class="flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-5 rounded-xl shadow-lg transition transform hover:scale-105 text-sm text-center">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Enviar por Correo
                            </a>
                            <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank"
                               class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-5 rounded-xl shadow-lg transition transform hover:scale-105 text-sm text-center">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Descargar PDF
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>