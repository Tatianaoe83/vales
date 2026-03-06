<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl text-gray-800 leading-tight" style="font-weight:800;">
            Resultados de búsqueda para:
            <span class="ml-1" style="color:#121f48;">"{{ $query }}"</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family:'Inter',sans-serif;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Vales --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:800;">
                        Vales
                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">{{ $vales->count() }}</span>
                    </h3>
                    <i class="fas fa-ticket-alt text-gray-300"></i>
                </div>
                <div class="p-6">
                    @if($vales->isEmpty())
                        <p class="text-gray-400 text-sm">No se encontraron vales con ese folio.</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($vales as $vale)
                                <li class="py-3 flex items-center gap-3">
                                    <span class="text-sm text-gray-800" style="font-weight:700;">Folio: {{ $vale->folio_vale }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full border"
                                          style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">
                                        {{ $vale->estatus }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Clientes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:800;">
                        Clientes
                        <span class="ml-2 text-xs px-2 py-0.5 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">{{ $clients->count() }}</span>
                    </h3>
                    <i class="fas fa-users text-gray-300"></i>
                </div>
                <div class="p-6">
                    @if($clients->isEmpty())
                        <p class="text-gray-400 text-sm">No se encontraron clientes.</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach($clients as $client)
                                <li class="py-3">
                                    <span class="text-sm text-gray-800" style="font-weight:700;">{{ $client->name }}</span>
                                    <span class="text-gray-400 text-xs ml-2">RFC: {{ $client->rfc }} · {{ $client->email }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Materiales --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:800;">
                            Materiales
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">{{ $materials->count() }}</span>
                        </h3>
                        <i class="fas fa-box text-gray-300"></i>
                    </div>
                    <div class="p-6">
                        @if($materials->isEmpty())
                            <p class="text-gray-400 text-sm">No se encontraron materiales.</p>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($materials as $material)
                                    <li class="py-2.5">
                                        <span class="text-sm text-gray-800" style="font-weight:700;">{{ $material->name }}</span>
                                        <span class="text-gray-400 text-xs ml-2">Cód: {{ $material->code }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- Unidades --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-sm text-gray-800 uppercase tracking-wide" style="font-weight:800;">
                            Unidades
                            <span class="ml-2 text-xs px-2 py-0.5 rounded-full border" style="font-weight:700; color:#121f48; background:#eef1f8; border-color:#c8cedf;">{{ $units->count() }}</span>
                        </h3>
                        <i class="fas fa-truck text-gray-300"></i>
                    </div>
                    <div class="p-6">
                        @if($units->isEmpty())
                            <p class="text-gray-400 text-sm">No se encontraron unidades.</p>
                        @else
                            <ul class="divide-y divide-gray-100">
                                @foreach($units as $unit)
                                    <li class="py-2.5">
                                        <span class="text-sm text-gray-800" style="font-weight:700;">Placa: {{ $unit->placa }}</span>
                                        <span class="text-gray-400 text-xs ml-2">{{ $unit->tipo_vehiculo }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>