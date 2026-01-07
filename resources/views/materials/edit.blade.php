<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Material</h2></x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800">Editar Información</h2>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-lg font-bold">ID: {{ $material->id }}</span>
                </div>
                <div class="p-8">
                    <form action="{{ route('materials.update', $material->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $material->name) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Código</label>
                                <input type="text" name="code" value="{{ old('code', $material->code) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unidad</label>
                                <input type="text" name="unit" value="{{ old('unit', $material->unit) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500">
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Precio ($)</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $material->price) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 font-mono text-lg font-bold text-green-700 bg-green-50">
                                <p class="text-xs text-gray-500 mt-1">Si cambias este valor, se guardará en el historial.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stock</label>
                                <input type="number" name="stock" value="{{ old('stock', $material->stock) }}" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500">
                            </div>
                            
                            <div class="col-span-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $material->is_active ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900">Material Activo</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('materials.index') }}" class="text-gray-500 px-4 py-2">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-md font-bold text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Historial de Precios
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-500">
                            <tr>
                                <th class="px-6 py-3">Fecha Cambio</th>
                                <th class="px-6 py-3">Precio</th>
                                <th class="px-6 py-3">Tendencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($history as $index => $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ $record->changed_at->format('d M Y, h:i A') }}
                                        @if($index === 0) 
                                            <span class="ml-2 text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full border border-green-200">Actual</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 font-mono font-bold text-gray-800">
                                        ${{ number_format($record->price, 2) }}
                                    </td>
                                    <td class="px-6 py-3">
                                        @if(isset($history[$index + 1]))
                                            @php
                                                $prev = $history[$index + 1]->price;
                                                $curr = $record->price;
                                            @endphp
                                            @if($curr > $prev)
                                                <span class="text-red-500 font-bold text-xs flex items-center">
                                                    ▲ Subió ${{ number_format($curr - $prev, 2) }}
                                                </span>
                                            @elseif($curr < $prev)
                                                <span class="text-green-500 font-bold text-xs flex items-center">
                                                    ▼ Bajó ${{ number_format($prev - $curr, 2) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">= Igual</span>
                                            @endif
                                        @else
                                            <span class="text-blue-500 text-xs font-semibold">Precio Inicial</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-400">No hay historial registrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>