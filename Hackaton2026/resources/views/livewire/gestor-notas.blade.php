<div>
    <div class="max-w-6xl mx-auto p-6">

        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mis Notas</h2>
            <p class="text-gray-500 text-sm mt-1">Apunta ideas rápidas, recordatorios o fragmentos de código.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-10 max-w-2xl">
            <form wire:submit.prevent="guardarNota" class="space-y-4">
                <div>
                    <input type="text" wire:model="titulo" placeholder="Título (opcional)"
                        class="w-full border-none focus:ring-0 text-lg font-bold text-gray-800 placeholder-gray-400 bg-transparent px-0">
                </div>
                <div>
                    <textarea wire:model="contenido" placeholder="Escribe tu nota aquí..." rows="3"
                        class="w-full border-none focus:ring-0 text-gray-600 placeholder-gray-400 bg-transparent resize-none px-0"></textarea>
                    @error('contenido')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end pt-3 border-t border-gray-100 mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors text-sm shadow">
                        Guardar Nota
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($notas as $nota)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow relative group">

                    <button wire:click="eliminarNota({{ $nota->id }})"
                        class="absolute top-3 right-3 text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>

                    @if ($nota->titulo)
                        <h3 class="font-bold text-gray-800 text-lg mb-2 pr-6">{{ $nota->titulo }}</h3>
                    @endif

                    <p class="text-gray-600 text-sm whitespace-pre-wrap">{{ $nota->contenido }}</p>

                    <div class="mt-4 text-xs text-gray-400 font-medium">
                        {{ $nota->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500">
                    <p>No tienes notas aún. ¡Escribe la primera!</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
