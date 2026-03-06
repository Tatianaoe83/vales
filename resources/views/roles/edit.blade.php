<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl text-gray-800 leading-tight tracking-tight" style="font-weight:800;">Editar Rol</h2>
            <a href="{{ route('roles.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-all"
               style="font-weight:500;">&larr; Volver a Roles</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">

                {{-- Header --}}
                <div class="bg-white px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <div class="p-3 rounded-xl" style="background:#eef1f8;">
                        <svg class="w-6 h-6" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-gray-900" style="font-weight:800;">Editar Rol</h2>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Modificando: <span class="font-mono" style="color:#121f48; font-weight:700;">{{ $role->name }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-1.5 mb-8">
                            <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $role->name) }}"
                                   class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white @error('name') border-red-400 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('roles.index') }}"
                               class="text-gray-500 hover:text-gray-800 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-colors text-sm"
                               style="font-weight:600;">Cancelar</a>
                            <button type="submit"
                                    class="text-white py-2.5 px-6 rounded-xl shadow-sm transition-all active:scale-95 flex items-center gap-2 text-sm"
                                    style="background:#121f48; font-weight:700;"
                                    onmouseover="this.style.background='#0d1633'"
                                    onmouseout="this.style.background='#121f48'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Actualizar Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>