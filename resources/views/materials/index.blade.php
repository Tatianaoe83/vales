<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Catálogo de Materiales</h1>
                    <p class="text-gray-500 text-sm mt-1">Gestiona precios, unidades y existencias.</p>
                </div>
                <a href="{{ route('materials.create') }}" class="group bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 flex items-center">
                    <div class="bg-blue-500 group-hover:bg-blue-600 p-1 rounded-lg mr-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    Nuevo Material
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                @if($materials->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold tracking-wider text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
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
                                <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-lg">
                                                {{ substr($material->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $material->name }}</div>
                                                <div class="text-xs text-gray-400 font-mono">{{ $material->code ?? 'S/C' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-md bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $material->unit }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center text-green-700 font-bold font-mono bg-green-50 px-3 py-1.5 rounded-lg border border-green-200 w-fit">
                                            <span>$</span>
                                            <span class="ml-1">{{ number_format($material->price, 2) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-700">
                                            {{ $material->stock }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($material->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-800 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Baja
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            
                                            <a href="{{ route('materials.edit', $material->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition shadow-sm" title="Editar y Ver Historial">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            @if($material->is_active)
                                                <form id="delete-material-{{ $material->id }}" action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                
                                                <button type="button" 
                                                        onclick="confirmDelete('delete-material-{{ $material->id }}')" 
                                                        class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm" 
                                                        title="Dar de baja">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            @else
                                                <button disabled class="p-2 bg-gray-50 border border-gray-100 rounded-lg text-gray-300 cursor-not-allowed">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
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
                        <div class="inline-block p-4 rounded-full bg-indigo-50 mb-4">
                            <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No hay materiales registrados</h3>
                        <p class="mt-1 text-gray-500">Comienza a construir tu catálogo de productos.</p>
                        <div class="mt-6">
                            <a href="{{ route('materials.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                + Agregar Material
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>