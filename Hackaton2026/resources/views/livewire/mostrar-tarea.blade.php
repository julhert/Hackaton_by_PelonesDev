<div x-data="{
    modalAbierto: false,
    tareaActiva: null,
    segundos: 0,
    corriendo: false,
    intervalo: null,
    mostrarAviso: false,
    mensaje: '',

    init() {
        window.addEventListener('mostrar-aviso', (e) => {
            // Asignamos el mensaje que viene de Livewire
            this.mensaje = e.detail.mensaje;
            this.mostrarAviso = true;

            // Consola para que tú veas si el evento llegó (presiona F12)
            console.log('Evento recibido:', e.detail.mensaje);

            // Se quita en 8 segundos
            setTimeout(() => { this.mostrarAviso = false; }, 8000);
        });
    },

    abrirDetalles(tarea) {
        if (this.corriendo && this.tareaActiva && this.tareaActiva.id === tarea.id) {
            this.modalAbierto = true;
            return;
        }

        this.tareaActiva = tarea;
        this.modalAbierto = true;
        this.corriendo = false;
        if(this.intervalo) clearInterval(this.intervalo);
    },

    iniciarCronometro() {
        if (this.corriendo || !this.tareaActiva.tiempoAsignado) return;

        let tiempoString = this.tareaActiva.tiempoAsignado.toString();
        let partes = tiempoString.split(':');

        if (partes.length === 3) {
            let h = parseInt(partes[0]) || 0;
            let m = parseInt(partes[1]) || 0;
            let s = parseInt(partes[2]) || 0;
            this.segundos = (h * 3600) + (m * 60) + s;
        } else {
            this.segundos = (parseInt(tiempoString) || 0) * 60;
        }

        if (this.segundos <= 0) return;

        this.corriendo = true;
        this.intervalo = setInterval(() => {
            if (this.segundos > 0) {
                this.segundos--;
            } else {
                clearInterval(this.intervalo);
                this.corriendo = false;
                alert('¡Tiempo terminado!');
                $wire.finalizarTarea(this.tareaActiva.id);

                this.modalAbierto = false;
            }
        }, 1000);
    },

    formatTime() {
        let h = Math.floor(this.segundos / 3600);
        let m = Math.floor((this.segundos % 3600) / 60);
        let s = this.segundos % 60;
        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
}" class="p-6 min-h-screen bg-gray-50">

<div wire:ignore>
    <div x-show="mostrarAviso"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;"
         class="bg-yellow-400 border-l-4 border-yellow-600 p-4 shadow-2xl rounded-lg animate-bounce">
        <div class="flex items-center">
            <span class="mr-2 text-xl">🔔</span>
            <p class="text-sm font-bold text-yellow-900" x-text="mensaje"></p>
            <button @click="mostrarAviso = false" class="ml-4 text-yellow-700 font-extrabold hover:text-yellow-900">&times;</button>
        </div>
    </div>
</div>

    <div class="mb-8 flex justify-between items-center max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold text-gray-900">Lista de Tareas</h1>
        <button @click="window.location.href= '{{ route('crearTarea') }}'"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow transition">
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
                                <div class="text-sm font-bold text-gray-900">{{ $tarea->nombreTarea }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 flex items-center gap-1">
                                    🗓 {{ $tarea->fechaHora }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600">
                                    ⏱ {{ $tarea->tiempoAsignado }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tarea->importancia == 1)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alta</span>
                                @elseif($tarea->importancia == 2)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Media</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baja</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(trim(strtolower($tarea->status)) === 'pendiente')
                                    <span class="text-sm font-medium text-gray-500">⏳ Por Hacer</span>
                                @elseif(trim(strtolower($tarea->status)) === 'en_progreso')
                                    <span class="text-sm font-medium text-blue-600">▶ En Progreso</span>
                                @else
                                    <span class="text-sm font-medium text-green-600">✔ Finalizado</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="abrirDetalles({{ json_encode($tarea) }})"
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

        <div @click.away="if(!corriendo) { modalAbierto = false }" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">

            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-gray-800" x-text="tareaActiva?.nombreTarea"></h3>

                <button x-show="!corriendo" @click="modalAbierto = false" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
            </div>

            <div class="p-6 space-y-4">

                <div x-show="corriendo" class="text-center pb-2">
                    <span class="text-xs font-bold text-red-500 uppercase tracking-widest animate-pulse">🔴 Modo Concentración Activo</span>
                </div>

                <div x-show="corriendo" class="text-center py-4 bg-indigo-50 rounded-xl mb-4">
                    <span class="text-4xl font-mono font-bold text-indigo-600" x-text="formatTime()"></span>
                </div>

                <div>
                    <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Descripción</h4>
                    <p class="text-gray-700" x-text="tareaActiva?.descripcion || 'Sin descripción detallada.'"></p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Fecha Límite</h4>
                        <p class="text-gray-800 font-medium" x-text="tareaActiva?.fechaHora"></p>
                    </div>
                    <div>
                        <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider mb-1">Tiempo Estimado</h4>
                        <p class="text-gray-800 font-medium" x-text="tareaActiva?.tiempoAsignado"></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">

                <button x-show="!corriendo" @click="modalAbierto = false" class="px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-lg font-semibold transition">
                    Cerrar
                </button>

                <button x-show="(tareaActiva?.status || '').toLowerCase().trim() === 'pendiente' && !corriendo"
                        @click="iniciarCronometro()"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold shadow transition flex items-center gap-2">
                    <span>▶ Iniciar Tarea</span>
                </button>
            </div>

        </div>
    </div>

</div>
