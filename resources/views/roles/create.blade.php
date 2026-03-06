<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl text-gray-800 leading-tight tracking-tight" style="font-weight:800;">Crear Nuevo Rol</h2>
            <a href="{{ route('roles.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-all"
               style="font-weight:500;">&larr; Volver a Roles</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/60 overflow-hidden">

                {{-- Header --}}
                <div class="bg-white px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                    <div class="p-3 rounded-xl" style="background:#eef1f8;">
                        <svg class="w-6 h-6" style="color:#121f48;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-gray-900" style="font-weight:800;">Nuevo Rol de Acceso</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Define el nombre y los permisos que tendrá este rol.</p>
                    </div>
                </div>

                <div class="p-8">

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

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        {{-- Nombre --}}
                        <div class="space-y-1.5 mb-8">
                            <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white @error('name') border-red-400 @enderror"
                                   placeholder="Ej: operador, supervisor, admin..."
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Permisos --}}
                        <div class="mb-8">
                            <label class="block text-[10px] text-gray-400 uppercase tracking-widest mb-3" style="font-weight:700;">
                                Asignar Permisos
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-5 rounded-2xl border"
                                 style="background:#eef1f8; border-color:#c8cedf;">
                                @foreach($permissions as $permission)
                                    <label for="perm_{{ $permission->id }}"
                                           class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-[#c8cedf] hover:bg-[#eef1f8]/50 transition-colors group">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               id="perm_{{ $permission->id }}"
                                               value="{{ $permission->name }}"
                                               class="rounded border-gray-300 shadow-sm"
                                               style="accent-color:#121f48;">
                                        <span class="text-xs text-gray-600 group-hover:text-gray-800 transition-colors" style="font-weight:600;">
                                            {{ $permission->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer --}}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Guardar Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>