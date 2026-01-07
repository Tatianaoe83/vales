<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                
                <div class="bg-gray-50 px-8 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Información General</h2>
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Nuevo Registro</span>
                </div>

                <div class="p-8">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Razón Social / Nombre *</label>
                                <input type="text" 
                                       name="name" 
                                       class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200 @error('name') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                                       value="{{ old('name') }}"
                                       placeholder="Ej. Distribuidora del Norte S.A. de C.V."
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">RFC</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="rfc" 
                                           class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 uppercase transition duration-200 pl-10 @error('rfc') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror" 
                                           maxlength="13"
                                           minlength="12"
                                           placeholder="XAXX010101000"
                                           oninput="this.value = this.value.toUpperCase()"
                                           value="{{ old('rfc') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.95 2-2.5 2H11" />
                                        </svg>
                                    </div>
                                </div>
                                @error('rfc')
                                    <p class="text-red-600 text-xs font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                                <div class="relative">
                                    <input type="text" 
                                           name="phone" 
                                           class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200 pl-10 @error('phone') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                           maxlength="15"
                                           placeholder="999 123 4567"
                                           value="{{ old('phone') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                </div>
                                @error('phone')
                                    <p class="text-red-600 text-xs font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                                <div class="relative">
                                    <input type="email" 
                                           name="email" 
                                           class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200 pl-10 @error('email') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                           placeholder="contacto@empresa.com"
                                           value="{{ old('email') }}">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="text-red-600 text-xs font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección Fiscal / Física</label>
                                <textarea name="address" 
                                          rows="3" 
                                          class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200"
                                          placeholder="Calle, Número, Colonia, Ciudad...">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('clients.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2 transition duration-200">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                                Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>