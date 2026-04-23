<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tarea;

class MostrarTarea extends Component
{
    public function render()
    {
        // 1. Buscamos la tarea pendiente más cercana en el tiempo que NO haya pasado hace más de 10 min
        $proxima = Tarea::where('status', 'pendiente')
            ->where('fechaHora', '>=', now()->subMinutes(10))
            ->orderBy('fechaHora', 'asc') // CRUCIAL: Ordenar por la más cercana
            ->first();

        if ($proxima) {
            $minutos = now()->diffInMinutes($proxima->fechaHora, false);

            // 2. Solo notificamos si está dentro del rango de 30 minutos
            if ($minutos <= 30) {
                $texto = $minutos <= 0
                    ? "¡Atención! La tarea '{$proxima->nombreTarea}' ya debería haber iniciado."
                    : "La tarea '{$proxima->nombreTarea}' inicia en {$minutos} minutos.";

                $this->dispatch('mostrar-aviso', mensaje: $texto);
            }
        }

        return view('livewire.mostrar-tarea', [
            'tareas' => Tarea::orderBy('importancia', 'asc')
                ->orderBy('fechaHora', 'asc')
                ->get(),
        ]);
    }

    public function finalizarTarea($tareaId)
    {
        $tarea = Tarea::find($tareaId);
        if ($tarea) {
            $tarea->status = 'finalizado';
            $tarea->save();
        }

        $this->dispatch('notify', 'Tarea finalizada con exito');
    }
}
