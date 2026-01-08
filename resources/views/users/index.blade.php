<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Gestión de Usuarios</h1>
                    <p class="text-gray-500 text-sm mt-1">Administra las cuentas, roles y accesos al sistema.</p>
                </div>
                <a href="{{ route('users.create') }}" class="group bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-300 ease-in-out flex items-center">
                    <div class="bg-blue-500 group-hover:bg-blue-600 p-1 rounded-lg mr-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0 1 1 0 002 0zM16 9a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    Nuevo Usuario
                </a>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="relative w-full md:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Buscar usuario por nombre o email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white shadow-sm">
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold tracking-wider text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-5">Usuario / Email</th>
                                    <th class="px-6 py-5">Rol Asignado</th>
                                    <th class="px-6 py-5 text-center">Estado</th>
                                    <th class="px-6 py-5 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                <tr class="hover:bg-blue-50/30 transition duration-200 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm border border-blue-200 uppercase">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($user->roles->isNotEmpty())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm">
                                                {{ ucfirst($user->roles->first()->name) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-500">
                                                Sin Rol
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                            
                                            <a href="{{ route('users.edit', $user->id) }}" 
                                               class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition shadow-sm" 
                                               title="Editar Usuario">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            <form id="toggle-form-{{ $user->id }}" action="{{ route('users.toggle', $user->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                            
                                            @if($user->is_active)
                                                <button type="button" onclick="confirmToggle('{{ $user->id }}', 'desactivar')" 
                                                        class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-orange-500 hover:border-orange-300 hover:bg-orange-50 transition shadow-sm"
                                                        title="Desactivar Cuenta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                </button>
                                            @else
                                                <button type="button" onclick="confirmToggle('{{ $user->id }}', 'activar')" 
                                                        class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-green-600 hover:border-green-300 hover:bg-green-50 transition shadow-sm"
                                                        title="Activar Cuenta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </button>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $users->links() }}
                    </div>

                @else
                    <div class="p-12 text-center">
                        <div class="inline-block p-4 rounded-full bg-blue-50 mb-4">
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No hay usuarios registrados</h3>
                        <p class="mt-1 text-gray-500">Invita a miembros de tu equipo para comenzar.</p>
                        <div class="mt-6">
                            <a href="{{ route('users.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                + Crear Usuario
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Confirmación de Cambio de Estado (Toggle)
        function confirmToggle(id, action) {
            let titleText = action === 'desactivar' ? '¿Desactivar Usuario?' : '¿Activar Usuario?';
            let bodyText = action === 'desactivar' 
                ? 'El usuario perderá el acceso al sistema inmediatamente.' 
                : 'El usuario podrá volver a iniciar sesión.';
            let confirmColor = action === 'desactivar' ? '#F59E0B' : '#10B981'; // Naranja o Verde
            let buttonText = action === 'desactivar' ? 'Sí, desactivar' : 'Sí, activar';

            Swal.fire({
                title: titleText,
                text: bodyText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#d33',
                confirmButtonText: buttonText,
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('toggle-form-' + id).submit();
                }
            })
        }

        // 2. Notificaciones Toast (Éxito)
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
                title: "Error en la operación"
            });
        @endif
    </script>
</x-app-layout>