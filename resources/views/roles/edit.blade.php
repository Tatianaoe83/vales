<x-app-layout>
    <x-slot name="header">
        Editar Rol
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Editar Rol: <span class="text-blue-600">{{ $role->name }}</span></h2>

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nombre del Rol</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   value="{{ old('name', $role->name) }}" 
                                   required>
                            
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('roles.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                                Actualizar Rol
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>