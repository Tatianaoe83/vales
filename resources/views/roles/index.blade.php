<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Perfiles y Accesos</h1>
                    <p class="text-gray-500 text-sm mt-1">Define los roles y permisos de seguridad del sistema.</p>
                </div>
                <a href="{{ route('roles.create') }}" class="group bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 ease-in-out flex items-center">
                    <div class="bg-blue-500 group-hover:bg-blue-600 p-1 rounded-lg mr-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    Nuevo Rol
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold tracking-wider text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-5 w-24">ID</th>
                                    <th class="px-6 py-5 w-48">Nombre del Rol</th>
                                    <th class="px-6 py-5">Permisos Asignados</th>
                                    <th class="px-6 py-5 text-right w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($roles as $role)
                                <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-400 font-mono">#{{ $role->id }}</div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-800">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($role->permissions as $permission)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                                    {{ $permission->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    Sin permisos
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                            
                                            <a href="{{ route('roles.edit', $role->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition shadow-sm" title="Editar Rol">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $role->id }}')" 
                                                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm" 
                                                    title="Eliminar Rol">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($roles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $roles->links() }}
                    </div>
                    @endif

                @else
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-indigo-50 mb-4">
                            <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No hay roles definidos</h3>
                        <p class="mt-1 text-gray-500">Crea el primer rol para asignar permisos a los usuarios.</p>
                        <div class="mt-6">
                            <a href="{{ route('roles.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                + Crear Primer Rol
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Eliminar Rol?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444', // Rojo Tailwind
                cancelButtonColor: '#3085d6', // Azul
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: "Ocurrió un error en la operación"
            });
        @endif
    </script>
</x-app-layout>