<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl text-gray-800 leading-tight tracking-tight" style="font-weight:800;">
                Editar Unidad
            </h2>
            <a href="{{ route('units.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-all"
               style="font-weight:500;">
                &larr; Volver a Flota
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">

                {{-- Card Header --}}
                <div class="bg-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-xl" style="background:#eef1f8;">
                            <svg class="w-6 h-6" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h12a1 1 0 001-1v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl text-gray-900" style="font-weight:800;">Información de la Unidad</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Actualiza los datos o el estado de la unidad.</p>
                        </div>
                    </div>
                    <span class="font-mono text-sm px-3 py-1.5 rounded-xl border"
                          style="font-weight:800; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                        {{ $unit->placa }}
                    </span>
                </div>

                <div class="p-8">
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-xl shadow-sm">
                                <p class="text-sm mb-1" style="font-weight:700;">Por favor corrige los siguientes errores:</p>
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Cliente --}}
                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Cliente (Dueño) <span class="text-red-500">*</span>
                                </label>
                                <select name="client_id" class="w-full border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm bg-gray-50/50 focus:bg-white transition" required>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $unit->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Placa --}}
                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Placa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="placa" value="{{ old('placa', $unit->placa) }}"
                                       class="w-full border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm uppercase font-mono bg-gray-50/50 focus:bg-white transition"
                                       placeholder="Ej: ABC-123" required>
                            </div>

                            {{-- Tipo Vehículo --}}
                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Tipo de Vehículo</label>
                                <select name="tipo_vehiculo" class="w-full border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm bg-gray-50/50 focus:bg-white transition">
                                    <option value="Olla"       {{ $unit->tipo_vehiculo == 'Olla'       ? 'selected' : '' }}>Olla</option>
                                    <option value="Volteo"     {{ $unit->tipo_vehiculo == 'Volteo'     ? 'selected' : '' }}>Volteo</option>
                                    <option value="Plataforma" {{ $unit->tipo_vehiculo == 'Plataforma' ? 'selected' : '' }}>Plataforma</option>
                                    <option value="Gondola"    {{ $unit->tipo_vehiculo == 'Gondola'    ? 'selected' : '' }}>Góndola</option>
                                </select>
                            </div>

                            {{-- Capacidad + Unidad --}}
                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Capacidad Máxima <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-3">
                                    <input type="number" step="0.01" name="capacidad_maxima"
                                           value="{{ old('capacidad_maxima', $unit->capacidad_maxima) }}"
                                           class="flex-1 border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm bg-gray-50/50 focus:bg-white transition"
                                           required>
                                    <select name="unidad_medida"
                                            class="w-28 border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm bg-gray-50/50 focus:bg-white transition">
                                        <option value="m3"        {{ $unit->unidad_medida == 'm3'        ? 'selected' : '' }}>m³</option>
                                        <option value="toneladas" {{ $unit->unidad_medida == 'toneladas' ? 'selected' : '' }}>Ton</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Estado --}}
                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Estado</label>
                                <select name="is_active" class="w-full border-gray-200 rounded-xl shadow-sm px-3 py-2.5 text-sm bg-gray-50/50 focus:bg-white transition">
                                    <option value="1" {{ $unit->is_active  ? 'selected' : '' }}>✅ Activo — Unidad disponible</option>
                                    <option value="0" {{ !$unit->is_active ? 'selected' : '' }}>🔒 Bloqueado — En mantenimiento</option>
                                </select>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-end gap-3 mt-10 pt-6 border-t border-gray-100">
                            <a href="{{ route('units.index') }}"
                               class="text-gray-500 hover:text-gray-800 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-colors text-sm"
                               style="font-weight:600;">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="text-white py-2.5 px-6 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2 text-sm"
                                    style="background:#121f48; font-weight:700;"
                                    onmouseover="this.style.background='#0d1633'"
                                    onmouseout="this.style.background='#121f48'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Actualizar Unidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>