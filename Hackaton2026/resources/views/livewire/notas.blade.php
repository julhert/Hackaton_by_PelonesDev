@php
    // MOCK DATA: Solo título y contenido
    $notas = [
        (object) [
            'id' => 1,
            'titulo' => 'Comandos de Docker',
            'contenido' => "Levantar: docker-compose up -d\nDetener: docker-compose down"
        ],
        (object) [
            'id' => 2,
            'titulo' => 'Ideas para los Jueces',
            'contenido' => 'Mencionar cómo el diseño minimalista reduce la carga cognitiva del estudiante.'
        ],
        (object) [
            'id' => 3,
            'titulo' => 'Pendientes del proyecto',
            'contenido' => 'Revisar que no se rompa la tabla cuando se acabe el tiempo del cronómetro en la vista principal.'
        ]
    ];
@endphp

<div x-data="{ modalNota: false }" class="p-6 min-h-screen bg-gray-50">
    
    <div class="mb-8 flex justify-between items-center max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold text-gray-900">Mis Notas</h1>
        <button @click="modalNota = true" class="bg-gray-900 hover:bg-gray-800 text-white font-bold py-2 px-5 rounded-lg shadow transition">
            + Nueva Nota
        </button>
    </div>

    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @foreach($notas as $nota)
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition duration-200">
                    <h3 class="font-bold text-gray-800 text-lg mb-3">{{ $nota->titulo }}</h3>
                    <p class="text-gray-600 text-sm whitespace-pre-wrap leading-relaxed">{!! nl2br(e($nota->contenido)) !!}</p>
                </div>
            @endforeach

        </div>
    </div>

    <div x-show="modalNota" 
         style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm px-4"
         x-transition>
        
        <div @click.away="modalNota = false" class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
            
            <div class="p-6">
                <input type="text" placeholder="Título" class="w-full border-b border-gray-200 text-xl font-bold text-gray-800 focus:border-blue-500 focus:ring-0 px-0 py-2 mb-4 placeholder-gray-400 transition">
                
                <textarea rows="6" placeholder="Escribe tu nota aquí..." class="w-full border-none resize-none focus:ring-0 px-0 py-2 text-gray-700 placeholder-gray-400"></textarea>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <button @click="modalNota = false" class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-lg font-semibold transition">
                    Cancelar
                </button>
                <button @click="modalNota = false" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-bold shadow transition">
                    Guardar
                </button>
            </div>
            
        </div>
    </div>

</div>