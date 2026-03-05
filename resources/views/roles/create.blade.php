{{-- resources/views/roles/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Rol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Mostrar errores de validación --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        {{-- Nombre del Rol --}}
                        <div class="mb-6">
                            <label for="name" class="block font-medium text-sm text-gray-700">Nombre del Rol:</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        {{-- Permisos en formato Grid (Cuadrícula) --}}
                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Asignar Permisos:</label>
                            
                            {{-- Grid responsivo: 1 col en móviles, 3 en tablets, 4 en PC --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                                @foreach($permissions as $permission)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="permissions[]" id="perm_{{ $permission->id }}" value="{{ $permission->name }}" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label for="perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-600 cursor-pointer">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('roles.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                                Cancelar
                            </a>
                            <button type="submit" 
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Rol
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>