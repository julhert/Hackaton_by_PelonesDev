{{-- DATOS DE PRUEBA --}}
{{-- BORREN DESDE @php HASTA @endphp CUANDO VAYAN A INSERTAR DATOS EN BACKEND, LUEGO SALEN ESTOS REGISTROS --}}
@php
    $tareas = [
        (object) [
            'id' => 1,
            'nombre' => 'Estructurar base de datos',
            'descripcion' => 'Crear las migraciones para usuarios y tareas en PostgreSQL.',
            'fecha_hora' => '2026-04-23 10:00',
            'tiempo_asignado' => 60,
            'importancia' => 'alta',
            'status' => 'por_hacer'
        ],
        (object) [
            'id' => 2,
            'nombre' => 'Diseñar presentación',
            'descripcion' => 'Armar las diapositivas para los jueces del hackatón.',
            'fecha_hora' => '2026-04-24 16:00',
            'tiempo_asignado' => 45,
            'importancia' => 'media',
            'status' => 'en_progreso'
        ],
        (object) [
            'id' => 3,
            'nombre' => 'Grabar demo del proyecto',
            'descripcion' => 'Hacer la captura de pantalla del flujo completo de la aplicación.',
            'fecha_hora' => '2026-04-25 12:00',
            'tiempo_asignado' => 30,
            'importancia' => 'baja',
            'status' => 'finalizado'
        ]
    ];
@endphp

<div x-data="{ modalAbierto: false, tareaActiva: null }" class="p-6 min-h-screen bg-gray-50">
    
    <div class="mb-8 flex justify-between items-center max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold text-gray-900">Lista de Tareas</h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow transition">
            + Nueva Tarea
        </button>
    </div>

    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tarea</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Límite</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tiempo</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prioridad</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tareas as $tarea)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $tarea->nombre }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 flex items-center gap-1">
                                    🗓 {{ $tarea->fecha_hora }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    ⏱ {{ $tarea->tiempo_asignado }} min
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tarea->importancia === 'alta')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alta</span>
                                @elseif($tarea->importancia === 'media')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Media</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baja</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tarea->status === 'por_hacer')
                                    <span class="text-sm font-medium text-gray-500">⏳ Por Hacer</span>
                                @elseif($tarea->status === 'en_progreso')
                                    <span class="text-sm font-medium text-blue-600">▶ En Progreso</span>
                                @else
                                    <span class="text-sm font-medium text-green-600">✔ Finalizado</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="tareaActiva = {{ json_encode($tarea) }}; modalAbierto = true" 
                                        class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100 transition">
                                    Ver detalles
                                </button>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="modalAbierto" 
         style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm px-4"
         x-transition>
        
        <div @click.away="modalAbierto = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-gray-800" x-text="tareaActiva?.nombre"></h3>
                <button @click="modalAbierto = false" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Descripción</h4>
                    <p class="text-gray-700" x-text="tareaActiva?.descripcion || 'Sin descripción detallada.'"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Fecha Límite</h4>
                        <p class="text-gray-800 font-medium" x-text="tareaActiva?.fecha_hora"></p>
                    </div>
                    <div>
                        <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Tiempo Estimado</h4>
                        <p class="text-gray-800 font-medium" x-text="tareaActiva?.tiempo_asignado + ' minutos'"></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <button @click="modalAbierto = false" class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-lg font-semibold transition">
                    Cerrar
                </button>
                
                <button x-show="tareaActiva?.status === 'por_hacer'" 
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow transition flex items-center gap-2">
                    <span>▶ Iniciar Tarea</span>
                </button>
            </div>

        </div>
    </div>

</div>