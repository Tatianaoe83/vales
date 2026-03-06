<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight" style="font-weight:800;">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base text-gray-800" style="font-weight:800;">Editar Información</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Actualiza los datos del cliente</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs border"
                          style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                        ID: {{ $client->id }}
                    </span>
                </div>

                <div class="p-8">
                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">
                                    Razón Social / Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name"
                                       class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white @error('name') border-red-400 @enderror"
                                       value="{{ old('name', $client->name) }}" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">RFC</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H11"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="rfc"
                                           class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white uppercase pl-10 @error('rfc') border-red-400 @enderror"
                                           maxlength="13" placeholder="LOGJ580812RH7"
                                           oninput="this.value = this.value.toUpperCase()"
                                           value="{{ old('rfc', $client->rfc) }}">
                                </div>
                                @error('rfc')
                                    <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Teléfono</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="phone"
                                           class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white pl-10 @error('phone') border-red-400 @enderror"
                                           value="{{ old('phone', $client->phone) }}">
                                </div>
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Correo Electrónico</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input type="email" name="email"
                                           class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white pl-10 @error('email') border-red-400 @enderror"
                                           value="{{ old('email', $client->email) }}">
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1" style="font-weight:600;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2 space-y-1.5">
                                <label class="block text-[10px] text-gray-400 uppercase tracking-widest" style="font-weight:700;">Dirección</label>
                                <textarea name="address" rows="3"
                                          class="w-full border-gray-200 rounded-xl shadow-sm transition bg-gray-50/50 focus:bg-white resize-none"
                                          placeholder="Calle, Número, Colonia...">{{ old('address', $client->address) }}</textarea>
                            </div>

                            {{-- Toggle Estado --}}
                            <div class="col-span-2 p-4 rounded-xl border flex items-center justify-between"
                                 style="background:#eef1f8; border-color:#c8cedf;">
                                <div>
                                    <span class="block text-sm text-gray-800" style="font-weight:700;">Estado del Cliente</span>
                                    <span class="block text-xs text-gray-500 mt-0.5">Controla si este cliente puede operar en el sistema.</span>
                                </div>
                                <label class="inline-flex items-center cursor-pointer relative">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                           {{ $client->is_active ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-gray-300 rounded-full peer
                                                peer-checked:after:translate-x-full peer-checked:after:border-white
                                                after:content-[''] after:absolute after:top-[2px] after:left-[4px]
                                                after:bg-white after:border-gray-300 after:border after:rounded-full
                                                after:h-6 after:w-6 after:transition-all shadow-inner
                                                peer-checked:bg-[#121f48]"></div>
                                </label>
                            </div>

                        </div>

                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('clients.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-100 transition"
                               style="font-weight:600;">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="text-white py-2.5 px-6 rounded-xl shadow-lg transition-all active:scale-95 text-sm"
                                    style="background:#121f48; font-weight:700;"
                                    onmouseover="this.style.background='#0d1633'"
                                    onmouseout="this.style.background='#121f48'">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>