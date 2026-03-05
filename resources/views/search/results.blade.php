<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Resultados de búsqueda para: <span class="text-indigo-600">"{{ $query }}"</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Resultados de Vales --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Vales ({{ $vales->count() }})</h3>
                    <i class="fas fa-ticket-alt text-gray-400"></i>
                </div>
                <div class="p-6">
                    @if($vales->isEmpty())
                        <p class="text-gray-500 text-sm">No se encontraron vales con ese folio.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($vales as $vale)
                                <li class="py-3">
                                    <span class="font-bold text-gray-800">Folio: {{ $vale->folio_vale }}</span> 
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $vale->estatus }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Resultados de Clientes --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold">Clientes ({{ $clients->count() }})</h3>
                    <i class="fas fa-users text-gray-400"></i>
                </div>
                <div class="p-6">
                    @if($clients->isEmpty())
                        <p class="text-gray-500 text-sm">No se encontraron clientes.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($clients as $client)
                                <li class="py-3">
                                    <span class="font-bold text-gray-800">{{ $client->name }}</span> 
                                    <span class="text-gray-500 text-sm ml-2">RFC: {{ $client->rfc }} | {{ $client->email }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Resultados de Materiales --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Materiales ({{ $materials->count() }})</h3>
                        <i class="fas fa-box text-gray-400"></i>
                    </div>
                    <div class="p-6">
                        @if($materials->isEmpty())
                            <p class="text-gray-500 text-sm">No se encontraron materiales.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($materials as $material)
                                    <li class="py-2">
                                        <span class="font-bold text-gray-800">{{ $material->name }}</span>
                                        <span class="text-gray-500 text-sm ml-2">Cód: {{ $material->code }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                {{-- Resultados de Unidades --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Unidades ({{ $units->count() }})</h3>
                        <i class="fas fa-truck text-gray-400"></i>
                    </div>
                    <div class="p-6">
                        @if($units->isEmpty())
                            <p class="text-gray-500 text-sm">No se encontraron unidades.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($units as $unit)
                                    <li class="py-2">
                                        <span class="font-bold text-gray-800">Placa: {{ $unit->placa }}</span>
                                        <span class="text-gray-500 text-sm ml-2">Vehículo: {{ $unit->tipo_vehiculo }}</span>
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