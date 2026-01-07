<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Material</h2></x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Detalles del Material</h2>
                </div>
                <div class="p-8">
                    <form action="{{ route('materials.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Material *</label>
                                <input type="text" name="name" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="Ej. Cemento Cruz Azul">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Código / SKU</label>
                                <input type="text" name="code" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej. MAT-001">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Unidad de Medida *</label>
                                <select name="unit" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="Pieza">Pieza</option>
                                    <option value="Saco">Saco</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Litro">Litro</option>
                                    <option value="Metro">Metro</option>
                                    <option value="M3">Metro Cúbico</option>
                                    <option value="Viaje">Viaje</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Precio Unitario ($) *</label>
                                <input type="number" step="0.01" name="price" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono" required placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Inicial</label>
                                <input type="number" name="stock" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" value="0">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción (Opcional)</label>
                                <textarea name="description" rows="2" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('materials.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg transition">Guardar Material</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>