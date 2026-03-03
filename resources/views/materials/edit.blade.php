<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
                Editar Material
            </h2>
            <a href="{{ route('materials.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-all">
                &larr; Volver al catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden mb-8">
                
                <div class="bg-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Información del Material</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Actualiza los datos o cambia el estado del producto.</p>
                        </div>
                    </div>
                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full font-bold shadow-sm border border-gray-200">
                        ID: {{ $material->id }}
                    </span>
                </div>

                <div class="p-8">
                    <form action="{{ route('materials.update', $material->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Material <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $material->name) }}" 
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-shadow bg-gray-50/50 focus:bg-white @error('name') border-red-500 @enderror" required>
                                @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Código Interno</label>
                                <input type="text" name="code" value="{{ old('code', $material->code) }}" 
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-shadow bg-gray-50/50 focus:bg-white uppercase @error('code') border-red-500 @enderror">
                                @error('code') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unidad de Medida <span class="text-red-500">*</span></label>
                                <select name="unit" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-shadow bg-gray-50/50 focus:bg-white cursor-pointer @error('unit') border-red-500 @enderror" required>
                                    <option value="pza" {{ old('unit', $material->unit) == 'pza' ? 'selected' : '' }}>Pieza (Pza)</option>
                                    <option value="saco" {{ old('unit', $material->unit) == 'saco' ? 'selected' : '' }}>Saco</option>
                                    <option value="m3" {{ old('unit', $material->unit) == 'm3' ? 'selected' : '' }}>Metro Cúbico (M3)</option>
                                    <option value="ton" {{ old('unit', $material->unit) == 'ton' ? 'selected' : '' }}>Tonelada (Ton)</option>
                                </select>
                                @error('unit') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Precio Unitario ($) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-green-600 font-bold">$</span>
                                    </div>
                                    <input type="text" name="price" value="{{ old('price', $material->price) }}" 
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                        class="w-full pl-8 border-green-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-shadow bg-green-50/30 focus:bg-white font-mono font-bold text-green-700 @error('price') border-red-500 @enderror" required>
                                </div>
                                @error('price') 
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> 
                                @else 
                                    <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Si cambias el precio, se registrará en el historial.
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Actual</label>
                                <input type="text" name="stock" value="{{ old('stock', $material->stock) }}" 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-shadow bg-gray-50/50 focus:bg-white @error('stock') border-red-500 @enderror">
                                @error('stock') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="col-span-2 flex items-center justify-between p-4 bg-gray-50/50 border border-gray-100 rounded-xl">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Estado del Material</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Los materiales inactivos no aparecerán para ser seleccionados en nuevos registros.</p>
                                </div>
                                <label class="inline-flex items-center cursor-pointer relative">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $material->is_active) ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                            <a href="{{ route('materials.index') }}" class="text-gray-600 hover:text-gray-900 font-medium px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all active:scale-95 active:translate-y-0 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Actualizar Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">
                <div class="bg-gray-50/80 px-8 py-5 border-b border-gray-200/80 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Historial de Precios
                    </h3>
                    <span class="text-xs font-medium text-gray-500">Últimos movimientos</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-white border-b border-gray-100 text-xs uppercase font-bold text-gray-400 tracking-wider">
                            <tr>
                                <th class="px-8 py-4">Fecha del Cambio</th>
                                <th class="px-8 py-4">Precio Registrado</th>
                                <th class="px-8 py-4">Tendencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($history as $index => $record)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-gray-900 font-medium">{{ $record->changed_at->format('d M Y') }}</span>
                                            <span class="text-gray-400 ml-2 text-xs">{{ $record->changed_at->format('h:i A') }}</span>
                                            @if($index === 0) 
                                                <span class="ml-3 text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full border border-green-200 font-bold uppercase tracking-wide">Actual</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="font-mono font-bold text-gray-900 text-base">${{ number_format($record->price, 2) }}</span>
                                    </td>
                                    <td class="px-8 py-4">
                                        @if(isset($history[$index + 1]))
                                            @php
                                                $prev = $history[$index + 1]->price;
                                                $curr = $record->price;
                                            @endphp
                                            @if($curr > $prev)
                                                <span class="inline-flex items-center gap-1 text-red-600 font-bold text-xs bg-red-50 px-2.5 py-1 rounded-md border border-red-100">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                                                    Subió ${{ number_format($curr - $prev, 2) }}
                                                </span>
                                            @elseif($curr < $prev)
                                                <span class="inline-flex items-center gap-1 text-green-600 font-bold text-xs bg-green-50 px-2.5 py-1 rounded-md border border-green-100">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    Bajó ${{ number_format($prev - $curr, 2) }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-gray-500 font-bold text-xs bg-gray-50 px-2.5 py-1 rounded-md border border-gray-200">
                                                    <div class="w-2 h-0.5 bg-gray-400 rounded"></div>
                                                    Sin cambio
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 text-blue-600 font-bold text-xs bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                Precio Inicial
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-sm font-medium">No hay historial registrado para este material.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>