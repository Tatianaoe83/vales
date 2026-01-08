<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Unidad: {{ $unit->placa }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT') @if ($errors->any())
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                                    <select name="client_id" class="w-full border rounded-lg px-3 py-2">
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ $unit->client_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Placa</label>
                                    <input type="text" name="placa" value="{{ old('placa', $unit->placa) }}" class="w-full border rounded-lg px-3 py-2 uppercase">
                                </div>
                                
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo Vehículo</label>
                                    <select name="tipo_vehiculo" class="w-full border rounded-lg px-3 py-2">
                                        <option value="Olla" {{ $unit->tipo_vehiculo == 'Olla' ? 'selected' : '' }}>Olla</option>
                                        <option value="Volteo" {{ $unit->tipo_vehiculo == 'Volteo' ? 'selected' : '' }}>Volteo</option>
                                        <option value="Plataforma" {{ $unit->tipo_vehiculo == 'Plataforma' ? 'selected' : '' }}>Plataforma</option>
                                        <option value="Gondola" {{ $unit->tipo_vehiculo == 'Gondola' ? 'selected' : '' }}>Góndola</option>
                                    </select>
                                </div>

                                <div class="flex gap-4">
                                    <div class="w-2/3">
                                        <label class="block text-sm font-medium text-gray-700">Capacidad</label>
                                        <input type="number" step="0.01" name="capacidad_maxima" value="{{ old('capacidad_maxima', $unit->capacidad_maxima) }}" class="w-full border rounded-lg px-3 py-2">
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-sm font-medium text-gray-700">Unidad</label>
                                        <select name="unidad_medida" class="w-full border rounded-lg px-3 py-2">
                                            <option value="m3" {{ $unit->unidad_medida == 'm3' ? 'selected' : '' }}>m³</option>
                                            <option value="toneladas" {{ $unit->unidad_medida == 'toneladas' ? 'selected' : '' }}>Ton</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select name="is_active" class="w-full border rounded-lg px-3 py-2 bg-gray-50">
                                        <option value="1" {{ $unit->is_active ? 'selected' : '' }}>Activo (Unidad disponible)</option>
                                        <option value="0" {{ !$unit->is_active ? 'selected' : '' }}>Bloqueado (Unidad en mantenimento)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                            <a href="{{ route('units.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md">Actualizar Unidad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>