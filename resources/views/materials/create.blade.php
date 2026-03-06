<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl text-gray-800 leading-tight tracking-tight" style="font-weight:800;">
                Nuevo Material
            </h2>
            <a href="{{ route('materials.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors" style="font-weight:500;">
                &larr; Volver al catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">

                <div class="bg-white px-8 py-6 border-b border-gray-100 flex items-center gap-3">
                    <div class="p-2 rounded-xl" style="background:#eef1f8;">
                        <svg class="w-6 h-6" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-gray-900" style="font-weight:800;">Detalles del Material</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Ingresa la información básica para registrar un nuevo producto en el inventario.</p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('materials.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">

                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Nombre del Material <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name"
                                       class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white"
                                       required placeholder="Ej. Cemento Portland Cruz Azul">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Código Interno</label>
                                <input type="text" name="code"
                                       class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white uppercase"
                                       placeholder="Ej. MAT-001">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Unidad de Medida <span class="text-red-500">*</span>
                                </label>
                                <select name="unit" class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white cursor-pointer">
                                    <option value="" disabled selected>Selecciona una unidad...</option>
                                    <option value="pza">Pieza (Pza)</option>
                                    <option value="saco">Saco</option>
                                    <option value="m3">Metro Cúbico (M3)</option>
                                    <option value="ton">Tonelada (Ton)</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Precio Unitario ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-500" style="font-weight:600;">$</span>
                                    </div>
                                    <input type="number" step="0.01" name="price" min="0"
                                           class="w-full pl-8 border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white font-mono text-gray-900"
                                           required placeholder="0.00">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Stock Inicial</label>
                                <input type="number" name="stock" min="1" step="1"
                                       class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white"
                                       value="1">
                                <p class="text-xs text-gray-400 mt-1">Cantidad inicial en inventario.</p>
                            </div>

                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Descripción (Opcional)</label>
                                <textarea name="description" rows="3"
                                          class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white resize-none"
                                          placeholder="Agrega detalles adicionales sobre este material..."></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
                            <a href="{{ route('materials.index') }}"
                               class="text-gray-500 hover:text-gray-800 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-colors text-sm"
                               style="font-weight:600;">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="text-white py-2.5 px-6 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2 text-sm"
                                    style="background:#121f48; font-weight:700;"
                                    onmouseover="this.style.background='#0d1633'"
                                    onmouseout="this.style.background='#121f48'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Guardar Material
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>