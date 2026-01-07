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
                                    <th class="px-6 py-5">Material</th>
                                    <th class="px-6 py-5">Unidad</th>
                                    <th class="px-6 py-5">Precio Actual</th>
                                    <th class="px-6 py-5">Stock</th>
                                    <th class="px-6 py-5 text-center">Estado</th>
                                    <th class="px-6 py-5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($materials as $material)
                                <tr class="hover:bg-blue-50/30 transition duration-200">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $material->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $material->code ?? 'S/C' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $material->unit }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-green-600 font-bold font-mono bg-green-50 px-2 py-1 rounded-md border border-green-200">
                                            ${{ number_format($material->price, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                        {{ $material->stock }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($material->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('materials.edit', $material->id) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">Editar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $materials->links() }}</div>
                @else
                    <div class="p-12 text-center text-gray-500">No hay materiales registrados aún.</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>