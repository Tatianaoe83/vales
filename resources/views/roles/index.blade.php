<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl text-gray-800 tracking-tight" style="font-weight:800;">Perfiles y Accesos</h1>
                    <p class="text-gray-500 text-sm mt-1">Define los roles y permisos de seguridad del sistema.</p>
                </div>
                <a href="{{ route('roles.create') }}"
                   class="group text-white py-2.5 px-5 rounded-xl shadow-lg transition-all duration-200 flex items-center"
                   style="background:#121f48;"
                   onmouseover="this.style.background='#0d1633'"
                   onmouseout="this.style.background='#121f48'">
                    <div class="p-1 rounded-lg mr-2" style="background:rgba(255,255,255,.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span style="font-weight:700;">Nuevo Rol</span>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">

                @if($roles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] text-gray-400 uppercase tracking-widest bg-gray-50 border-b border-gray-100" style="font-weight:800;">
                                    <th class="px-6 py-5 w-24">ID</th>
                                    <th class="px-6 py-5 w-48">Nombre del Rol</th>
                                    <th class="px-6 py-5">Permisos Asignados</th>
                                    <th class="px-6 py-5 text-right w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($roles as $role)
                                <tr class="hover:bg-gray-50/80 transition duration-150 group">

                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-400 font-mono">#{{ $role->id }}</div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center mr-3 border"
                                                 style="background:#eef1f8; border-color:#c8cedf; color:#121f48;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-800" style="font-weight:700;">{{ ucfirst($role->name) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($role->permissions as $permission)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] border"
                                                      style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                                                    {{ $permission->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Sin permisos
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-3 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                               class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 transition shadow-sm"
                                               title="Editar Rol"
                                               onmouseover="this.style.background='#eef1f8'; this.style.borderColor='#c8cedf'; this.style.color='#121f48';"
                                               onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb'; this.style.color='#9ca3af';">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                            <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                    onclick="confirmDelete('{{ $role->id }}')"
                                                    class="p-2 bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-red-600 hover:border-red-300 hover:bg-red-50 transition shadow-sm"
                                                    title="Eliminar Rol">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
                        <div class="inline-block p-4 rounded-2xl mb-4" style="background:#eef1f8;">
                            <svg class="w-12 h-12" style="color:#121f48; opacity:.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg text-gray-900" style="font-weight:700;">No hay roles definidos</h3>
                        <p class="mt-1 text-sm text-gray-500">Crea el primer rol para asignar permisos a los usuarios.</p>
                        <div class="mt-6">
                            <a href="{{ route('roles.create') }}" class="text-sm hover:underline" style="color:#121f48; font-weight:700;">
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
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#121f48',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: { popup: 'rounded-xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        const Toast = Swal.mixin({
            toast: true, position: 'top-end',
            showConfirmButton: false, timer: 3000, timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        @if (session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif
        @if ($errors->any())
            Toast.fire({ icon: 'error', title: "Ocurrió un error en la operación" });
        @endif
    </script>
</x-app-layout>