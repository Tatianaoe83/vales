<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Flota de Transporte</h1>
                    <p class="text-gray-500 text-sm mt-1">Gestiona el acceso, capacidad y estado de las unidades.</p>
                </div>
                <button onclick="document.getElementById('modalCrear').classList.remove('hidden')" 
                        class="group bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 ease-in-out flex items-center cursor-pointer">
                    <div class="bg-blue-500 group-hover:bg-blue-600 p-1 rounded-lg mr-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                        </svg>
                    </div>
                    Nueva Unidad
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                @if($units->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold tracking-wider text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-5">Identificación / Chofer</th>
                                    <th class="px-6 py-5">Cliente (Dueño)</th>
                                    <th class="px-6 py-5">Tipo & Capacidad</th>
                                    <th class="px-6 py-5 text-center">Estado</th>
                                    <th class="px-6 py-5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($units as $unit)
                                <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-lg border border-indigo-200">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h12a1 1 0 001-1v-3a1 1 0 00-1-1H9z"></path></svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 font-mono tracking-wide">{{ $unit->placa }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    {{ $unit->nombre_chofer }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-700">{{ $unit->client->name ?? 'Sin Asignar' }}</div>
                                        <div class="text-xs text-gray-400">ID Cliente: {{ $unit->client_id }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-800">{{ $unit->tipo_vehiculo }}</span>
                                            <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200 w-fit">
                                                {{ $unit->capacidad_maxima }} {{ $unit->unidad_medida }}
                                            </span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        @if($unit->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Bloqueado
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                            
                                            <a href="{{ route('units.gafete', $unit->uuid) }}" target="_blank" 
                                               class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-gray-800 hover:border-gray-300 hover:bg-gray-50 transition shadow-sm" 
                                               title="Descargar Gafete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m1-4h6m-3-1v3m-2-10h6"></path></svg>
                                            </a>

                                            <a href="{{ route('units.edit', $unit->id) }}" 
                                               class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition shadow-sm" 
                                               title="Editar Unidad">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            <form id="delete-form-{{ $unit->id }}" action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $unit->id }}')" 
                                                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm" 
                                                    title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $units->links() }}
                    </div>

                @else
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h12a1 1 0 001-1v-3a1 1 0 00-1-1H9z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No hay unidades registradas</h3>
                        <p class="mt-1 text-gray-500">Comienza agregando tu primer camión al sistema.</p>
                        <div class="mt-6">
                            <button onclick="document.getElementById('modalCrear').classList.remove('hidden')" class="text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                                + Agregar Unidad
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-100">
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Registrar Nueva Unidad</h3>
                <button type="button" onclick="document.getElementById('modalCrear').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('units.store') }}" method="POST" class="p-6">
                @csrf
                
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm">
                        <p class="font-bold text-sm">Atención:</p>
                        <ul class="mt-1 list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Cliente (Dueño)</label>
                        <select name="client_id" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            <option value="">Seleccione un cliente...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Placa</label>
                            <input type="text" name="placa" value="{{ old('placa') }}" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm uppercase font-mono placeholder-gray-300 focus:ring-2 focus:ring-blue-500 transition" placeholder="Ej: ABC-123" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Chofer</label>
                            <input type="text" name="nombre_chofer" value="{{ old('nombre_chofer') }}" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm placeholder-gray-300 focus:ring-2 focus:ring-blue-500 transition" placeholder="Nombre completo" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Tipo</label>
                            <select name="tipo_vehiculo" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 transition">
                                <option value="Olla" {{ old('tipo_vehiculo') == 'Olla' ? 'selected' : '' }}>Olla</option>
                                <option value="Volteo" {{ old('tipo_vehiculo') == 'Volteo' ? 'selected' : '' }}>Volteo</option>
                                <option value="Plataforma" {{ old('tipo_vehiculo') == 'Plataforma' ? 'selected' : '' }}>Plataforma</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Capacidad</label>
                            <input type="number" step="0.01" name="capacidad_maxima" value="{{ old('capacidad_maxima') }}" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Unidad</label>
                            <select name="unidad_medida" class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 transition">
                                <option value="m3">m³</option>
                                <option value="toneladas">Ton</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modalCrear').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium transition">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-blue-500/30 text-sm font-bold transform active:scale-95 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Eliminar Unidad?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: "Por favor revisa el formulario"
            });
        @endif
    </script>
</x-app-layout>