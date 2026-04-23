<?php

namespace App\Livewire;

use App\Models\Nota;
use Livewire\Component;

class GestorNotas extends Component
{
    public $titulo = '';

    public $contenido = '';

    protected $rules = [
        'contenido' => 'required|min:3',
    ];

    public function guardarNota()
    {
        $this->validate();

        Nota::create([
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
        ]);

        // Limpiamos los campos
        $this->reset(['titulo', 'contenido']);

        // Lanzamos un mensajito de éxito discreto
        session()->flash('mensaje', 'Nota guardada con éxito.');
    }

    public function eliminarNota($id)
    {
        Nota::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.gestor-notas', [
            // Traemos las notas más recientes primero
            'notas' => Nota::latest()->get(),
        ])->layout('layouts.app');
    }
}
